<?php
/**
 * Report model collection
 * @author: Mihai Petcu mihai.costin.petcu@gmail.com
 * @date: 17.10.2015
 */
class Report extends \Phalcon\Mvc\Collection
{
    public $_id;
    public $did;
    public $name;
    public $qry;
    public $logCount;
    public $status;

    public function getSource(){
        return "report";
    }

    public function getId(){
        return $this->_id->{'$id'};
    }

    public function beforeCreate(){
        $this->status = 1;
        $this->logCount = 0;
    }

    public function beforeUpdate(){
        $this->setLogCount((int) count(Log::find(['condition' => ['rid' => $this->rid]])));
    }

    public function setLogCount($logCount){
        $this->logCount = $logCount;
    }

    public function getLogCount(){
        return $this->logCount;
    }

    public function setDb(Db $db){
    $this->did = $db->getId();
}

    public function getDb(){
        return Db::findById($this->did);
    }

    /**
     * Return assigned job or a new instance of Job
     * @return Job object
     */
    public function getJob(){
        $job = Job::findFirst([['rid' => $this->getId()]]);
        if(!$job){
            $job = new Job();
            $job->setReport($this);
        }
        return $job;
    }

    /**
     * Return assigned job or false
     * @return Job object/bool
     */
    public function getJobIfExist(){
        $job = Job::findFirst([['rid' => $this->getId()]]);
        if(!$job){
            return false;
        }
        return $job;
    }

    /**
     * Run current report object
     * @return mixed
     */
    public function run(){
        return $this->getDb()->getConn()->query($this->qry);
    }

    /**
     * Add log data to Log collection;
     * @param $log
     */
    public function addLog($logData){

        $log = new Log();
        $log->rid = $this->getId();
        $log->startTime = $logData['startTime'];
        $log->endTime = $logData['endTime'];
        $log->totalTime = $logData['totalTime'];
        $log->runType = $logData['runType'];
        $log->errors = $logData['errors'];

        if(!$logData['errors']) {
            $log->fileLocation = $logData['fileLocation'];
            $log->fileType = $logData['fileType'];
            $log->fileSize = $logData['fileSize'];
            $log->rows = $logData['rows'];
        }
        $log->save();
        return $log;
    }

    /**
     * Get latest log
     * @return mixed
     */
    public function getLatestLog(){
        return Log::findFirst(['conditions' => ['rid' => $this->getId()], 'sort' => ['_id' => -1]]);
    }

    public function getLogs($limit = 100){
        return Log::find(['conditions' => ['rid' => $this->getId()], 'sort' => ['_id' => -1], 'limit' => $limit]);
    }

    public function isSelectionQuery(){
        return !((bool) preg_match('/\b(insert|update)\b/i', $this->qry));
    }

    public function deleteJob(){
        $job = Job::findFirst([['rid' => $this->getId()]]);
        if($job){
            $job->delete();
        }
        return $this;
    }

    public function deletePhysicalData(){
        foreach($this->logs as $itm){
            unlink($itm['fileLocation']);
        }
        return $this;
    }

    public function beforeDelete(){
        $this->deleteJob();
        $this->deletePhysicalData();
    }

    /**
     * Send email notification to defined email addreses in $this->mail
     * @return bool|string
     */
    public function sendNotif(){
        $mail = \Phalcon\DI\FactoryDefault::getDefault()->get('mail');
        $url = \Phalcon\DI\FactoryDefault::getDefault()->get('url');
        $utility = \Phalcon\DI\FactoryDefault::getDefault()->get('utility');

        //$mail->addAddress(''); //todo here

        if($this->getLatestLog()['errors']){
            $mail->Subject = 'Report ERROR ' . $this->getDb()->name . '/' . $this->name . ' (' . $this->getLatestLog()['startTime'] . ')';
            $mail->Body = '<big>A new report for <a href="' . $url->get('db/show', ['id' => $this->getDb()->getId()]) . '" target="_blank" ><b>' . $this->getDb()->name . '/' . $this->name . '</b></a> was generated</big><br/><br/><i>' .
                '<span style="color: red">Error: ' . $this->getLatestLog()['errors'] . '</span><br/>';
        }else {
            $mail->Subject = 'Report ' . $this->getDb()->name . '/' . $this->name . ' (' . $this->getLatestLog()['startTime'] . ')';
            $mail->Body = '<big>A new report for <a href="' . $url->get('db/show', ['id' => $this->getDb()->getId()]) . '" target="_blank" ><b>' . $this->getDb()->name . '/' . $this->name . '</b></a> was generated</big><br/><br/><i>' .
                'Rows: ' . $this->getLatestLog()['rows'] . '<br/>' .
                'Time: ' . $this->getLatestLog()['totalTime'] . '<br/>' .
                'File size: ' . $utility->formatBytes($this->getLatestLog()['fileSize']) . '</i><br/><br/>' .
                '<b>Download here: <a href="' . $url->get('') . $this->getLatestLog()['fileLocation'] . '" target="_blank" style="color: orange ">' . $url->get('') . $this->getLatestLog()['fileLocation'] . '</a></b>';
        }
        $mail->AltBody = strip_tags($mail->Body);
        if($mail->send())
            return true;
        else
            return 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    }

    /**
     * Convert file from $array and and save to $location
     * @param $array
     * @param $location
     * @return array
     */
    public function generateFile($type = 'user', $fileType = 'CSV'){
        $startTime = microtime(true);
        $return = $this->setCSV($type); //TODO implement other functions
        $endTime = microtime(true);
        $totalTime = number_format($endTime-$startTime,3);
        $logData = [
            'startTime' => date('Y-m-d H:i:s', $startTime),
            'endTime' => date('Y-m-d H:i:s', $endTime),
            'totalTime' => $totalTime,
            'runType' => $type,
        ];
        $this->addLog(array_merge($return, $logData));
        return $this;
    }

    /**
     * Create a CSV file from array provided by run()->fetchAll()
     * @return array
     */
    private function setCSV(){
        try {
            $absPath = $this->getDI()->get('config')->application->publicDir;
            $reportsPath = $this->getDI()->get('config')->reportsPath;
            $hash = md5(microtime());
            $hashDir =  $reportsPath.substr($hash, 0, 2). '/' . substr($hash, 2, 2);
            $hashFile = substr($hash, 4);
            if(!is_dir($absPath.$hashDir))
                mkdir($absPath.$hashDir, 0755, true);
            $fileLocation =  $hashDir . '/' . $hashFile . '.csv';
            $fp = fopen($absPath.$fileLocation, 'w');
            $i=0;
            $result = $this->run();
            $result->setFetchMode(Phalcon\Db::FETCH_ASSOC);
            while ($row = $result->fetch()){
                // if($i==0){
                //     fputcsv($fp, $)
                // }
                fputcsv($fp, $row, ';'); $i++;
            }
            fclose($fp);
        }catch (Exception $e){
            return ['errors' => $e->getMessage()];
        }
        return [
            'fileLocation' => $fileLocation,
            'fileType' => 'csv',
            'fileSize' => filesize($absPath.$fileLocation),
            'rows' => $i,
            'errors' => false,
        ];
    }

}
