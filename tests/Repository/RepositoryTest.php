<?php

class RepositoryTest extends BaseTest
{
    /**
     * @var \OFFLINE\UpdateManager\Repository\Repository
     */
    protected $repo;

    protected function setUp()
    {
        $this->repo = $this->getRepo();
    }

    public function testGetsLatest()
    {
        $latest = $this->repo->latest();

        $this->assertEquals('Latest', reset($latest['changelog']));
    }

    public function testGetsLatestBetaVersion()
    {
        $this->repo->setStability('beta');
        $latest = $this->repo->latestVersion();

        $this->assertEquals('4.1.2', $latest);
    }

    public function testGetsLatestVersion()
    {
        $latest = $this->repo->latestVersion();

        $this->assertEquals('4.1.1', $latest);
    }

    public function testChannelSettings()
    {
        $this->repo->setStability('alpha');

        $this->assertEquals(['stable', 'beta', 'alpha'], $this->repo->validStabilities());

        $this->repo->setStability('stable');

        $this->assertEquals(['stable'], $this->repo->validStabilities());
    }
}