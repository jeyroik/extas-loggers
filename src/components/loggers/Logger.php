<?php
namespace extas\components\loggers;

use extas\components\Item;
use extas\components\samples\parameters\THasSampleParameters;
use extas\components\TDispatcherWrapper;
use extas\components\THasTags;
use extas\interfaces\loggers\ILogger;

/**
 * Class Logger
 *
 * @package extas\components\loggers
 * @author jeyroik <jeyroik@gmail.com>
 */
class Logger extends Item implements ILogger
{
    use TDispatcherWrapper;
    use THasTags;
    use THasSampleParameters;

    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
