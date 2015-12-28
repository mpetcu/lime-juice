<?php
/**
 * @author: Mihai Petcu mihai.costin.petcu@gmail.com
 * @date: 7.11.2015
 */
namespace Utility;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;
use Phalcon\Validation\ValidatorInterface;

class DateTimeValidator extends Validator implements ValidatorInterface
{
    /**
     * Custom date validator
     * @param \Phalcon\Validation $validator
     * @param string $attribute
     * @return boolean
     */
    public function validate(\Phalcon\Validation $validator, $attribute)
    {
        $val = $validator->getValue($attribute);
        $date = $val['year'].'-'.$val['month'].'-'.$val['day'].' '.$val['hour'].':'.$val['min'];
        if(!$this->validateDate($date)) {
            $message = $this->getOption('message');
            if (!$message) {
                $message = 'Selected date and time is invalid ';
            }
            $validator->appendMessage(new Message($message, $attribute, 'datetime'));
            return false;
        }
        return true;
    }

    /**
     * Function that validates a given $date
     * @param $date
     * @return bool
     */
    private function validateDate($date)
    {
        $d = \DateTime::createFromFormat('Y-n-j G:i', $date);
        return $d && $d->format('Y-n-j G:i') == $date;
    }

}