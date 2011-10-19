<?php

class Shopware_Plugins_Frontend_Boilerplate_Bootstrap extends Shopware_Components_Plugin_Bootstrap {

    /**
     * Events registered with this plugin
     * @static
     */
    static $myEvents =
        array(
             array(
                'event' => 'Enlight_Controller_Dispatcher_ControllerPath_Backend_Boilerplate',
                'method' => 'getControllerPath'
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
            array(
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
            $this->registerFormSettings()
        )
            return true;
        else
            return false;
    }

    public function registerFormSettings() {
        if( count( self::$myForms ) > 0 ) {
            $form = $this->Form();
            foreach( self::$myForms as $key => $formArray ) {
                $form->setElement(
                    $formArray['tyoe'],
                    $formArray['name'],
                    $formArray['settings']
                );
            }
            $form->save();
        }
        return true;
    }

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
     * @returns string path of Import controller
     */
    public static function getImportControllerPath( $arg ) {
        return dirname(__FILE__) . '/Boilerplate.php';
    }

}
