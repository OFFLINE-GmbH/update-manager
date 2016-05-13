<?php

namespace OFFLINE\UpdateManager\Updater;


use OFFLINE\UpdateManager\Repository\Repository;
use OFFLINE\UpdateManager\Strategy\UpdateStrategy;

class Updater
{
    /**
     * @var Repository
     */
    private $repository;
    /**
     * @var string
     */
    private $targetVersion;
    /**
     * @var string
     */
    private $targetDirectory;

    public function __construct(Repository $repository, string $targetVersion, string $targetDirectory)
    {
        $this->repository      = $repository;
        $this->targetVersion   = $targetVersion;
        $this->targetDirectory = $targetDirectory;
    }


    public function run(UpdateStrategy $strategy)
    {
        $strategy->updatePath = $this->updatePath();
        $strategy->targetDirectory = $this->targetDirectory;

        $exception = false;

        try {
            $strategy->prepare();
            $strategy->beforeUpdate();
            $strategy->update();
        } catch (\Throwable $t) {
            $exception = $t;
            $strategy->rollback();
        }

        $strategy->afterUpdate();
        $strategy->cleanup();

        if ($exception) {
            throw $exception;
        }
    }


    /**
     * Returns the path to the update file.
     *
     * @return string
     */
    protected function updatePath()
    {
        $location = trim($this->repository->location(), '/');
        $path     = $location . DIRECTORY_SEPARATOR . $this->repository->filename($this->targetVersion);

        return $path;
    }
}