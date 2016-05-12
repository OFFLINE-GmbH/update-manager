<?php

namespace OFFLINE\UpdateManager\Repository;


use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;

class Info
{
    public $information;

    public function __construct($file, $parser = null)
    {
        if ( ! file_exists($file)) {
            throw new \InvalidArgumentException($file . ' does not exist.');
        }

        if ($parser === null) {
            $parser = new Parser();
        }

        try {
            $this->information = $parser->parse(file_get_contents($file));
        } catch (ParseException $e) {
            throw new \RuntimeException(sprintf('Unable to parse the YAML string: %s', $e->getMessage()));
        }
    }
}