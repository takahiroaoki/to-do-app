<?php

class Log
{
    public static function getLogWriter(): Zend_Log
    {
        $logger = new Zend_Log(
            new Zend_Log_Writer_Stream(LOG_PATH)
        );
        // Set log level filter
        $logger->addFilter(new Zend_Log_Filter_Priority(Zend_Log::INFO));

        return $logger;
    }

    public static function getMessage(?string $userId, string $content): string
    {
        $userPart = 'user_id=';
        if (is_null($userId)) {
            $userPart = $userPart . 'unknown';
        } else {
            $userPart = $userPart . $userId;
        }
        return $userPart . ', ' . $content;
    }
}