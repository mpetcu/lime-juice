<?php
/**
 * @author: Mihai Petcu mihai.costin.petcu@gmail.com
 * @date: 10.11.2015
 */
use BaseForm as Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\Email;

class MailReportForm extends Form
{

    public function initialize()
    {
        $mail = new Text("mail");
        $mail->setLabel('To:');
        $mail->addValidator(
            new Email(
                array(
                    'message' => 'The e-mail is not valid'
                )
            )
        );
        $this->add($mail);

        //$this->setCsrf();
    }
}