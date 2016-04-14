<?php namespace Delphinium\Uliop\Components;

use Cms\Classes\ComponentBase;
/*
* Lines bellow must be uncommented once a Model and Controller are created for this component.
* References to MyModel and MyController in this file must be updated with the appropriate classes
*/
//use Delphinium\Uliop\Models\Model as MyModel;
//use Delphinium\Uliop\Controllers\Controller as MyController;

class NewComp extends ComponentBase
{

    /**
     * @return array An array of details to be shown in the CMS section of OctoberCMS
     */
    public function componentDetails()
    {
        return [
            'name'        => 'NewComp Component',
            'description' => 'No description provided yet...'
        ];
    }

    /**
     * @return array Array of properties that can be configured in this instance of this component
     */
    public function defineProperties()
    {
        return [
            'instance'	=> [
                'title'             => '(Optional) NewComp instance',
                'description'       => 'Select the newcomp instance to display. If an instance is selected, it will be
                                    the configuration for all courses that use this page. Leaving this field blank will allow
                                    different configurations for every course.',
                'type'              => 'dropdown',
                'default'           => 0
            ]
        ];
    }

    /**
     * @return array An array of instances (eloquent models) to populate the instance dropdown to configure this component
     */
    public function getInstanceOptions() {
        //In order for this to work, you must create a model and controller to store the instances of this component.
        //Modify use statement above to include the model and controller and uncomment the following code

        /*
        $instances = MyModel::all();

        if (count($instances) === 0) {
            return $array_dropdown = ["0" => "No instances available."];
        } else {
            $array_dropdown = ["0" => "- select MyModel Instance - "];
            foreach ($instances as $instance) {
                $array_dropdown[$instance->id] = $instance->name;//assuming that the model has id and name fields
            }
            return $array_dropdown;
        }
        */
    }

    /**
     * This function will run every time this component runs. To use this component, drop it on a OctoberCMS page along with the dev component
     * (for development) or LTIConfiguration component (for production)
     */
    public function onRun()
    {
        /*
        try
        {
            //NOTES:
            //Components have database instances. The logic for how they are created is as follows:
            //is an instance set in this component's properties? yes show it
            //else get all instances
            //    is there an instance with this alias_course? yes use it
            //else create dynamicInstance, save new instance, show it

            //Requires minimal.htm layout
            //Requires the Dev component set up from Here:
            //https://github.com/ProjectDelphinium/delphinium/wiki/3.-Setting-up-a-Project-Delphinium-Dev-environment-on-localhost

            $config = $this->getInstance();
            //use the record in the component and frontend form
            $this->page['config'] = json_encode($config);
            //Use the primary key of the record you want to update
            $this->page['recordId'] = $config->id;

            //get LMS roles --used to determine functions and display options
            $roleStr = $_SESSION['roles'];
            $this->page['role'] = $roleStr;
            if(stristr($roleStr, 'Instructor')||stristr($roleStr, 'TeachingAssistant'))
            {//only instructors will be able to configure the component
                //add whatever logic needed for when instructors or TAs come in
            }
            else
            {
                //add whatever logic needed for when students come in
            }


            //INCLUDE JS AND CSS
            //include your css. Note: bootstrap.min.css is part of minimal layout.
            //See #10 in https://github.com/ProjectDelphinium/delphinium/wiki/1.-Installation#setup
            // javascript had to be added to default.htm to work correctly
            $this->addJs("/plugins/delphinium/newcomp/assets/javascript/jquery.min.js");
            //if you desire to use OctoberCMS' ui library (See https://octobercms.com/docs/ui/form) uncomment the following three lines
            //$this->addCss('/modules/system/assets/ui/storm.css', 'core');
            //$this->addJs('/modules/system/assets/ui/storm-min.js', 'core');
            //$this->addCss('/modules/system/assets/ui/storm.less', 'core');


            //THIS NEXT SECTION WILL PROVIDE TEACHERS WITH FRONT-EDITING CAPABILITIES OF THE BACKEND INSTANCES.
            //A CONTROLLER AND MODEL MUST EXIST FOR THE INSTANCES OF THIS COMPONENT SO THE BACKEND FORM CAN BE USED IN THE FRONT END FOR THE TEACHERS TO USE
            //ALSO, AN INSTRUCTIONS PAGE WITH THE NAME instructor.htm MUST BE ADDED TO YOUR CONTROLLER DIRECTORY, AFTER THE CONTROLLER IS CREATED
            //IN Delphinium\Uliop\controllers\NewComp\_instructions.htm

            // include the backend form with instructions for instructor.htm
            if(stristr($roleStr, 'Instructor'))
            {
                //TODO: Replace MyController with your controller
                $formController = new MyController();
                $formController->create('frontend');

                //Append the formController to the page
                $this->page['form'] = $formController;

                //Append the Instructions to the page
                $instructions = $formController->makePartial('instructions');
                $this->page['instructions'] = $instructions;
            }
            else if(stristr($roleStr, 'Learner'))
            {
                //code specific to the student.htm goes here
            }

        //Error handling requires nonlti.htm. See #11 in https://github.com/ProjectDelphinium/delphinium/wiki/1.-Installation#setup
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
        */
    }

    /**
     * Retrieves instance of this component. If no specific instance was selected in the CMS configuration of this component
     * then it will create a dynamic instance based on the alias_courseId in which this component was launched
     * @param null $name The name of the component
     * @return mixed Instance of Component
     */
    private function getInstance($name=null)
    {
        /*
        if (!isset($_SESSION)) { session_start(); }
        $courseId = $_SESSION['courseID'];

        //if instance has been set
        if( $this->property('instance') )
        {
            //use the instance set in CMS dropdown
            //TODO: replace MyModel with your actual model
            $config = MyModel::find($this->property('instance'));

        } else {
            if(is_null($name))
            {
                $name =$this->alias . "_".$courseId;
            }
            $config =MyModel::firstOrNew(array('name'=>$name));
            $config->name = $name;

        }

        $config->save();
        return $config;
        */
    }

    /**
     * Ajax Handler for when teachers update the component from their view
     * @return string Json encoded instance of component
     */
    public function onUpdate()
    {
    /*
        $data = post('NewComp');//component name
        $id = intval($data['id']);// convert string to integer
        $config = MyModel::find($id);// retrieve existing record

        //update record with new data coming from POST
        $config->name = $data['name'];
        $config->course_id = $data['course_id'];//hidden in frontend
        $config->save();// update original record
        return json_encode($config);// back to instructor view
        */
    }
}