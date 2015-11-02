<?php

/**
 * Class Shopware_Plugins_Backend_Boilerplate_Bootstrap
 *
 * @author Nils Uliczka
 * @author Anton
 */
class Shopware_Plugins_Frontend_Boilerplate_Bootstrap extends Shopware_Components_Plugin_Bootstrap {

    /**
     * The Plugin-Version
     * @var string
     **/
    const VERSION = '1.0.0';

    /**
     * The author
     * @var string
     **/
    const AUTHOR = 'darookee';

    /**
     * The Link to the author's homepage
     * @var string
     **/
    const LINK = 'http://darookee.net';

    /**
     * The name displayed in the pluginmanager
     * @var string
     **/
    const PLUGINNAME = 'Boilerplate';

    /**
     * Events registered with this plugin
     * @static
     */
    static $myEvents =
        array(
            /** Get path to requested frontend controller **/
            /*
             *array(
             *    'event' => 'Enlight_Controller_Dispatcher_ControllerPath_Frontend_Boilerplate',
             *    'method' => 'getFrontendControllerPath'
             *),
             */
            /** Get path to requested backend controller **/
            /*
             *array(
             *    'event' => 'Enlight_Controller_Dispatcher_ControllerPath_Backend_Boilerplate',
             *    'method' => 'getBackendControllerPath'
             *),
             */

            /** Common events **/
            /** PostDispatch - all **/
            array(
                'event' => 'Enlight_Controller_Action_PostDispatch',
                'method' => 'onPostDispatch'
            ),
            /** Homepage **/
            /*
             *array(
             *    'event' => 'Enlight_Controller_Action_PostDispatch_Frontend_Index',
             *    'method' => 'onPostDispatchIndex'
             *),
             */
            /** Checkout (cart,...) **/
            /*
             *array(
             *    'event' => 'Enlight_Controller_Action_PostDispatch_Frontend_Checkout',
             *    'method' => 'onPostDispatchCheckout'
             *)
             */
        );

    static $myMenus =
        array(
            /*
             * array(
             *    'label' => 'Boilerplate',
             *    'controller' => 'Boilerplate',
             *    'action' => 'Index',
             *    'class' => 'sprite-application-block',
             *    'active' => 1,
             *    'parent' => array( 'label' => 'Einstellungen' )
             *)
             */
        );

    static $myForms =
        array(
            /*
             *array(
             *    'type' => 'text',
             *    'name' => 'testfield',
             *    'settings' =>
             *        array(
             *            'label' => 'Testfield',
             *            'scope' => \Shopware\Models\Config\Element::SCOPE_SHOP,
             *            'value' => 'testvalue'
             *        ),
             *)
             */
        );

    static $mySql =
        array(
            /*
             *"CREATE TABLE `s_plugin_boilerplate` (
             *    `id` int(11) NOT NULL AUTO_INCREMENT,
             *    `name` varchar(255) DEFAULT NULL,
             *    PRIMARY KEY ( `id` )
             *) DEFAULT CHARSET=latin1",
             */
        );

    static $myCron =
        array(
            /*
             *array(
             *    'eventName' => 'BoilerplateCronjob',
             *    'cronjobName' => 'Boilerplate',
             *    'functionName' => 'onCron',
             *    'interval' => 3600,
             *    'active' => true
             *),
             */
        );

    /**
     * install the plugin - call registerHooks and registerEvents
     * @see registerHooks
     * @see registerEvents
     */
    public function install() {
        if(
            $this->registerEvents() &&
            $this->registerMenuEntries() &&
            $this->registerFormSettings() &&
            $this->registerCron() &&
            $this->executeSql()
        )
            return array('success' => true, 'invalidateCache' => array('backend'));
        else
            return array('success' => false);
    }

    /**
     * registers forms
     * @return boolean true
     */
    public function registerFormSettings() {
        if(count(self::$myForms) > 0) {
            $form = $this->Form();
            foreach(self::$myForms as $key => $formArray) {
                $form->setElement(
                    $formArray['type'],
                    $formArray['name'],
                    $formArray['settings']
                );
            }
            $form->save();
        }
        return true;
    }

    /**
     * registers Menuentries
     * @return boolean true
     */
    public function registerMenuEntries() {
        if(count(self::$myMenus) > 0) {
            foreach(self::$myMenus as $key => $menuArray) {
                if(is_array($menuArray['parent'])) {
                    $findBy = array_keys($menuArray['parent']);
                    $findBy = $findBy[0];
                    $parent = $this->Menu()->findOneBy($findBy, $menuArray['parent'][$findBy]);
                    $menuArray['parent'] = $parent;
                }
                $this->createMenuItem($menuArray);
            }
        }
        return true;
    }

