<?php
/**
 * @author: Mihai Petcu mihai.costin.petcu@gmail.com
 * @date: 7.11.2015
 */
namespace Utility;
use Phalcon\Forms\Element;

class DateTimeSelector extends Element {

    /**
     * @TODO array merge with attr
     * Custom DateTime Selector
     * @param null $attr
     * @return string
     */
    public function render($attr = null)
    {
        $years = [date('Y') => date('Y'), (date('Y')+1) => (date('Y')+1)];
        $months = [1 => "Jan", 2 => "Feb", 3 => "Mar", 4 => "Apr", 5 => "May", 6 => "Jun", 7 => "Jul", 8 => "Aug", 9 => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec"];
        $days = array_combine(range(1,31), range(1,31));
        $hours = array_combine(range(0,23), range(0,23));
        $mins = ['00'=>0, '05'=>5, 10=>10, 15=>15, 20=>20, 25=>25, 30=>30, 35=>35, 40=>40, 45=>45, 50=>50, 55=>55];

        $val = $this->getValue();

        $html = \Phalcon\Tag::selectStatic([$this->getName().'[year]', $years, 'value' => $val['year'], 'useEmpty' => true, 'emptyText'  => 'Year', 'class' => $this->getName(), 'style' => 'min-width: auto']);
        $html .= '.'.\Phalcon\Tag::selectStatic([$this->getName().'[month]', $months, 'value' => $val['month'], 'useEmpty' => true, 'emptyText'  => 'Month', 'class' => $this->getName(), 'style' => 'min-width: auto']);
        $html .= '.'.\Phalcon\Tag::selectStatic([$this->getName().'[day]', $days, 'value' => $val['day'], 'useEmpty' => true, 'emptyText'  => 'Day', 'class' => $this->getName(), 'style' => 'min-width: auto']);

        $html .= ' &nbsp; '.\Phalcon\Tag::selectStatic([$this->getName().'[hour]', $hours, 'value' => $val['hour'], 'useEmpty' => true, 'emptyText'  => 'Hour', 'class' => $this->getName(), 'style' => 'min-width: auto']);
        $html .= ':'.\Phalcon\Tag::selectStatic([$this->getName().'[min]', $mins, 'value' => $val['min'], 'useEmpty' => true, 'emptyText'  => 'Min.', 'class' => $this->getName(), 'style' => 'min-width: auto']);

        return $html;
    }
}