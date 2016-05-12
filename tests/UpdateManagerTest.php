<?php

use OFFLINE\UpdateManager\UpdateManager;

class UpdateManagerTest extends BaseTest
{
    protected $manager;
    protected $repo;

    protected function setUp()
    {
        $this->repo    = $this->getRepo();
        $this->manager = new UpdateManager($this->repo, '4.1.0');
    }

    public function testStaticInit()
    {
        $manager = UpdateManager::load(__DIR__ . '/stubs/repo.yaml', '4.0.0');
        $this->assertInstanceOf(UpdateManager::class, $manager);

        $this->assertEquals('4.0.0', $manager->currentVersion);
    }

    public function testChecksForUpdate()
    {
        $manager = new UpdateManager($this->repo, '4.1.0');
        $this->assertEquals(true, $manager->hasUpdate());
        
        $manager = new UpdateManager($this->repo, '4.1.1');
        $this->assertEquals(false, $manager->hasUpdate());

        $manager = new UpdateManager($this->repo, '4.1.1');
        $manager->repo->setStability('beta');
        $this->assertEquals(true, $manager->hasUpdate());

        $manager = new UpdateManager($this->repo, '4.1.2');
        $manager->repo->setStability('beta');
        $this->assertEquals(false, $manager->hasUpdate());
    }
}