    /**
     * registers the events for this plugin
     * @see myEvents
     * @return boolean true
     */
    public function registerEvents() {

        if(count(self::$myEvents) > 0) {
            foreach(self::$myEvents as $eventArray) {
                $this->subscribeEvent(
                        $eventArray['event'],
                        $eventArray['method']
                    );
            }
        }

        return true;
    }

    /**
     * registers cronjobs for this plugin
     * @see myCron
     * @return boolean true
     */
    public function registerCron() {
        if(count(self::$myCron) > 0) {
            foreach(self::$myCron as $cronArray) {
                $event = $this->subscribeEvent(
                    'Shopware_CronJob_' . $cronArray['eventName'],
                    $cronArray['functionName']
                );
                $this->createCronJob($cronArray['cronjobName'], $cronArray['eventName'], $cronArray['interval'], $cronArray['active']);
            }
        }
        return true;
    }

    /**
     * execute sql statements (for table creation...)
     * @return boolean true
     */
    public function executeSql() {
        if(count(self::$mySql) > 0) {
            $db = Shopware()->Db();
            foreach(self::$mySql as $sql) {
                $db->query($sql);
            }
        }
        return true;
    }

    /**
     * return version for plugin manager
     * @return array of version information
     */
    public function getInfo() {
        return array(
            'version' => $this->getVersion(),
            'autor' => self::AUTHOR,
            'link' => self::LINK,
            'copyright' => '(c) '.date('Y'),
            'label' => $this->getLabel(),
            'description' => $this->getDescription()
        );
    }

    /**
     * return the version of plugin as string.
     *
     * THIS DOES NOT WORK FOR Shopware Code-Review/Community-Store
     * It HAS TO return the Version like this
     * return '1.0.0';
     *
     * @return string the Version
     */
    public function getVersion() {
        //return '1.0.0'; // when you submit your plugin to the shopware store
                          // you need to return the version string here
                          // without using the constant to pass
                          // the code quality test
        return self::VERSION;
    }

    /**
     * returns the plugin label as string
     *
     * @return string Name of the plugin
     */
    public function getLabel() {
        return self::PLUGINNAME;
    }

    /**
     * return plugindescription from file info.txt
     *
     * @return string Path to info.txt
     */
    public function getDescription() {
        return file_get_contents($this->Path().'/info.txt');
    }

    /**
     * @return string path of Backend controller
     */
    /*
     *public static function getBackendControllerPath(Enlight_Event_EventArgs $args) {
     *    return dirname(__FILE__) . '/Controllers/BoilerplateBackend.php';
     *}
     */

    /**
     * @return string path of Frontend controller
     */
    /*
     *public static function getFrontendControllerPath(Enlight_Event_EventArgs $args) {
     *    return dirname(__FILE__) . '/Controllers/BoilerplateFrontend.php';
     *}
     */

    /**
     * Called as cronjob
     * @return boolean true
     *
     * Wrapped in a try ... catch because there may be issues when an exception is thrown
     *  - sometimes the cronjob will not be run ever again
     * to prevent this the exception is caught and the message can be viewed in the cj settings
     */
    /*
     *public function onCron(Shopware_Components_Cron_CronJob $job) {
     *    try {
     *        $job->stop();
     *    } catch(Exception $e) {
     *        $job->setData($e->getMessage());
     *        $job->stop();
     *    }
     *    return true;
     *}
     */

    /**
     * onPostDispatch
     * @param Enlight_Event_EventArgs $args
     * @return void
     **/
    public function onPostDispatch(Enlight_Event_EventArgs $args) {
        /** @var $controller Shopware_Controllers_Frontend_Index */
        $controller = $args->getSubject();

        /** @var $request Zend_Controller_Request_Http */
        $request = $controller->Request();

        /** @var $response Zend_Controller_Response_Http */
        $response = $controller->Response();

        /** @var $view Enlight_View_Default  */
        $view = $controller->View();

        //Check if there is a template and if an exception has occured
        if(
            !$request->isDispatched()
            ||$response->isException()
            || !$view->hasTemplate()
            //|| $request->getModuleName() != 'frontend' // check for frontend
        ) {
            return;
        }

        if($this->isConnexoTemplate()) { // Connexo Responsive Template
            $view->addTemplateDir(dirname(__FILE__) . '/Views.connexo/');
        } else { // .. default template
            $view->addTemplateDir(dirname(__FILE__) . '/Views/');
        }

    }

    /**
     * check for Connexo Template
     *
     * @return array|false
     */
    private function isConnexoTemplate() {
        $sql = "SELECT `active` FROM `s_core_plugins` WHERE `name` = 'SwfResponsiveTemplate' LIMIT 1";
        return Shopware()->Db()->fetchOne($sql);
    }

}
