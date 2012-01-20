<?php

class Shopware_Plugins_Frontend_Boilerplate_Bootstrap extends Shopware_Components_Plugin_Bootstrap {

    static $_envBkp = NULL;

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
        );

    /** 
        * Hooks registrered with this plugin
        * @static
     */
    static $myHooks =
        array(
            /*
             *array(
             *    'class' => 'sOrder',
             *    'oMethod' => 'sGetOrderNumer',
             *    'nMethod' => 'rGetOrderNumber',
             *    'type' => Enlight_Hook_HookHandler::TypeReplace,
             *    'position' => 0
             *)
             */
        );

    static $myMenus =
        array(
            'Artikel' => array(
                'label' => 'Boilerplate',
                'onclick' => 'openAction(\'Boilerplate\');',
                'class' => 'ico2 connect',
                'active' => 1,
                'style' => 'background-position: 5px 5px;'
            )
        );

    static $myForms =
        array(
            /*
             *array(
             *    'type' => 'text',
             *    'name' => 'Testfield',
             *    'settings' =>
             *        array(
             *            'label' => 'Testfield',
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

    /**
        * install the plugin - call registerHooks and registerEvents 
        * @see registerHooks
        * @see registerEvents
     */
    public function install() {
        if(
            $this->registerHooks() &&
            $this->registerEvents() &&
            $this->registerMenuEntries() &&
            $this->registerFormSettings() &&
            $this->executeSql()
        )
            return true;
        else
            return false;
    }

    /**
     * registers forms
     * @returns true
     */
    public function registerFormSettings() {
        if( count( self::$myForms ) > 0 ) {
            $form = $this->Form();
            foreach( self::$myForms as $key => $formArray ) {
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
        if( count( self::$myMenus ) > 0 ) {
            foreach( self::$myMenus as $key => $menuArray ) {
                $parent = $this->Menu()->findOneBy( 'label', $key );
                $item = $this->createMenuItem( array_merge( $menuArray, array( 'oarent' => $parent ) ) );
                $this->Menu()->addItem( $item );
            }
            $this->Menu()->save();
        }
        return true;
    }

    /**
     * registers the events for this plugin
     * @see myEvents
     * @returns true
     */
    public function registerEvents() {

        if( count( self::$myEvents ) > 0 ) {
            foreach( self::$myEvents as $eventArray ) {
                $event = $this->createEvent(
                        $eventArray['event'],
                        $eventArray['method']
                    );
                $this->subscribeEvent( $event );
            }
        }

        return true;
    }

    /**
     * registers the hooks for this plugin
     * @see myHooks
     * @returns true
     */
    public function registerHooks() {
        if( count( self::$myHooks ) > 0 ) {
            foreach( self::$myHooks as $hookArray ) {
                $hook = $this->createHook(
                            $hookArray['class'],
                            $hookArray['oMethod'],
                            $hookArray['nMethod'],
                            $hookArray['type'],
                            $hookArray['position']
                        );
                $this->subscribeHook( $hook );
            }
        }
        return true;
    }

    /**
     * execute sql statements (for table creation...)
     * @returns true
     */
    public function executeSql() {
        if( count( self::$mySql ) > 0 ) {
            $db = Shopware()->Db();
            foreach( self::$mySql as $sql ) {
                $db->query( $sql );
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
            'version' => '0.0.1',
            'autor' => 'darookee',
            'copyright' => '(c) 2011',
            'label' => 'ShopwareBoilerplate',
            'source' => 'Local',
        );
    }

    /**
     * @returns string path of Backend controller
     */
    public static function getBackendControllerPath( $arg ) {
        return dirname(__FILE__) . '/BoilerplateBackend.php';
    }

    /**
     * @returns string path of Frontend controller
     */
    public static function getFrontendControllerPath( $arg ) {
        return dirname(__FILE__) . '/BoilerplateFrontend.php';
    }

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

    /**
     * wrapper for Shopware()->Log()->log() with predefined type
     * @returns void
     */
    protected static function _log( $message = '', $type = Zend_Log::INFO ) {
        Shopware()->Log()->log( $message, $type );
    }

}
