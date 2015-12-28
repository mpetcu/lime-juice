<?php
    /**
     * @author: Mihai Petcu mihai.costin.petcu@gmail.com
     * @date: 15.12.2015
     */
    use BaseForm as Form;
    use Phalcon\Forms\Element\Text;
    use Phalcon\Forms\Element\Select;
    use Phalcon\Validation\Validator\PresenceOf;
    use Utility\UniquenessValidator as Uniqueness;
    use Phalcon\Validation\Validator\Email as EmailValidator;

class UserRequestForm extends Form
{

    public function initialize()
    {
        $email = new Text("email");
        $email->setLabel('Email');
        $email->addValidator(
            new PresenceOf(['message' => '<strong>Email</strong> address is required.'])
        );
        $this->add($email);


        $type = new Select('type',
            [
            'master' => 'master',
            'operator'  => 'operator'
            ]
        );
        $type->setLabel('Type');
        $type->setDefault('operator');
        $this->add($type);

        //$this->setCsrf();
    }

    /**
     * Rewrite bind event to overload
     * @param array $data
     * @param $entity
     * @param null $whitelist
     */
    public function bind(array $data, $entity, $whitelist=null){
        //Validator for email address format
        if (!empty($data['email'])) {
            $this->get('email')->addValidator(
                new EmailValidator(['message' => '<strong>Email</strong> address is invalid.'])
            );

            $this->get('email')->addValidator(
                new Uniqueness(['collection' => 'User', 'field'=> 'email', 'message' => '<strong>Email</strong> is already used by another account.'])
            );
        }
        parent::bind($data, $entity, $whitelist);
    }
}