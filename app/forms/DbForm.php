<?php
/**
 * @author: Mihai Petcu mihai.costin.petcu@gmail.com
 * @date: 10.11.2015
 */
use BaseForm as Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Message\Group as MessageGroup;


class DbForm extends Form
{

    public function initialize()
    {
        $name = new Text("name");
        $name->setLabel('Name');
        $name->addValidator(
            new PresenceOf(
                [
                    'message' => '<strong>Name</strong> is required.'
                ]
            )
        );
        $this->add($name);

        $adapter = new Select(
            "adapter",
            [
                'Mysql' => 'MySQL',
//                'Postgresql' => 'PostgreSQL'
            ]
        );
        $adapter->setLabel('DB type');
        $this->add($adapter);

        $host = new Text("host");
        $host->setLabel('DB host');
        $host->addValidator(
            new PresenceOf(
                [
                    'message' => '<strong>DB host</strong> is required.'
                ]
            )
        );
        $this->add($host);

        $user = new Text("username");
        $user->setLabel('DB user');
        $user->addValidator(
            new PresenceOf(
                array(
                    'message' => '<strong>DB user</strong> is required.'
                )
            )
        );
        $this->add($user);

        $pass = new Text("password");
        $pass->setLabel('DB pass');
        $this->add($pass);

        $dbname = new Text("dbname");
        $dbname->setLabel('DB name');
        $dbname->addValidator(
            new PresenceOf(
                array(
                    'message' => '<strong>DB name</strong> is required.'
                )
            )
        );
        $this->add($dbname);

        //$this->setCsrf();

    }

    /**
     * Append a global message if new connection is invalid
     * @param $data
     * @return bool
     */
    public function afterValidation($data){
        $conn = $this->getEntity()->checkConn();
        if($conn === true){
            return true;
        }
        $this->_messages['global'] = new MessageGroup(array("<strong>Database connection invalid</strong>: ".$conn));
        return false;

    }
}