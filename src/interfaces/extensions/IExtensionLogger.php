<?php
namespace extas\interfaces\extensions;

/**
 * Interface IExtensionLogger
 *
 * @package extas\interfaces\extensions
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IExtensionLogger
{
    /**
     * @param string $message
     * @param array $context
     */
    public function emergency($message, array $context): void;

    /**
     * @param string $message
     * @param array $context
     */
    public function alert($message, array $context): void;

    /**
     * @param string $message
     * @param array $context
     */
    public function critical($message, array $context): void;

    /**
     * @param string $message
     * @param array $context
     */
    public function error($message, array $context): void;

    /**
     * @param string $message
     * @param array $context
     */
    public function warning($message, array $context): void;

    /**
     * @param string $message
     * @param array $context
     */
    public function notice($message, array $context): void;

    /**
     * @param string $message
     * @param array $context
     */
    public function info($message, array $context): void;

    /**
     * @param string $message
     * @param array $context
     */
    public function debug($message, array $context): void;

    /**
     * @param mixed $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context): void;
}
