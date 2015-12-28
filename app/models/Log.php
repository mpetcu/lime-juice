<?php
/**
 * Log model collection
 * @author: Mihai Petcu mihai.costin.petcu@gmail.com
 * @date: 20.11.2015
 */
use Cron\CronExpression as CronExpression;

class Log extends \Phalcon\Mvc\Collection
{
    public $_id;
    public $rid;
    public $startTime;
    public $endTime;
    public $totalTime;
    public $runType;
    public $fileLocation;
    public $fileType;
    public $fileSize;
    public $rows;
    public $errors;

    public function getSource(){
        return "log";
    }

    public function getId(){
        return $this->_id->{'$id'};
    }

    public function setReport(Report $report){
        $this->rid = $report->getId();
    }

    public function getReport(){
        return Report::findById($this->rid);
    }

}