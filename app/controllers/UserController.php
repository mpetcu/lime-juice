<?php

/**
 * @author: Mihai Petcu mihai.costin.petcu@gmail.com
 * @date: 25.11.2015
 */
class UserController extends ControllerBase
{
    /**
     * Unset isSecure for this controller. To be defined in each method.
     */
    public function initialize(){
        $this->isSecure = false;
        parent::initialize();
    }

    /**
     * Edit current session user profile data
     * @return mixed
     */
    public function editAction(){
        if($this->securePage(true)){
            $userSess = $this->session->get("user-data");
            $user = User::findById($userSess->getId());
            if($this->processForm($user, 'UserForm')){
                $this->flash->success("Your account was succesfully changed.");
                return $this->response->redirect('user/edit');
            }
            $this->view->user = $user;
        }
    }

    /**
     * Change pass from a master user account
     * @return mixed
     */
    public function passAction(){
        if($this->securePage(true)){
            $userSess = $this->session->get("user-data");
            $user = User::findById($userSess->getId());
            if($this->processForm($user, 'PassForm')){
                $this->flash->success("Your account password was succesfully changed.");
                return $this->response->redirect('user/edit');
            }
            $this->view->user = $user;
        }
    }

    /**
     * Login form setup session and remember me cookie
     */
    public function loginAction(){
        $this->view->dbs = false;
        if($this->isUserAuthenticated()) {
            return $this->response->redirect();
        }
        $this->view->showMenu = false;
        if($this->request->isPost()){
            $auth = $this->request->get('auth');
            $user = User::loginUser($auth['email'], $auth['pass']);
            if($user){
                $this->session->set("user-data", $user);
                if(isset($auth['cookie']))
                    $this->cookies->set('remember-me', $user->session, time() + 30 * 86400);
                if($ref = $this->request->getHTTPReferer()) {
                    return $this->response->redirect($ref);
                }
                return $this->response->redirect();
            }else{
                $this->flash->error('<b>Autentication faild!</b> Email or password incorect.');
            }
        }
    }

    
    /**
     * Logout link for current session user
     */
    public function logoutAction(){
        $this->cookies->get('remember-me')->delete();
        $this->session->destroy();
        if($ref = $this->request->getHTTPReferer());
            return $this->response->redirect($ref);
        return $this->response->redirect();
    }


}