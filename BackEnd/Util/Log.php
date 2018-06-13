<?php
/**
 * Created by PhpStorm.
 * User: Isaque
 * Date: 07/08/2017
 * Time: 10:50
 */

namespace Ciente\Util;

class Log
{
    public static function i($TAG, $message,$user = null,$logFile='logs.txt')
    {
        // Write the log message to the file
        self::appendLog(Utils::GetDateTimeNow(), $TAG,$message,$user,$logFile);
    }
    public static function appendLog($date, $tag, $message,$user='',$logFile='logs.txt')
    {
        $txt = 'TAG: '.$tag.' # Message:'.$message.' # DateTime:'.$date.' # USER:'.$user;
        file_put_contents($logFile, $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
    }
}