<?php
/**
 * @author: Mihai Petcu mihai.costin.petcu@gmail.com
 * @date: 10.11.2015
 */
use BaseForm as Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Check;
use Utility\DateTimeSelector;
use Utility\DateTimeValidator;
use Phalcon\Validation\Validator\PresenceOf;

class JobForm extends Form
{

    public function initialize()
    {
        $type = new Select("type", ['once' => 'Only once', 'repetitiv' => 'Repetitiv']);
        $type->setLabel('Run type:');
        $this->add($type);

        $time = new DateTimeSelector("datetime");
        $time->setLabel('Run once at:');
        $this->add($time);


        $job_sel = new Select("job_sel", Job::getJobOptions(),
            [
                'useEmpty' => true,
                'emptyText' => 'Please choose...',
            ]);
        $job_sel->setLabel('Run every:');
        $this->add($job_sel);


        $job = new Text("job");
        $job->setLabel('Custom def.:');
        $this->add($job);


        $status = new Check('status', [
            'value' => 1,
            'style' => 'min-width: auto'
        ]);
        $status->setLabel('Is active:');
        $status->setDefault(1);
        $this->add($status);

        //$this->setCsrf();
    }

    public function getJobOptions(){
        $return = [];
        foreach($this->optArr as $k => $v){
            $return[$v['cron']] = $v['label'];
        }
        $return['custom'] = 'Custom';
        return $return;
    }

    public function bind(array $data, $entity, $whitelist=null){
        if(!isset($data['status']))
            $data['status'] = 0;

        //Validator for date and time
        if($data['type'] == 'once') {
            $this->get('datetime')->addValidator(
                new DateTimeValidator()
            );
        }

        //validator for job_sel
        if($data['type'] == 'repetitiv') {
            $this->get('job_sel')->addValidator(
                new PresenceOf([
                        'message' => 'Choose a period to setup a cron.'
                    ]
                )
            );
        }

        //Validator for cron custom definition
        if ($data['job_sel'] == 'custom') {
            $this->get('job')->addValidator(
                new PresenceOf(
                    array(
                        'message' => 'The cron custom def. is required.'
                    )
                )
            );
        }

        parent::bind($data, $entity, $whitelist);
    }
}