<?php

require_once APPLICATION_PATH . '/models/entities/User.php';
require_once APPLICATION_PATH . '/models/logics/TaskLogic.php';
require_once APPLICATION_PATH . '/utilities/SessionNamespace.php';

class HomeController extends Zend_Controller_Action {

    public function init(): void {
        $layoutConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/common-layout-config.ini', 'layout');
        Zend_Layout::startMvc($layoutConfig);
    }
    
    public function indexAction(): void {
        // session check
        if (! Zend_Session::sessionExists()) {
            $this->_redirect('/welcome/signin');
            return;
        }

        $defaultNamespace = SessionNamespace::getInstance()->getNamespace(DEFAULT_NAMESPACE);
        $user = User::cast(unserialize($defaultNamespace->user));
        $userId = $user->getUserId();
        $allTasks = TaskLogic::getAllTasks($userId);
        $this->view->assign('isLogin', '1');
        $this->view->assign('taskStatus', array(TASK_TO_DO, TASK_IN_PROGRESS, TASK_DONE));
        $this->view->assign('allTasks', $allTasks);
        return;
    }

    public function updatetaskAction(): void {
        // session check
        if (! Zend_Session::sessionExists()) {
            $this->_redirect('/welcome/signin');
            return;
        }

        $defaultNamespace = SessionNamespace::getInstance()->getNamespace(DEFAULT_NAMESPACE);
        $user = User::cast(unserialize($defaultNamespace->user));
        $userId = $user->getUserId();

        if ($this->getRequest()->isGet()) {// To home page
            $this->_redirect('/home/index');
            return;
        } else {// Update a task and to home page
            $taskId = $this->_getParam(TASK_ID);
            $taskTitle = $this->_getParam(TASK_TITLE);
            $taskContent = $this->_getParam(TASK_CONTENT);
            $taskStatus = $this->_getParam(TASK_STATUS);

            // Update a task on DB
            if (TaskLogic::updateTask($userId, $taskId, $taskTitle, $taskContent, $taskStatus)) {// Success
                $this->_redirect('/home/index');
                return;
            } else {// Failure
                // TODO: error message
                $this->_redirect('/home/index');
                return;
            }
        }
    }

    public function newtaskAction(): void {
        // session check
        if (! Zend_Session::sessionExists()) {
            $this->_redirect('/welcome/signin');
            return;
        }

        $defaultNamespace = SessionNamespace::getInstance()->getNamespace(DEFAULT_NAMESPACE);
        $user = User::cast(unserialize($defaultNamespace->user));
        $userId = $user->getUserId();
        
        if ($this->getRequest()->isGet()) {// To new task page
            return;
        } else {// Register new task and to home page
            $taskTitle = $this->_getParam(TASK_TITLE);
            $taskContent = $this->_getParam(TASK_CONTENT);
            $taskStatus = $this->_getParam(TASK_STATUS);

            // Register new task to DB
            if (TaskLogic::registerTask($userId, $taskTitle, $taskContent, $taskStatus)) {// Success in registering a new task
                $this->_redirect('/home/index');
                return;
            } else {// Failure
                $this->_redirect('/home/newtask');
                return;
            }
        }
    }

    public function deletetaskAction(): void {
        // session check
        if (! Zend_Session::sessionExists()) {
            $this->_redirect('/welcome/signin');
            return;
        }

        $defaultNamespace = SessionNamespace::getInstance()->getNamespace(DEFAULT_NAMESPACE);
        $user = User::cast(unserialize($defaultNamespace->user));
        $userId = $user->getUserId();

        if ($this->getRequest()->isGet()) {// Redirect to indexAction
            $this->_redirect('/home/index');
        } else {// Delete the task
            $taskId = $this->_getParam(TASK_ID);

            // Delete the task on DB
            if (TaskLogic::deleteTask($userId, $taskId)) {// Success in deleting the task
                $this->_redirect('/home/index');
                return;
            } else {// Failure
                $this->_redirect('/home/index');
                return;
            }
        }
    }
}