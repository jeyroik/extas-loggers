<?php
namespace extas\interfaces\loggers;

use extas\interfaces\IDispatcherWrapper;
use extas\interfaces\IHasTags;
use extas\interfaces\IItem;
use extas\interfaces\samples\parameters\IHasSampleParameters;

/**
 * Interface ILogger
 *
 * @package extas\interfaces\loggers
 * @author jeyroik <jeyroik@gmail.com>
 */
interface ILogger extends IItem, IDispatcherWrapper, IHasTags, IHasSampleParameters
{
    public const SUBJECT = 'extas.logger';
}
