<?php
/**
 * Job model collection
 * @author: Mihai Petcu mihai.costin.petcu@gmail.com
 * @date: 13.10.2015
 */
use Cron\CronExpression as CronExpression;

class Job extends \Phalcon\Mvc\Collection
{
    public $_id;
    public $rid;
    public $type;
    public $datetime;
    public $job;
    public $status;
    public $nextRun;
    private $formatDateRun = 'Y-m-d H:i:s'; //setup with date dormat for return from $nextRun timestamp
    private static $optArr = [ //predefined job timing
        [
            'label' => '5 min.',
            'cron' => '*/5 * * * *'
        ],
        [
            'label' => '15 min.',
            'cron' => '*/15 * * * *'
        ],
        [
            'label' => '30 min.',
            'cron' => '*/30 * * * *'
        ],
        [
            'label' => '45 min.',
            'cron' => '*/45 * * * *'
        ],
        [
            'label' => 'Hour',
            'cron' => '0 * * * *'
        ],
        [
            'label' => '3 hours',
            'cron' => '* */3 * * *'
        ],
        [
            'label' => '6 hours',
            'cron' => '* */6 * * *'
        ],
        [
            'label' => '9 hours',
            'cron' => '* */9 * * *'
        ],
        [
            'label' => '12 hours',
            'cron' => '* */12 * * *'
        ],
        [
            'label' => 'Day (12am)',
            'cron' => '0 0 * * *'
        ],
        [
            'label' => 'Day (12pm)',
            'cron' => '0 12 * * *'
        ],
        [
            'label' => 'Monday',
            'cron' => '0 0 * * 1'

        ],
        [
            'label' => 'Friday',
            'cron' => '0 0 * * 5'
        ],
        [
            'label' => '3 days',
            'cron' => '0 0 */3 * *'
        ],
        [
            'label' => '6 days',
            'cron' => '0 0 */6 * *'
        ],
        [
            'label' => '10 days',
            'cron' => '0 0 */10 * *'
        ],
        [
            'label' => '15 days',
            'cron' => '0 0 */15 * *'
        ],
        [
            'label' => '20 days',
            'cron' => '0 0 */20 * *'
        ],
        [
            'label' => 'Month',
            'cron' => '0 0 1 * *'
        ],

    ];

    public function getSource(){
        return "job";
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

    public function setStatus($status){
        $this->status = (int) $status;
    }

    /**
     * Get options from predefined timings in $optArr
     * @return array
     */
    public static function getJobOptions(){
        $return = [];
        foreach(self::$optArr as $k => $v){
            $return[$v['cron']] = $v['label'];
        }
        $return['custom'] = 'Custom';
        return $return;
    }

    /**
     * Set nextRun in timestamp format before save object
     */
    public function beforeSave(){
        if($this->type == 'repetitiv'){
            if($this->job_sel == 'custom'){
                $exp = $this->job;
            }else{
                $exp = $this->job_sel;
            }
            $this->nextRun = CronExpression::factory($exp)
                                ->getNextRunDate()
                                ->getTimestamp();
        }else{
            $mkt = mktime($this->datetime['hour'], $this->datetime['min'], 0, $this->datetime['month'], $this->datetime['day'], $this->datetime['year']);
            $this->nextRun = $mkt;
        }
    }

    /**
     * return nextRun in format defined in $formatDateRun
     * @return bool|string
     */
    public function getNextRun(){
        return date($this->formatDateRun, $this->nextRun);
    }

}