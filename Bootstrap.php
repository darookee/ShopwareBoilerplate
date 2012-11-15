<?php

class Shopware_Plugins_Backend_Boilerplate_Bootstrap extends Shopware_Components_Plugin_Bootstrap {

    static $_envBkp = NULL;

    /**
     * The Plugin-Version
     **/
    const VERSION = '0.5';

    /**
     * The author
     **/
    const AUTHOR = 'darookee';

    /**
     * The name displayed in the pluginmanager
     **/
    const PLUGINNAME = 'Boilerplate';

    /**
     * Events registered with this plugin
     * @static
     */
    static $myEvents =
        array(
            array(
                'event' => 'Enlight_Controller_Dispatcher_ControllerPath_Frontend_Boilerplate',
                'method' => 'getFrontendControllerPath'
            ),
            array(
                'event' => 'Enlight_Controller_Dispatcher_ControllerPath_Backend_Boilerplate',
                'method' => 'getBackendControllerPath'
            ),

            /** Common events **/
            /*
             *array(
             *    'event' => 'Enlight_Controller_Action_PostDispatch_Frontend_Index',
             *    'method' => 'onPostDispatchIndex'
             *),
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
             *            'scope' => \Shopware\Models\Config\Element::SCOPE_SHOP
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
     * @returns true
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
     * @returns true
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
     * @returns true
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
     * @returns true
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
     * @returns true
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
     * returns version for plugin manager
     * @returns array of version information
     */
    public function getInfo() {
        return array(
            'version' => $this->getVersion(),
            'autor' => self::AUTHOR,
            'copyright' => '(c) 2012',
            'label' => $this->getLabel(),
            'description' => $this->getDescription()
        );
    }

    /**
     * Returns the version of plugin as string.
     *
     * THIS DOES NOT WORK FOR Shopware Code-Review/Community-Store
     * It HAS TO return the Version like this
     * return '1.0.0';
     *
     * @return string
     */
    public function getVersion() {
        return self::VERSION;
    }

    /**
     * Returnss the plugin label as string
     *
     * @return string
     */
    public function getLabel() {
        return self::PLUGINNAME;
    }

    /**
     * Returns plugindescription from file info.txt
     *
     * @return string
     */
    public function getDescription() {
        return file_get_contents($this->Path().'/info.txt');
    }

    /**
     * @returns string path of Backend controller
     */
    public static function getBackendControllerPath(Enlight_Event_EventArgs $args) {
        return dirname(__FILE__) . '/Controllers/BoilerplateBackend.php';
    }

    /**
     * @returns string path of Frontend controller
     */
    public static function getFrontendControllerPath(Enlight_Event_EventArgs $args) {
        return dirname(__FILE__) . '/Controllers/BoilerplateFrontend.php';
    }

    /**
     * Called as cronjob
     * @returns true
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
     * saves envvars
     * useful for shopware core methods which substitute _GET vars (sArticles::sGetArticleById())
     *
     * @return true
     */
    protected static function _bkpEnv() {
        self::$_envBkp = array(
            '_SESSION' => Shopware()->System()->_SESSION,
            '_GET' => Shopware()->System()->_GET,
            '_POST' => Shopware()->System()->_POST
        );
        return true;
    }

    /**
     * restores envvars
     *
     * @return true
     */
    protected static function _rstEnv() {
        Shopware()->System()->_SESSION = self::$_envBkp['_SESSION'];
        Shopware()->System()->_GET = self::$_envBkp['_GET'];
        Shopware()->System()->_POST = self::$_envBkp['_POST'];
        return true;
    }

}
