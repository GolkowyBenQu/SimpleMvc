<?php

namespace Framework\Exception;

class FrameworkException extends \Exception
{
    private const DEFAULT_EXCEPTION = 'Ooops, something went wrong: ';

    /**
     * FrameworkException constructor.
     * @param string $message
     */
    public function __construct(string $message)
    {
        $message = self::DEFAULT_EXCEPTION . $message;
        parent::__construct($message);
    }
}