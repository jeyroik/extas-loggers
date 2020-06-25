<?php
namespace extas\components\extensions;

use extas\interfaces\extensions\IExtensionLogger;
use extas\interfaces\loggers\ILogger;
use extas\interfaces\repositories\IRepository;
use Psr\Log\LoggerInterface;

/**
 * Class ExtensionLogger
 *
 * @method IRepository loggers()
 *
 * @package extas\components\extensions
 * @author jeyroik <jeyroik@gmail.com>
 */
class ExtensionLogger extends Extension implements IExtensionLogger
{
    /**
     * @param string $message
     * @param array $context
     */
    public function emergency($message, array $context): void
    {
        $this->log('emergency', $message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function alert($message, array $context): void
    {
        $this->log('alert', $message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function critical($message, array $context): void
    {
        $this->log('critical', $message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function error($message, array $context): void
    {
        $this->log('error', $message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function warning($message, array $context): void
    {
        $this->log('warning', $message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function notice($message, array $context): void
    {
        $this->log('notice', $message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function info($message, array $context): void
    {
        $this->log('info', $message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function debug($message, array $context): void
    {
        $this->log('debug', $message, $context);
    }

    /**
     * @param mixed $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context): void
    {
        foreach ($this->getLoggers() as $loggerContainer) {
            /**
             * @var ILogger $loggerContainer
             * @var LoggerInterface $logger
             */
            $logger = $loggerContainer->buildClassWithParameters($loggerContainer->getParametersValues());
            $logger->$level($message, $context);
        }
    }

    /**
     * @return \Generator
     */
    protected function getLoggers()
    {
        $loggers = $this->loggers()->all($this->getParametersValues());
        foreach ($loggers as $logger) {
            yield $logger;
        }
    }
}
