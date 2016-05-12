<?php

use OFFLINE\UpdateManager\Repository\Info;

class RepositoryInfoTest extends BaseTest
{

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testThrowNotExistingException()
    {
        new Info('not-existing.yaml');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testInvalidYamlFile()
    {
        new Info(__DIR__ . '/../stubs/invalid.yaml');
    }

    public function testReadRepoFile()
    {
        $info = $this->getInfo();
        $this->assertEquals('Project Title', $info->information['project']);
    }
}