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
    public $permissions; //set permission type to resources
    public $passHash;
    public $type; //master or operator
    public $status;
    public $session;
    public $sessionDate;
    public $config;

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

    public function setConfig($key, $val){
        $this->config[$key] = $val;
        $this->save();
        return $this;
    }

    public function getConfig($key = null){
        if($key === null)
            return $this->config;
        else {
            if(isset($this->config[$key]))
                return $this->config[$key];
        }
        return false;
    }

    public function setPermission($model, $id, $type){
        if(isset($this->to[sha1($model.$id)])){
            array_push($this->to[sha1($model . $id)]['type'], $type);
        }else {
            $this->to[sha1($model . $id)] = ['model' => $model, 'id' => $id, 'type' => $type];
        }
        return $this;
    }

    public function unsetPermission($model, $id, $type = null){
        if(isset($this->to[sha1($model.$id)]))
            if($type != null){
                $this->to[sha1($model . $id)]['type'] = array_diff($this->to[sha1($model . $id)]['type'], (array) $type);
            }else {
                unset($this->to[sha1($model . $id)]);
            }
        return $this;
    }

    public function getObjectPermissions($obj){
        $model = get_class($obj);
        $id = $obj->getId();
        if(isset($this->to[sha1($model.$id)])){
            return $this->to[sha1($model.$id)]['type'];
        }
        return [];
    }

    public function getModelIdsByPermissionType($model, $type){
        $return = [];
        foreach($this->to as $key => $val){
            if($val['model'] === $model AND in_array($type, $val['type'])){
                $return[] = $val['id'];
            }
        }
        return $return;
    }

    public function removePermissions(){
        $this->to = [];
        return $this;
    }

    public function hasPermission($obj, $type){
        if($this->type == 'master'){
            return true;
        }
        $model = get_class($obj);

        //if $model is "Db"
        if($model == 'Db'){
            $reports = $obj->getReports();
            foreach($reports as $itm){
                if(isset($this->to[sha1(get_class($itm).$itm->getId())]) && in_array($type, (array) $this->to[sha1(get_class($itm).$itm->getId())]['type']))
                    return true;
            }
            return false;
        }

        $id = $obj->getId();
        if(isset($this->to[sha1($model.$id)])){
            if(in_array($type, (array) $this->to[sha1($model.$id)]['type']))
                return true;
        }
        return false;
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
        $arr = (array) json_decode(base64_decode($cookie));
        if(isset($arr['a'])) {
            $user = User::findFirst(['conditions' => ['session' => $arr['a'], 'status' => 1]]);
            if ($user && $user->checkUserCookieHash($cookie)) {
                $user->setSession();
                $user->save();
                return $user;
            }
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

    /**
     * secure cookie
     */
    public function setUserCookieHash(){
        return base64_encode(json_encode(['a' => $this->session,'b' => sha1($this->passHash.$this->session)]));
    }

    public function checkUserCookieHash($hash){
        $arr = (array) json_decode(base64_decode($hash));
        if(sha1($this->passHash.$this->session) === $arr['b'])
            return true;
        return false;
    }

}