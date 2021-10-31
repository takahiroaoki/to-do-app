<?php


require_once APPLICATION_PATH . '/models/logics/UserLogic.php';

class WelcomeController extends Zend_Controller_Action {

    public function init() {
        $layoutConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/common-layout-config.ini', 'layout');
        Zend_Layout::startMvc($layoutConfig);
    }
    
    public function indexAction() {
        return;
    }

    public function signinAction() {
        if ($this->getRequest()->isGet()) {// To sign in page
            return;
        }
        // Sign in process
        $userEmail = $this->_getParam(USER_EMAIL);
        $userPassword = $this->_getParam(USER_PASSWORD);
        if (UserLogic::verifyUser($userEmail, $userPassword)) {
            // TODO: make session
            // Redirect to user's home page
            $this->_redirect('/home/index');
        } else {
            // Redirect to sign-in page
            $this->_redirect('/welcome/signin');
        }
    }

    public function signoutAction() {
        // TODO: delete session
        $this->_redirect('/welcome/index');
    }

    public function signupAction() {
        if ($this->getRequest()->isGet()) {// To sign up page
            return;
        }
        // Sign up process
        $userEmail = $this->_getParam(USER_EMAIL);
        $userPassword = $this->_getParam(USER_PASSWORD);

        if (UserLogic::registerUser($userEmail, $userPassword)) {// To sign in page
            $this->_redirect('/welcome/signin');
        } else {// To sign up page again
            $this->_redirect('/welcome/signup');
        }
    }
}