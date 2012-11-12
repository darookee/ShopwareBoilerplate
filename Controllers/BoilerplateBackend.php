<?php

/**
 * 
 */
class Shopware_Controllers_Backend_Boilerplate extends Shopware_Controllers_Backend_ExtJs {

    protected $_useTemplates = true;
    protected $_templateBase = 'backend/boilerplate/';

    /**
     * Adds the Templatedir to the stack if _useTemplates is set
     * and decodes JSON in Post-Body
     *
     * @returns void
     */
    public function init() {
        if($this->_useTemplates == true)
            $this->View()->addTemplateDir(dirname(__FILE__) . '/Views/');

        if($this->Request()->isPost() && !count($this->Request()->getPost())) {
            $data = file_get_contents('php://input');
            $data = Zend_Json::decode($data);
            $this->Request()->setPost($data);
        }
    }

    /**
     * sample skeleton action
     *
     * @returns void
     */
    public function skeletonAction() {
        $this->_loadTemplate('skeleton.tpl');
    }

    /**
     * sample index action
     *
     * @returns void
     */
    public function indexAction() {
        $this->_loadTemplate('index.tpl');
    }

    /**
     * helper function to load template
     *
     * @returns void
     */
    protected function _loadTemplate($name = NULL) {
        if( $name == NULL )
            return false;
        if(file_exists($this->_templateBase.$name))
            $this->View()->loadTemplate($this->_templateBase . $name);
        else
            return false;
    }

    /**
     * helper function to extend template
     *
     * @returns void
     */
    protected function _extendTemplate($name = NULL) {
        if( $name == NULL )
            return false;
        if(file_exists($this->_templateBase.$name))
            $this->View()->extendTemplate($this->_templateBase . $name);
        else
            return false;
    }

}
