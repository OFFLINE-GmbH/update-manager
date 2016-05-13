<?php


namespace OFFLINE\UpdateManager\Strategy;


abstract class UpdateStrategy
{
    public $updatePath;
    public $targetDirectory;

    public abstract function update();
    public abstract function rollback();

    public function prepare()
    {
    }

    public function beforeUpdate()
    {
    }

    public function afterUpdate()
    {
    }

    public function cleanup()
    {
    }
}