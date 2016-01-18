<?php
/**
 * @author: Mihai Petcu mihai.costin.petcu@gmail.com
 * @date: 18.11.2015
 */
use BaseForm as Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;

class ReportForm extends Form
{

    public function initialize()
    {
        $name = new Text("name");
        $name->setLabel('Name');
        $name->addValidator(
            new PresenceOf(
                array(
                    'message' => '<strong>Name</strong> is required.'
                )
            )
        );
        $this->add($name);

        $format = new Select(
            "format",
            [
                'CSV' => 'Standard CSV',
                'Excel' => 'Micosoft Excel (.xlsx)'
            ]
        );
        $format->setLabel('File format');
        $this->add($format);

        $qry = new TextArea("qry");
        $qry->setLabel('Query');
        $qry->addValidator(
            new PresenceOf(
                array(
                    'message' => '<strong>Query</strong> is required.'
                )
            )
        );
        $this->add($qry);

        //$this->setCsrf();

    }
}