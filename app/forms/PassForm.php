<?php
/**
 * @author: Mihai Petcu mihai.costin.petcu@gmail.com
 * @date: 10.11.2015
 */
use BaseForm as Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\StringLength;


class PassForm extends Form
{

    public function initialize()
    {
        $pass = new Password("pass");
        $pass->setLabel('Pass');
        $pass->addValidator(new StringLength(array('min' => 6, 'messageMinimum' => '<b>Pass</b> must be at least 6 chars long.')));
        $pass->addValidator(new Confirmation(array('with' => 'pass2', "message" => "<b>Pass</b> and <b>Retype pass</b> does not match.")));
        $this->add($pass);


        $pass2 = new Password("pass2");
        $pass2->setLabel('Retype pass');
        $this->add($pass2);


        $oldpass = new Password("oldPass");
        $oldpass->setLabel('Old pass');
        $oldpass->addValidator(
            new PresenceOf(['message' => '<strong>Email</strong> address is required.'])
        );
        $this->add($oldpass);

        //$this->setCsrf();
    }


}