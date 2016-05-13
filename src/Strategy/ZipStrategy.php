<?php

namespace OFFLINE\UpdateManager\Strategy;


use Guzzle\Http\Client;
use ZipArchive;

class ZipStrategy extends UpdateStrategy
{

    protected $tempFile;

    public function update()
    {
        $this->extract();
    }

    public function rollback()
    {
        // TODO: Implement rollback() method.
    }

    public function prepare()
    {
        $this->tempFile = tempnam(sys_get_temp_dir(), 'UM');
        if ( ! is_writable($this->tempFile)) {
            throw new \RuntimeException("{$this->tempFile} is not writeable.");
        }

        if (filter_var($this->updatePath, FILTER_VALIDATE_URL)) {
            $this->fetchZip();

            return;
        }

        if ( ! file_exists($this->updatePath)) {
            throw new \RuntimeException("Update file {$this->updatePath} does not exist.");
        }

        copy($this->updatePath, $this->tempFile);
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
     */
    protected function fetchZip()
    {
        $response = (new Client)->get($this->updatePath);
        file_put_contents($this->tempFile, $response->getBody());
    }


    public function cleanup()
    {
        if (file_exists($this->tempFile)) {
            @unlink($this->tempFile);
        }
    }
}