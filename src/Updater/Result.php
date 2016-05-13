<?php

namespace OFFLINE\UpdateManager\Updater;


class Result
{
    public $result = null;
    public $code = null;
    public $error = null;

    const NO_UPDATE_AVAILABLE = 'NO_UPDATE_AVAILABLE';
    const UPDATE_ERROR = 'UPDATE_ERROR';
    const UPDATE_SUCCESSFUL = 'UPDATE_SUCCESSFUL';

    public function __construct(bool $result, string $code, string $error = '')
    {
        $this->result = $result;
        $this->code   = $code;
        $this->error  = $error;
    }

    public function __toString()
    {
        return (string)$this->code;
    }
}