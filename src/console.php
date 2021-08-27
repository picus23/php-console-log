<?php
/** 
 * Красивый лог
 */
namespace x;
class console
{

    static $log_path = '';
    static $isBlockLogs = false;
    static $logSold = '';
    static $lastFreezeWriteStr = '';
    static $logs = '';
    static $isLogFrize = '';
    /** 
     * Запись логов
     */
    static function log($text)
    {
        if (self::$isBlockLogs) return false;

        $text = "$text";
        if (self::$isLogFrize) {
            file_put_contents($this->getLogFullPathName(), '');
            self::$isLogFrize = false;
        }



        if (self::$isLogFrize) {
            if (self::$lastFreezeWriteStr!=$text) {
                self::$lastFreezeWriteStr = $text;
                file_put_contents(self::getLogFullPathName(), self::$logs . $text);
            }
        } else {
            $memory = round(100 / memory_get_peak_usage() * memory_get_usage()) ."% memory | ";
            file_put_contents(self::getLogFullPathName(), "<br>\n". $memory . $text, FILE_APPEND);
        }
        return self::class;
    }
    
    static function freezeLog($isFreeze)
    {
        self::$isLogFrize = $isFreeze;
        if (self::$isLogFrize) {
            self::$logs = file_get_contents(self::getLogFullPathName());
        } else {
            self::$logs = '';
        }
    }

    static function readLogs()
    {
        return file_get_contents(self::getLogFullPathName());
    }

    static function setLogSold($sold)
    {
        self::$logSold = $sold;
        return self::class;
    }

    static function getLogFullPathName()
    {
        return self::$log_path . self::$logSold . '.log';
    }


    /** 
     * Инициализация
     */
    function __construct()
    {
        if (isset($_SERVER['DOCUMENT_ROOT'])){
            self::$log_path = $_SERVER['DOCUMENT_ROOT'] . "/REVOLUTION/INTERFACE/parse_logs/__console__";
        } else {
            self::$log_path = __DIR__ . '/__console__';
        }
    }

    static function init($begin = ''){
        if ($begin=='') $begin = date('H:i d:m:Y', time() + 60*60);
        file_put_contents(self::getLogFullPathName(), $begin . "<hr>\n");
    }

    /** 
     * Сохранение логов при завершениии работы скрипта
     */
    // function __destruct()
    // {
    //     self::saveLog();
    //     // tlg('Парсинг завершен - серия '. $this->seria_id);
    //     // $this->log('Парсинг завершен!');
    // }


    // static function saveLog()
    // {
    //     echo "[SAVE LOGS]";
    // }
}

return new console();