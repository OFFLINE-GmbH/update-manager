<?php

namespace OFFLINE\UpdateManager\Updater;


use Guzzle\Http\Client;
use OFFLINE\UpdateManager\Repository\Repository;
use ZipArchive;

class Updater
{
    protected $tempFile;
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

        $this->tempFile = tempnam(sys_get_temp_dir(), 'UM');
        if ( ! is_writable($this->tempFile)) {
            throw new \RuntimeException("{$this->tempFile} is not writeable.");
        }
    }

    public function __destruct()
    {
        if (file_exists($this->tempFile)) {
            @unlink($this->tempFile);
        }
    }

    public function run()
    {
        $this->prepareUpdate($this->updatePath());
        $this->extract();
    }

    /**
     * Extract the zip file into the given directory.
     *
     * @return $this
     */
    protected function extract()
    {
        $archive = new ZipArchive;
        $archive->open($this->tempFile);
        $archive->extractTo($this->targetDirectory);
        $archive->close();

        return $this;
    }

    /**
     * Downloads a remote zip file to the local
     * file system.
     *
     * @param string $path
     *
     * @return Updater
     */
    protected function fetchZip(string $path) : Updater
    {
        $response = (new Client)->get($path);
        file_put_contents($this->tempFile, $response->getBody());

        return $this;
    }

    /**
     * Copies the update to a temporary file.
     *
     * @param $path
     *
     * @throws \RuntimeException
     */
    protected function prepareUpdate($path)
    {
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            $this->fetchZip($path);

            return;
        }

        if ( ! file_exists($path)) {
            throw new \RuntimeException("Update file {$path} does not exist.");
        }

        copy($path, $this->tempFile);
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