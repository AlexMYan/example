<?php
namespace MyPsr;

use \Psr\Log\LoggerInterface,
    \Psr\Log\LogLevel;

/**
 * Класс, реализующий  интерфейс LoggerInterface.
 *
 * Class FilesLogger
 * @package MyPsr
 */
class FilesLogger implements LoggerInterface
{

    public function emergency($message, array $context = []): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    public function alert($message, array $context = []): void
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }
    public function critical($message, array $context = array())
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    public function error($message, array $context = array())
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    public function warning($message, array $context = array())
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    public function notice($message, array $context = array())
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    public function info($message, array $context = array())
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    public function debug($message, array $context = array())
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    public function log($level, $message, array $context = []): void
    {

        //данные $context в формат json
        $contextString = $context ? json_encode($context):'{}';

        // Формируем сообщение
        $message = sprintf(
            '[%s] %s: %s%s',
            $this->getTime(),
            $level,
            $message,
            $contextString,
        );

        // Пишем в файл myLog.log
        file_put_contents('myLog.log', $message.PHP_EOL, FILE_APPEND | LOCK_EX);
        // FILE_APPEND - позволяет добавлять записи к существующим, не затирая старые логи
         //LOCK_EX - блокирует файл на время записи.

    }

    /**
     * Текущая дата в формате 1970-12-01 23:59:59
     *
     * @return string
     */
    private function getTime():string
    {
        return (new \DateTimeImmutable('now'))->format('Y-m-d H:i:s.u');
    }


}
