<?php namespace Delphinium\Testing;

use Backend;
use System\Classes\PluginBase;
use Event;

/**
 * Testing Plugin Information File
 */
class Plugin extends PluginBase
{

    public $require = [
        'Delphinium.Greenhouse',
        'Delphinium.Dev',
        'Delphinium.Roots'
    ];
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Testing',
            'description' => 'No description provided yet...',
            'author'      => 'Delphinium',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'Delphinium\Testing\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'delphinium.testing.some_permission' => [
                'tab' => 'Testing',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * Adds a navigation item for this plugin's controllers in Delphinium's Greenhouse backend
     *
     * @return array
     */
    public function boot()
    {
          Event::listen('backend.menu.extendItems', function($manager) {

                //Uncomment following block and complete missing parameters to activate
                /*
                $manager->addSideMenuItems('Delphinium.Greenhouse', 'greenhouse', [
                    'Name' => [
                     'label' => 'Testing',
                     'icon'  => 'icon-leaf',
                     'owner' => 'Delphinium.Greenhouse',
                     'url' => Backend::url('delphinium/testing/mycontroller')
                    ]
                 ]);
                 */
          });
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'testing' => [
                'label'       => 'Testing',
                'url'         => Backend::url('delphinium/testing/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['delphinium.testing.*'],
                'order'       => 500,
            ],
        ];
    }

}
