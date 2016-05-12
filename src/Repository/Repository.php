<?php

namespace OFFLINE\UpdateManager\Repository;


use Composer\Semver\Semver;

class Repository
{
    /**
     * @var Info
     */
    public $info;
    /**
     * @var string
     */
    private $stability;

    const STABILITIES = ['stable', 'beta', 'alpha', 'dev'];

    public function __construct(Info $info, string $stability = 'stable')
    {
        $this->info      = $info;
        $this->stability = $stability;
    }

    public function setStability(string $stability) : Repository
    {
        $this->stability = $stability;

        return $this;
    }

    /**
     * Returns all valid stabilities for the current
     * stability setting.
     *
     * @return array
     */
    public function validStabilities() : array
    {
        $valid = [];
        foreach ($this::STABILITIES as $stability) {
            $valid[] = $stability;
            if ($stability === $this->stability) {
                break;
            }
        }

        return $valid;
    }

    public function releases() : array
    {
        $valid = $this->validStabilities();

        return array_filter($this->info->information['releases'], function ($item) use ($valid) {
            return in_array($item['stability'], $valid);
        });
    }

    public function latest() : array
    {
        return $this->releases()[$this->latestVersion()] ?? [];
    }

    public function latestVersion() : string
    {
        $releases = array_keys($this->releases());
        $releases = Semver::rsort($releases);

        return reset($releases);
    }
}