<?php

use OFFLINE\UpdateManager\Repository\Info;
use OFFLINE\UpdateManager\Repository\Repository;

abstract class BaseTest extends PHPUnit_Framework_TestCase
{
    protected function getInfo() : Info
    {
        return new Info(__DIR__ . '/stubs/repo.yaml');
    }

    protected function getRepo() : Repository
    {
        return new Repository($this->getInfo());
    }
}