<?php

namespace OFFLINE\UpdateManager;


use Composer\Semver\Comparator;
use OFFLINE\UpdateManager\Repository\Info;
use OFFLINE\UpdateManager\Repository\Repository;
use OFFLINE\UpdateManager\Strategy\UpdateStrategy;
use OFFLINE\UpdateManager\Updater\Result;
use OFFLINE\UpdateManager\Updater\Updater;
use Throwable;

class UpdateManager
{
    /**
     * @var Repository
     */
    public $repo;
    /**
     * @var string
     */
    public $currentVersion;
    /**
     * @var string
     */
    private $targetDirectory;

    public function __construct(Repository $repo, string $currentVersion, string $targetDirectory)
    {
        $this->repo            = $repo;
        $this->currentVersion  = $currentVersion;
        $this->targetDirectory = $targetDirectory;
    }

    public static function load(string $file, string $currentVersion, string $targetDirectory) : UpdateManager
    {
        return new static(new Repository(new Info($file)), $currentVersion, $targetDirectory);
    }

    public function hasUpdate() : bool
    {
        return Comparator::greaterThan($this->repo->latestVersion(), $this->currentVersion);
    }

    public function update(UpdateStrategy $strategy) : Result
    {
        if ( ! $this->hasUpdate()) {
            return new Result(true, Result::NO_UPDATE_AVAILABLE);
        }

        $nextVersion = $this->repo->nextVersion($this->currentVersion);

        $updater = new Updater($this->repo, $nextVersion, $this->targetDirectory);
        try {
            $updater->run($strategy);
        } catch (Throwable $e) {
            return new Result(false, Result::UPDATE_ERROR, $e);
        }

        return new Result(true, Result::UPDATE_SUCCESSFUL);
    }
}