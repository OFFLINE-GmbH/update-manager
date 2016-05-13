<?php

namespace OFFLINE\UpdateManager\Updater;


class Result
{
    public $result = null;
    public $code = null;
    public $exception = null;

    const NO_UPDATE_AVAILABLE = 'NO_UPDATE_AVAILABLE';
    const UPDATE_ERROR = 'UPDATE_ERROR';
    const UPDATE_SUCCESSFUL = 'UPDATE_SUCCESSFUL';

    public function __construct(bool $result, string $code, \Throwable $exception = null)
    {
        $this->result    = $result;
        $this->code      = $code;
        $this->exception = $exception;
    }

    public function __toString()
    {
        return (string)$this->code;
    }
}