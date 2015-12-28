<?php
/**
 * Job model collection
 * @author: Mihai Petcu mihai.costin.petcu@gmail.com
 * @date: 19.11.2015
 */
use Cron\CronExpression as CronExpression;

class Settings extends \Phalcon\Mvc\Collection
{
    public $_id;
    public $label;
    public $data;
    public $type;
}