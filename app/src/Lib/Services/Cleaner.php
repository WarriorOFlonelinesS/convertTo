<?php
namespace App\Lib\Services;

use App\Lib\Constants;

class Cleaner
{
    public static function clean($fileName)
    {
        $path = dirname($fileName);
        if ($handle = opendir($path)) {
            while (false !== ($file = readdir($handle))) {
                if (!preg_match(Constants::HTACCESS_FILE_PATTERN, $file)) {
                    unlink($path . '/' . $file);
                }
                ;
            }
        }

    }
}