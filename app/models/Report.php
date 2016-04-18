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
    public $format;
    public $notif;

    public function getSource(){
        return "report";
    }

    public function getId(){
        return $this->_id->{'$id'};
    }

    /**
     * Return notif array
     */
    public function getNotif($uid = null){
        if($uid !== null){
            if(isset($this->notif) && in_array($uid, $this->notif)){
                return array_intersect($this->notif, [$uid]);
            }
            return [];
        }
        return isset($this->notif)?$this->notif:[];
    }


    public function setNotif($uid){
        $this->notif[] = $uid;
        return $this->notif;
    }

    public function unsetNotif($uid){
        $this->notif = array_diff($this->notif, $uid);
        return $this->notif;
    }

    public function beforeCreate(){
        $this->status = 1;
        $this->logCount = 0;
        if($this->format == null){
            $this->format = 'CSV';
        }
    }

    public function beforeUpdate(){
        $this->setLogCount((int) count(Log::find(['conditions' => ['rid' => $this->getId()]])));
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
    public function getLatestLog($num = 1){
        if($num == 1)
            return Log::findFirst(['conditions' => ['rid' => $this->getId()], 'sort' => ['_id' => -1]]);
        else
            return Log::find(['conditions' => ['rid' => $this->getId()], 'sort' => ['_id' => -1], 'limit' => $num]);
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
        $notifUids = $this->getNotif();

        if($notifUids) {
            $mail = \Phalcon\DI\FactoryDefault::getDefault()->get('mail');
            $url = \Phalcon\DI\FactoryDefault::getDefault()->get('url');
            $utility = \Phalcon\DI\FactoryDefault::getDefault()->get('utility');

            //$users = User::find(['conditions' => ['_id' => ['$in' => $notifUids]]]);
            $users = User::find();

            if(count($users)) {
                foreach ($users as $user) {
                    $mail->addAddress($user->email);
                }
                $log = $this->getLatestLog(1);
                if ($log->errors) {
                    return false;
                } else {
                    $mail->Subject = 'Report executed [Report manager]';
                    $mail->Body = 'A new report was generated<br/><br/>' .
                        'Report: <strong><a href="' . $url->get('report/index', ['id' => $this->getDb()->getId()]) . '#' .$this->getId().'" target="_blank" >' . $this->name . '<br/></strong>' .
                        'Database: <strong><a href="' . $url->get('report/index', ['id' => $this->getDb()->getId()]) . '" target="_blank" >' . $this->getDb()->name . '<br/></strong>' .
                        'Executed at: ' . $utility->formatDate($log->startTime ) . '<br/>' .
                        'Rows: ' . $log->rows . '<br/>' .
                        'Duration: ' . $log->totalTime . ' sec<br/>' .
                        'File size: ' . $utility->formatBytes($log->fileSize) . '</i><br/><br/>' .
                        'Download here: <a href="' . $url->get('') . $log->fileLocation . '" target="_blank" style="color: orange ">' . $url->get('') . $log->fileLocation . '</a>';
                }
                $mail->AltBody = strip_tags($mail->Body);
                if ($mail->send())
                    return true;
                else
                    return 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
            }
        }
    }

    /**
     * Convert file from $array and and save to $location
     * @param $array
     * @param $location
     * @return array
     */
    public function generateFile($type = 'user', $fileType = null){
        $fileType = $fileType === null?$this->format:$fileType;
        $startTime = microtime(true);
        $return = $this->{"set$fileType"}(); //TODO implement other functions
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
                if($i==0)
                    fputcsv($fp, array_keys($row));
                fputcsv($fp, $row); $i++;
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

    /**
     * Create a Excel file from array provided by run()->fetchAll()
     * @return array
     */
    private function setExcel(){
        try {
            $absPath = $this->getDI()->get('config')->application->publicDir;
            $reportsPath = $this->getDI()->get('config')->reportsPath;
            $hash = md5(microtime());
            $hashDir =  $reportsPath.substr($hash, 0, 2). '/' . substr($hash, 2, 2);
            $hashFile = substr($hash, 4);
            if(!is_dir($absPath.$hashDir))
                mkdir($absPath.$hashDir, 0755, true);

            $exObj = new PHPExcel();
            $exObj->setActiveSheetIndex(0);
            //$exObj->getActiveSheet()->setCellValue('A1', 'TEST TEST TEST');

            $fileLocation =  $hashDir . '/' . $hashFile . '.xlsx';

            $i=0;
            $result = $this->run();
            $result->setFetchMode(Phalcon\Db::FETCH_ASSOC);
            $eas = $exObj->getActiveSheet();

            //custom table header format for excel
            $styleArray = array(
                'font'  => array(
                    'bold'  => true,
                    'color' => array('rgb' => '000000'),
                ),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'FFA500')
                )
            );

            while ($row = $result->fetch()){
                if($i==0){
                    $j = 'A';
                    foreach (array_keys($row) as $cell) {
                        $eas->setCellValue(($j) . ($i + 1), $cell);
                        $eas->getStyle(($j++) . ($i + 1))->applyFromArray($styleArray);
                    }
                    $i++;
                }
                $j = 'A';
                foreach ($row as $cell)
                    $eas->setCellValue(($j++).($i+1), $cell);
                $i++;
            }
            $wObj = new PHPExcel_Writer_Excel2007($exObj);
            $wObj->save($absPath.$fileLocation);


        }catch (Exception $e){
            return ['errors' => $e->getMessage()];
        }
        return [
            'fileLocation' => $fileLocation,
            'fileType' => 'csvExcel',
            'fileSize' => filesize($absPath.$fileLocation),
            'rows' => $i,
            'errors' => false,
        ];
    }

}
