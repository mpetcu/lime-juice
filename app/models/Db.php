<?php
/**
 * Database data collection
 * @author: Mihai Petcu mihai.costin.petcu@gmail.com
 * @date: 10.10.2015
 */
class Db extends \Phalcon\Mvc\Collection
{
    public $_id;
    public $database;
    public $name;
    public $report;
    public $status;

    public function getSource()
    {
        return "db";
    }

    public function beforeCreate(){
        $this->status = 1;
    }

    public function getId(){
        return $this->_id->{'$id'};
    }

    public function getDbname(){
        return @$this->database['dbname'];
    }

    public function setDbname($val){
        $this->database['dbname'] = $val;
        return $this;
    }

    public function getAdapter(){
    return @$this->database['adapter'];
}

    public function setAdapter($val){
        $this->database['adapter'] = $val;
        return $this;
    }
    public function getHost(){
        return @$this->database['host'];
    }

    public function setHost($val){
        $this->database['host'] = $val;
        return $this;
    }
    public function getUsername(){
        return @$this->database['username'];
    }

    public function setUsername($val){
        $this->database['username'] = $val;
        return $this;
    }
    public function getPassword(){
        return @$this->database['password'];
    }

    public function setPassword($val){
        $this->database['password'] = $val;
        return $this;
    }

    public function newReport(){
        $report = new Report();
        $report->setDb($this);
        return $report;
    }

    public function getReports(){
        return Report::find(['conditions' => ['did' => $this->getId()]]);
    }

    public function countReports(){
        return count($this->getReports());
    }

    /**
     * Only for mysql for now
     * @TODO for other databases later
     * @return \Phalcon\Db\Adapter\Pdo\Mysql
     */
    public function getConn(){
        if($this->database) {
            return new \Phalcon\Db\Adapter\Pdo\Mysql((array)$this->database);
        }
        return false;
    }

    /**
     * Only for mysql for now
     * @TODO for other databases later
     * @return \Phalcon\Db\Adapter\Pdo\Mysql
     */
    public function checkConn(){
        try {
            if ($this->database) {
                $con = new \Phalcon\Db\Adapter\Pdo\Mysql((array)$this->database);
            }
        }catch(Exception $e){
            return $e->getMessage();
        }
        return true;
    }





}