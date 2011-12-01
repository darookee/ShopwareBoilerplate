<?php

/**
 * 
 */
class Shopware_Controllers_Backend_Boilerplate extends Shopware_Controllers_Backend_Extjs {

    protected $_useTemplates = true;
    protected $_templateBase = 'backend/plugins/boilerplate/';

    public function init() {
        if( $this->_useTemplates == true )
            $this->View()->addTemplateDir( dirname( __FILE__ ) . '/templates/' );
    }

    public function skeletonAction() {
        $this->_loadTemplate( 'skeleton.tpl' );
    }

    public function indexAction() {
        $this->_loadTemplate( 'index.tpl' );
    }

    protected function _loadTemplate( $name = NULL ) {
        if( $name == NULL )
            return false;
        $this->View()->loadTemplate( $this->_templateBase . $name );
    }

    protected function _log( $message = '', $type = Zend_Log::INFO ) {
        Shopware()->Log()->log( $message, $type );
    }

}
