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

namespace Delphinium\Blossom\Components;

use Cms\Classes\ComponentBase;
use Delphinium\Blossom\Models\Experience as ExperienceModel;
use Delphinium\Blossom\Components\Experience as ExperienceComponent;

class Bonus extends ComponentBase {

    public function componentDetails() {
        return [
            'name' => 'Bonus',
            'description' => 'Displays bonus'
        ];
    }

    public function defineProperties() {
        return [
            'Experience' => [
                'title' => 'Experience instance',
                'description' => 'Select the experience instance to display the student\'s bonus and penalties',
                'type' => 'dropdown'
            ],
            'Size' => [
                'title' => 'Widget Size',
                'description' => 'Enter the size of the component (as a percentage, no sign)',
                'type' => 'string',
                'default' => '100',
                'validationPattern' => '^([1-9]|[1-9][0-9]|[1][0-9][0-9]|20[0-0])$',
                'validationMessage' => 'A number between 1 and 200 is required',
                'placeholder' => 'Enter a number w/o sign'
            ]
        ];
    }

    public function getExperienceOptions() {
        $instances = ExperienceModel::all();

        if (count($instances) === 0) {
            return $array_dropdown = ["0" => "No instances available. Component won\'t work"];
        } else {
            $array_dropdown = ["0" => "- select Experience Instance - "];
            foreach ($instances as $instance) {
                $array_dropdown[$instance->id] = $instance->name;
            }
            return $array_dropdown;
        }
    }


    public function onRun() {
        try
        {
            $experienceInstance = ExperienceModel::find($this->property('Experience'));

            //don't multiply by zero!
            $milestoneNum = count($experienceInstance->milestones) > 0 ? count($experienceInstance->milestones) : 1;

            $this->page['maxBonus'] = $experienceInstance->bonus_days * $experienceInstance->bonus_per_day * $milestoneNum;
            $this->page['minBonus'] = -$experienceInstance->penalty_days * $experienceInstance->penalty_per_day * $milestoneNum;
            $size = $this->property('Size');
            $this->page['bonusSize'] = $size;


            $bonusPenalties = $this->getBonusPenalties();
            $this->page['totalBonus'] = $bonusPenalties === 0 ? 0 : round($bonusPenalties->bonus, 2);
            $this->page['totalPenalties'] = $bonusPenalties === 0 ? 0 : round($bonusPenalties->penalties, 2);

            if (!isset($_SESSION)) {
                session_start();
            }
            $roleStr = $_SESSION['roles'];

            $this->page['role'] = $roleStr;
            $this->addCss("/plugins/delphinium/blossom/assets/css/main.css");
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
            echo "In order for experience to work properly you must be a student, or go into 'Student View'";
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


    private function getBonusPenalties($userId = null) {
        $experienceComp = new ExperienceComponent();
        if ((!is_null($this->property('Experience'))) && ($this->property('Experience') > 0)) {
            return $experienceComp->calculateTotalBonusPenalties($this->property('Experience'), $userId);
        } else {
            $obj = new \stdClass();
            $obj->bonus = 0;
            $obj->penalties = 0;
        }
    }

}
