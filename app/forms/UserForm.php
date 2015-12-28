<?php
/**
 * @author: Mihai Petcu mihai.costin.petcu@gmail.com
 * @date: 10.12.2015
 */
use BaseForm as Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\Email as EmailValidator;

class UserForm extends Form
{

    public function initialize()
    {
        $fname = new Text("firstName");
        $fname->setLabel('First name');
        $fname->addValidator(
            new PresenceOf(['message' => '<strong>First name</strong> is required.'])
        );
        $this->add($fname);


        $lname = new Text("lastName");
        $lname->setLabel('Last name');
        $lname->addValidator(
            new PresenceOf(['message' => '<strong>Last name</strong> is required.'])
        );
        $this->add($lname);


        $email = new Text("email");
        $email->setLabel('Email');
//        $email->addValidator(new Uniqueness(
//            array(
//                "model"   => "User",
//                "message" => "This <strong>Email</strong> address is already used by other account."
//            )
//        ));
        $email->addValidator(
            new PresenceOf(['message' => '<strong>Email</strong> address is required.'])
        );
        $email->addValidator(
            new EmailValidator(['message' => '<strong>Email</strong> address is invalid.'])
        );
        $this->add($email);

        //$this->setCsrf();
    }


}