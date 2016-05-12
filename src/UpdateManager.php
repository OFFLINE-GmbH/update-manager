<?php

namespace OFFLINE\UpdateManager;


use Composer\Semver\Comparator;
use OFFLINE\UpdateManager\Repository\Info;
use OFFLINE\UpdateManager\Repository\Repository;

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

    public function __construct(Repository $repo, string $currentVersion)
    {
        $this->repo           = $repo;
        $this->currentVersion = $currentVersion;
    }

    public static function load(string $file, string $currentVersion) : UpdateManager
    {
        return new static(new Repository(new Info($file)), $currentVersion);
    }

    public function hasUpdate() : bool
    {
        return Comparator::greaterThan($this->repo->latestVersion(), $this->currentVersion);
    }
}