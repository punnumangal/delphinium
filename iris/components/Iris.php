<?php
/**
 * Copyright (C) 2012-2016 Project Delphinium - All Rights Reserved
 *
 * This file is subject to the terms and conditions defined in
 * file 'https://github.com/ProjectDelphinium/delphinium/blob/master/EULA',
 * which is part of this source code package.
 *
 * NOTICE:  All information contained herein is, and remains the property of Project Delphinium. The intellectual and technical concepts contained
 * herein are proprietary to Project Delphinium and may be covered by U.S. and Foreign Patents, patents in process, and are protected by trade secret or copyright law.
 * Dissemination of this information or reproduction of this material is strictly forbidden unless prior written permission is obtained
 * from Project Delphinium.
 *
 * THE RECEIPT OR POSSESSION OF THIS SOURCE CODE AND/OR RELATED INFORMATION DOES NOT CONVEY OR IMPLY ANY RIGHTS
 * TO REPRODUCE, DISCLOSE OR DISTRIBUTE ITS CONTENTS, OR TO MANUFACTURE, USE, OR SELL ANYTHING THAT IT  MAY DESCRIBE, IN WHOLE OR IN PART.
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Non-commercial use only, you may not charge money for the software
 * You can modify personal copy of source-code but cannot distribute modifications
 * You may not distribute any version of this software, modified or otherwise
 */

namespace Delphinium\Iris\Components;

use Delphinium\Stem\Models\Home as IrisCharts;
use Delphinium\Roots\Roots;
use Delphinium\Roots\RequestObjects\ModulesRequest;
use Delphinium\Roots\Enums\ActionType;
use Cms\Classes\ComponentBase;

class Iris extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Iris Chart',
            'description' => 'This chart displays a course\'s modules and the student\'s progress in them'
        ];
    }

    public function onRun()
    {
        $this->addJs("/plugins/delphinium/iris/assets/javascript/d3.v3.min.js");
        $this->addJs("/plugins/delphinium/iris/assets/javascript/iris.js");
        $this->addCss("/plugins/delphinium/iris/assets/css/main.css");

    }
    public function onRender()
    {
        try
        {
            if(!isset($_SESSION))
            {
                session_start();
            }

            $courseId = $_SESSION['courseID'];
            $this->page['courseId'] = $courseId;
            $this->page['userId'] = $_SESSION['userID'];

            //Filter by parent node if it has been configured
            $defaultNode = 1;
            $filter = $this->property('filter',$defaultNode);
            $this->page['filter'] = $filter;
            $finalData=array();

            $freshData = false;
            $req = new ModulesRequest(ActionType::GET, null, null, true, true, null, null , $freshData);

            $roots = new Roots();
            $moduleData = $roots->modules($req);

            $this->page['rawData'] = json_encode($moduleData);
            $modArr = $moduleData->toArray();
            if($filter===$defaultNode)
            {///get all items
                $finalData = $this->buildTree($modArr,1);
            }
            else
            {//filter by node
                $filterObj = array_filter(
                    $modArr,
                    function ($e) use ($filter) {
                        return intval($e['module_id']) === intval($filter);
                    }
                );

                $obj = array_shift($filterObj);
                $finalData = $this->buildTree($modArr,$obj['parent_id'], $filter);

            }
            $this->page['graphData'] = json_encode($finalData);

        }
        catch(\Delphinium\Roots\Exceptions\InvalidRequestException $e)
        {
            if($e->getCode()==401)//meaning there are two professors and one is trying to access the other professor's grades
            {
                return;
            }
            else
            {
                return \Response::make($this->controller->run('error'), 500);
            }
        }
        catch (\GuzzleHttp\Exception\ClientException $e) {
            return;
        }
        catch(Delphinium\Roots\Exceptions\NonLtiException $e)
        {
            if($e->getCode()==584)
            {
                return \Response::make($this->controller->run('nonlti'), 500);
            }
        }
        catch(\Exception $e)
        {
            if($e->getMessage()=='Invalid LMS')
            {
                return \Response::make($this->controller->run('nonlti'), 500);
            }
            return \Response::make($this->controller->run('error'), 500);
        }
    }

    public function defineProperties()
    {
        return [
            'filter' => [
                'title'   => 'Filter',
                'description' => 'Display only this module and its children in Iris',
                'placeholder' => 'Select a parent node',
                'type'    => 'dropdown'
            ]
        ];
    }

    public function getFilterOptions()
    {
        $req = new ModulesRequest(ActionType::GET, null, null, true,
            true, null, null , false);
        $roots = new Roots();
        $moduleData = $roots->modules($req);
        $arr = $moduleData->toArray();

        $tree = $this->buildTree($arr, 1);
        $dash = "";
        $result = array();
        $result[$tree[0]['module_id']] = "({$tree[0]['name']})";

        foreach($tree as $item)
        {
            $this->recursion($item['children'], $dash, $result);
        }
        return $result;
    }


    private function recursion($children, &$dash, &$res)
    {
        foreach($children as $item)
        {
            $res[$item['module_id']] = $dash." ".$item['name'];
            if(sizeof($item['children'])>=1)
            {
                $newDash = $dash."-";
                $this->recursion($item['children'], $newDash, $res);
            }
        }
    }

    private function buildTree(array &$elements, $parentId = 1, $moduleFilter=null) {
        $branch = array();
        foreach ($elements as $key=>$module) {
            if($module['published'] == "1")//if not published don't include it
            {
                if(!is_null($moduleFilter)&&($module['module_id']!=$moduleFilter))
                {//if we have a filter and this module doesn't match the filter, skip the item

                    unset($elements[$module['module_id']]);
                    continue;
                }
                if ($module['parent_id'] == $parentId) {
                    $children = $this->buildTree($elements, $module['module_id']);
                    if ($children) {
                        $module['children'] = $children;
                    }
                    else
                    {
                        $module['children'] = array();
                    }
                    $branch[] = $module;
                    unset($elements[$module['module_id']]);
                }
            }
        }

        return $branch;
    }



}