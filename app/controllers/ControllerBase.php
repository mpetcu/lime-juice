<?php
/**
 * Base controller to be included in other controllers
 * @author: Mihai Petcu mihai.costin.petcu@gmail.com
 * @date: 28.10.2015
 */
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;

abstract class ControllerBase extends Controller
{
    //set default security policy
    protected $isSecure = true;

    /**
     * Should run every time a controller/action is called
     */
    public function initialize(){
        if($this->request->isAjax() == true){
            $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        }
        $this->install();
        $this->securePage($this->isSecure);
        $this->view->showMenu = true;
        $this->view->userRole = $this->getUserRole();
        $this->view->authenticatedUser = $this->getUserSession();
    }

    /**
     * Trigger Settings/Install controller/action!
     * @return mixed
     */
    protected function install(){
        if($this->request->getUri() != '/settings/install') {
            try {
                if (trim(fread(fopen('reports/install.txt', 'r'), 12)) == 'install=none')
                    return $this->response->redirect('settings/install');
            } catch (Exception $e) {
                //$e->getMessage();
            }
        }
    }

    /**
     * Default processForm method to use in New/Edit actions
     * @param $entity  Model/Collection to be passed to the form
     * @param $form  Form object
     * @return bool True if form is valid, otherwise false
     */
    protected function processForm($entity, $form){
        $this->form = new $form($entity);
        if($this->request->isPost()) {
            if($this->form->isValid($this->request->getPost())) {
                $this->form->bind((array) $this->request->getPost(), $entity);
                $entity->save();
                $this->view->form = $this->form;
                return true;
            }
        }
        $this->view->form = $this->form;
        return false;
    }

    /**
     * Secure page and redirect to user/login
     * @param bool $secure
     * @return mixed
     */
    protected function securePage($secure = true){
        if(!$this->isUserAuthenticated() && $secure) {
            $this->dispatcher->forward(
                array(
                    "controller" => "user",
                    "action"     => "login"
                )
            );
        }else{
            if($this->isUserAuthenticated()) {
                $this->view->showLogOut = true;
                $this->view->sessionUserName = $this->session->get("user-data")->getName();
                return true;
            }
        }
        return false;
    }

    /**
     * Internal controller method to check if a user is Auth
     * @return bool
     */
    protected function isUserAuthenticated(){
        if($this->session->get("user-data"))
            return true;
        if($this->cookies->get('remember-me')){
            $user = User::loginUserFromCookie($this->cookies->get('remember-me'));
            if($user){
                $this->session->set("user-data", $user);
                $this->cookies->set('remember-me', $user->session, time() + 30 * 86400);
                return true;
            }
        }
        return false;
    }

    /**
     * Get the role (type) of an Authenticated user or false;
     * @return bool
     */
    protected function getUserRole(){
        if($this->isUserAuthenticated()){
            return $this->session->get("user-data")->type;
        }
        return false;
    }


    /**
     * Get user collection of an Authenticated user or false;
     * @return bool
     */
    protected function getUserSession(){
        if($this->isUserAuthenticated()){
            return $this->session->get("user-data");
        }
        return false;
    }

    /**
     * Get the role (type) of an Authenticated user or false;
     * @return bool
     */
    protected function allowRoles($roles = ['master']){
        if($this->isUserAuthenticated()){
            if(in_array($this->getUserRole(), $roles)){
                return true;
            }
            $this->dispatcher->forward(
                array(
                    "controller" => "index",
                    "action"     => "error404"
                )
            );
            return false;
        }
    }


}
