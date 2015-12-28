<?php
/**
 * @author: Mihai Petcu mihai.costin.petcu@gmail.com
 * @date: 1.11.2015
 */
class IndexController extends ControllerBase
{
    /**
     * Forward to Database controller action Index
     */
    public function indexAction(){
        $this->dispatcher->forward(
            array(
                "controller" => "db",
                "action"     => "index"
            )
        );
    }

    /**
     * Forward to Database controller action Index
     */
    public function error404Action(){
        $this->view->dbs = false;
        $this->view->currentPage = (isset($this->config->application->baseUri)?$this->config->application->baseUri:'/') . substr($this->request->getUri(),1);
        return;
    }

}