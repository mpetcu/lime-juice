<?php
/**
 * @author: Mihai Petcu mihai.costin.petcu@gmail.com
 * @date: 3.11.2015
 */
class ReportController extends ControllerBase
{
    public function initialize(){
        parent::initialize();
        $this->view->dbs = Db::find(); //list all dbs in the left column
        $this->view->currentDbId = $this->request->get('id');
    }

    public function defaultAction(){
        if($this->view->userRole == 'master'){
            $this->view->dbsl = $this->view->dbs;
            $this->view->dbs = false;
        }else{
            //TODO My latest notifications
        }
    }

    /**
     * Show files generated by reports This action will be viewed by
     */
    public function indexAction(){
        $user = User::findById($this->session->get("user-data")->getId());
        if($this->request->get('id')){
            $db = Db::findById($this->request->get('id'));
            $user->setConfig('db', $db->getId());
        }
        if($user->getConfig('db')){
            $db = isset($db)?$db:(Db::findById($user->getConfig('db')));
            if(!$this->request->get('id')){
                return $this->response->redirect('report/index?id='.$db->getId());
            }
            $this->view->dbm = $db;
            $this->view->currentDbId = $db->getId();
        }else{
            return $this->response->redirect('report/default');
        }

        //if master show users
        if($this->getUserRole() == 'master'){
            $this->view->users = User::find(['conditions' => ['type' => 'operator', 'status' => 1]]);
        }
    }

    /**
     * Add a report
     * @return mixed
     */
    public function newAction(){
        $db = Db::findById($this->request->get('db'));
        if($this->processForm($db->newReport(), 'ReportForm')){
            $this->flash->success("Report <strong>".$this->request->get('name')."</strong> was created succesfully.");
            return $this->response->redirect('report/index?id='.$db->getId());
        }
        $this->view->currentDbId = $db->getId();
        $this->view->dbm = $db;
    }

    /**
     * Edit a report
     * @return mixed
     */
    public function editAction(){
        $report = Report::findById($this->request->get('id'));
        if($this->processForm($report, 'ReportForm')){
            $this->flash->success("Report <strong>".$this->request->get('name')."</strong> was changed succesfully.");
            return $this->response->redirect('report/index?id='.$report->getDb()->getId());
        }
        $this->view->currentDbId = $report->getDb()->getId();
        $this->view->report = $report;
    }

    /**
     * Remove a report
     * @return mixed
     */
    public function deleteAction(){
        $report = Report::findById($this->request->get('id'));
        if($this->request->get('confirm')) {
            $db = $report->getDb();
            if ($report->delete()) {
                $this->flash->success("Report was removed succesfully.");
            }
            return $this->response->redirect('report/index?id=' . $db->getId());
        }
        $this->view->report = $report;
    }

    /**
     * Remove a report
     * @return mixed
     */
    public function deleteLogAction(){
        $log = Log::findById($this->request->get('id'));
        if($this->request->get('confirm')) {
            $report = $log->getReport();
            if ($log->delete()) {
                $this->flash->success("Log was removed succesfully.");
            }
            return $this->response->redirect('report/index?id=' . $report->getDb()->getId().'#'.$report->getId());
        }
        $this->view->log = $log;
    }

    /**
     * The run action called from runModal action.
     * The output is redirected to runModal view
     */
    public function runAction(){
        $report = Report::findById($this->request->get('id'));
        $report->generateFile('user')->save();
        $report->save();

        //send mail
        $report->sendNotif();

        $this->view->lastRun = true;
        $this->view->report = $report;
        $this->view->pick('report/runModal');
    }

    /**
     * Run manually a database report in a modal
     */
    public function runModalAction(){
        $report = Report::findById($this->request->get('id'));
        $this->view->lastRun = false;
        $this->view->report = $report;
    }

    /**
     * Add/Delete emails modal for sending report notifications
     * @return mixed
     */
    public function msgModalAction(){
        $report = Report::findById($this->request->get('id'));
        if($this->request->isPost()){
            $notif = $this->request->getPost('notif');
            $user = $this->getUserSession();
            if($notif == 'yes'){
                $report->setNotif($user);
            }
            if($notif == 'no'){
                $report->unsetNotif($user);
            }
            $this->view->change = true;
            $report->save();

        }
        $this->view->report = $report;
    }

    /**
     * Add/Edit/Disable cron job modal to a database report
     * @return mixed
     */
    public function jobModalAction(){
        $report = Report::findById($this->request->get('id'));
        $job = $report->getJob();
        if($this->processForm($job, 'JobForm')){
            $this->flash->success("Saved succesfully.");
            return $this->response->redirect('report/jobModal?id='.$report->getId());
        }
        $this->view->report = $report;
        $this->view->job = $job;
    }

    /**
     * Show full logs modal for a report (manually or cron job)
     */
    public function fullLogModalAction(){
        $report = Report::findById($this->request->get('id'));
        $this->view->report = $report;
    }

    /**
     * Show full logs modal for a report (manually or cron job)
     */
    public function viewModalAction(){
        $absPath = $this->getDI()->get('config')->application->publicDir;
        $log = Log::findById($this->request->get('id'));
        $this->view->log = $log;
        try{
            $inFt = PHPExcel_IOFactory::identify($absPath.$log->fileLocation);
            $objReader = PHPExcel_IOFactory::createReader($inFt);
            $objPHPExcel = $objReader->load($absPath.$log->fileLocation);
            $st = $objPHPExcel->getSheet(0);
            $MaxRow = $st->getHighestRow()>100?100:$st->getHighestRow();
            $MaxCol = $st->getHighestColumn();
            $data = [];
            for ($row = 1; $row <= $MaxRow; $row++){
                array_push( $data, $st->rangeToArray('A' . $row . ':' . $MaxCol . $row, NULL, TRUE, FALSE)[0]);
            }
            $this->view->logDataArr = $data;
        } catch(Exception $e){}

    }

}

