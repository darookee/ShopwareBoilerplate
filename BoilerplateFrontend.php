<?php

/**
 * 
 */
class Shopware_Controllers_Frontend_Boilerplate extends Enlight_Controller_Action {

    protected $_useTemplates = true;
    protected $_templateBase = 'frontend/plugins/boilerplate/';

    public function init() {
        if( $this->_useTemplates == true )
            $this->View()->addTemplateDir( dirname( __FILE__ ) . '/templates/' );

        if( $this->Request()->isPost() && !count( $this->Request()->getPost() ) ) {
            $data = file_get_contents( 'php://input' );
            $data = Zend_Json::decode( $data );
            $this->Request()->setPost( $data );
        }
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
