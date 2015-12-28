<?php
/**
 * @author: Mihai Petcu mihai.costin.petcu@gmail.com
 * @date: 6.11.2015
 */
namespace Utility;

class Utility extends \Phalcon\Tag{

    /**
     * Convert from bytes to "KB", "MB", "GB" if is the case
     * @param int $bytes Number of bytes (eg. 25907)
     * @param int $precision [optional] Number of digits after the decimal point (eg. 1)
     * @return string Value converted with unit (eg. 25.3KB)
     */
    public function formatBytes($bytes, $precision = 2) {
        $unit = ["B","KB","MB","GB"];
        if($bytes<1)
            return '0'.$unit[0];
        $exp = floor(log($bytes, 1024)) | 0;
        return round($bytes/(pow(1024, $exp)), $precision).$unit[$exp];
    }

    /**
     * Get final file path
     * @param string $filepath
     * @return string
     */
    public function getFile($filePath) {
        return '/'.$filePath;
    }

    /**
     * Format date to specific $format or default
     * @param $dateString
     * @param string $format
     * @return bool|string
     */
    public function formatDate($dateString, $format = 'D, j M Y g:i a') {
        return date($format,strtotime($dateString));
    }

    /**
     * Check if a give email by $emailString is valid;
     * @param $emailString
     * @return bool
     */
    public function isEmailValid($emailString) {
        //TODO
        return true;
    }
}