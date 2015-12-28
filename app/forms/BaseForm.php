<?php
/**
 * Base abstract form class to be extended by forms class
 * @author: Mihai Petcu mihai.costin.petcu@gmail.com
 * @date: 12.11.2015
 */
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Message;
use Phalcon\Validation\Message\Group as MessageGroup;

abstract class BaseForm extends Form {

    /**
     * Returns the default value for field 'csrf'
     */
    public function renderCsrf()
    {
        return $this->renderItem($this->get('csrf'));
    }

    /**
     * Set Csrf
     */
    public function setCsrf()
    {
        $csrf = new Hidden(array(
            'name' => 'csrf',
            'value' => @$this->security->getToken()
        ));
        $csrf->addValidator(new Identical(array(
            'value' => $this->security->getSessionToken(),
            'message' => '<strong>CSRF</strong> security token is invalid.'
        )));
        $this->add($csrf);
    }

    /**
     * Render form field
     * @param $name
     * @param array $attr
     * @return string
     */
    public function renderDecorated($name, $attr = [])
    {
        $e = $this->get($name);
        return '<li '.($this->hasErrors($e)?'class="error"':'').'>'.$this->renderItem($e, $attr).'</li>';
    }

    /**
     * Render form field with errors displayed
     * @param $name
     * @param array $attr
     * @return string
     */
    public function renderDecoratedErrors($name, $attr = [])
    {
        $e = $this->get($name);
        return '<li '.($this->hasErrors($e)?'class="error"':'').'>'.$this->renderErrors($e).$this->renderItem($e, $attr).'</li>';
    }

    /**
     * Render classic form field
     * @param $e
     * @param array $attr
     * @return string
     */
    public function renderItem($e, $attr = []){
        return '<label for="'.$e->getName().'">'.$e->getLabel().'</label>'.$e->render($attr);
    }

    /**
     * Render errors
     * @param $e
     * @return string
     */
    public function renderFieldErrors($e){
        $m = $this->getMessagesFor($e->getName());
        if (count($m)) {
            $r = '<ul class="err_msg">';
            foreach ($m as $i) {
                $r .= '<li>'.$this->flash->error($i).'</li>';
            }
            return $r.'</ul>';
        }
    }

    /**
     * Check if specific field by $e has assigned errors
     * @param $e
     * @return bool
     */
    public function hasErrors($e){
        $m = $this->getMessagesFor($e->getName());
        if (count($m))
            return true;
        return false;
    }

    /**
     * Decorator for form errors
     * @return null|string
     */
    public function renderErrorsDecorated(){
        if(count($this->getMessages())){
            $r = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
            foreach ($this->getMessages() as $key => $message) {
                $r .= $message. '<br />';
            }
            return $r.'</div>';
        }
        return null;
    }

    /**
     * Appends custom message into form.
     *
     * @param   mixed   $message
     * @param   string  $field
     * @param   string  $type
     * @return  void
     * @throws  \Phalcon\Forms\Exception
     */
    public function appendMessage($message, $field, $type = null)
    {
        if ( is_string($message) )
        {
            $message = new Message($message, $field, $type);
        }

        if ( $message instanceof Message || $message instanceof ModelMessage )
        {
            // Check if there is a group for the field already.
            if ( ! is_null($this->_messages) && array_key_exists($field, $this->_messages) )
            {
                $this->_messages[$field]->appendMessage($message);
            }
            else
            {
                $this->_messages[$field] = new MessageGroup(array($message));
            }
        }
        else
        {
            throw new Exception("Can't append message into the form, invalid type.");
        }
    }

    /**
     * Rewrite validation class to handle afterValidateEvent
     * @param null $data
     * @param null $entity
     * @return bool
     */
    public function isValid($data = null, $entity = null){
        return (parent::isValid($data, $entity) && $this->afterValidation($data));
    }

    /**
     * afterValidation dummy class. Will return true
     * @param $data
     * @return bool
     */
    public function afterValidation($data){
        return true;
    }
}
