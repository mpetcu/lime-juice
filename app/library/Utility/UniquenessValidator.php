<?php
/**
 * @author: Mihai Petcu mihai.costin.petcu@gmail.com
 * @date: 18.12.2015
 */

namespace Utility;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;
use Phalcon\Validation\ValidatorInterface;


class UniquenessValidator extends Validator implements ValidatorInterface {
    /**
     * Custom date validator
     * @param \Phalcon\Validation $validator
     * @param string $attribute
     * @return boolean
     */
    public function validate(\Phalcon\Validation $validator, $attribute)
    {
        $val = $validator->getValue($attribute);
        $collection = $this->getOption('collection');
        if($collection && $this->getOption('field')){
            $entity = $collection::find(['conditions' => [$this->getOption('field') => trim($val)]]);
            if(count($entity) > 0) {
                $message = $this->getOption('message');
                if (!$message) {
                    $message = 'Input data not unique.';
                }
                $validator->appendMessage(new Message($message, $attribute, $this->getOption('field')));
                return false;
            }
        }
        return true;
    }
}