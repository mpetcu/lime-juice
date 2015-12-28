<?php
/**
 * Task for Cron run
 * @author: Mihai Petcu mihai.costin.petcu@gmail.com
 * @date: 1.12.2015
 */
class MainTask extends \Phalcon\CLI\Task
{
    /**
     * Will run every cron. Must run every 5 minutes
     */
    public function mainAction()
    {
        $this->refreshCronJobs();
        $jobs = Job::find([
            'conditions' => [
                'nextRun' => [
                    '$gt' => time() - 5*60+1, //5 min.
                    '$lte' => time() + 5*60-1
                ],
                'status' => 1
            ]
        ]);

        if(count($jobs)){
            foreach($jobs as $job){
                $report = $job->getReport();
                $report->generateFile('cron', 'CSV');
                $report->save();
                $job->save();
            }
        }

    }

    /**
     * Revive crashed crons
     */
    private function refreshCronJobs(){
        $jobs = Job::find([
            'conditions' => [
                'nextRun' => [
                    '$lte' => time() - 5*60 //5 min.
                ],
                'status' => 1
            ]
        ]);
        foreach ($jobs as $job){
            $job->save();
        }

    }
}