<?php
/**
 * User model collection
 * @author: Mihai Petcu mihai.costin.petcu@gmail.com
 * @date: 22.11.2015
 */
use Cron\CronExpression as CronExpression;

class User extends \Phalcon\Mvc\Collection
{
    public $_id;
    public $email;
    //public $firstName;
    //public $lastName;
    public $passHash;
    public $type; //master or operator
    public $status;
    public $session;
    public $sessionDate;

    public function getSource(){
        return "user";
    }

    public function beforeCreate(){
        $this->status = 1;
        if(!$this->type)
            $this->type = 'operator';
    }

    public function getId(){
        return $this->_id->{'$id'};
    }

    public function setPass($pass){
        $this->passHash = User::getPassHash($pass);
    }

    public function setPass2($pass){
        return;
    }
    public function setOldPass($pass){
        return;
    }

    public function setSession(){
        $this->session = sha1(microtime().$this->getDI()->get('config')->hash);
        $this->sessionDate = date('Y-m-d H:i:s');
    }

    public static function loginUser($email, $pass){
        $user = User::findFirst([ 'conditions' => ['email' => $email, 'passHash' => User::getPassHash($pass), 'status' => 1]]);
        if($user){
            $user->setSession();
            $user->save();
            return $user;
        }
        return false;
    }

    public static function loginUserFromCookie($cookie){
        $user = User::findFirst([ 'conditions' => ['session' => $cookie->getValue(), 'status' => 1]]);
        if($user){
            $user->setSession();
            $user->save();
            return $user;
        }
        return false;
    }

    public static function getPassHash($pass){
        $di = \Phalcon\DI::getDefault();
        return sha1($di->get('config')->hash.$pass);
    }

    public function getName(){
        if(isset($this->firstName) || isset($this->lastName)){
            return $this->firstName.' '.$this->lastName;
        }
        return $this->email;
    }

    public function setStatus($status){
        $this->status = (int) $status;
    }

}