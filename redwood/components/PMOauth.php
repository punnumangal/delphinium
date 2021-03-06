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

namespace Delphinium\Redwood\Components;

use Cms\Classes\ComponentBase;
use Delphinium\Redwood\Models\PMOAuth as OAuthModel;
use Delphinium\Redwood\Models\Authorization;
use Config;

class PMOauth extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name'        => 'OAuth Component',
            'description' => 'This component will carry out the OAuth process to make REST calls to Process Maker\'s API'
        ];
    }

    public function defineProperties()
    {
        return [
            'configs' => [
                'title'             => 'OAuth Configuration',
                'description'       => 'Select the pmoauth configuration',
                'type'              => 'dropdown',
                'validationPattern' => '^[1-9][0-9]*$',//check that they've selected an option from the drop down. The default placeholder is=0
                'validationMessage' => 'Select a set of credentials from the dropdown'
            ]
        ];
    }

    public function getConfigsOptions()
    {
        $instances = OAuthModel::get();

        $array_dropdown = ['0'=>'- select configuration - '];

        foreach ($instances as $instance)
        {
            $array_dropdown[$instance->id] = $instance->name;
        }

        return $array_dropdown;
    }

    public function onRun()
    {
        //check to see if we have a token for the given workspace. If we don't we'll have to go through the OAuth process
        if(!$this->property('configs')>0)
        {
            echo "Cannot connect to process maker. No credentials were selected";
            return;
        }

        $configs = OAuthModel::find($this->property('configs'));
        $workspace = $configs->workspace;
        $authorization = Authorization::where('workspace','=',$workspace)->first();

        $pmServer = $configs->server_url;

        if ($authorization)
        {
            if (!isset($_SESSION)) {
                session_start();
            }
            $_SESSION['pm_credentials_id']=$this->property('configs');
            $_SESSION['pm_encrypted_access_token'] =$authorization->encrypted_access_token;
            $_SESSION['pm_encrypted_refresh_token'] =  $authorization->encrypted_refresh_token;
            $_SESSION['pm_workspace'] = $workspace;
            $_SESSION['pm_server'] = $pmServer;
        }
        else
        {
            //STEP 1: Authorize :
            //GET http://{pm-server}/{workspace}/oauth2/authorize?response_type=code&client_id={client-id}&scope={scope}
            //save some variables to a cookie so we can retrieve them after the pmoauth process is completed. At that point the cookie will be
            //destroyed and we'll save the data we need in the session variables
            setcookie("pm_OAuthCredentialsId",  $this->property('configs'), time() + (86400 * 30), '/');
            setcookie("pm_workspace", $workspace, time() + (86400 * 30), '/');

            $credentials = OAuthModel::find($this->property('configs'));
            $client_id = $credentials->client_id;

            //TODO: where do we parameterize these urls?
            $url = "{$pmServer}/{$workspace}/oauth2/authorize?response_type=code&client_id={$client_id}&scope=*";
            $this->redirect($url);
        }

    }

    function redirect($url) {
        echo '<script type="text/javascript">';
        echo 'window.location.href="' . $url . '";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url=' . $url . '" />';
        echo '</noscript>';
        exit;
    }
}