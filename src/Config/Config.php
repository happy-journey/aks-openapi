<?php

namespace AksOpenapi\AksInitSdk\Config;

use InvalidArgumentException;

class Config
{
    private $schema;
    private $prefix;
    private $database;

    public function __construct(string $schema, string $prefix, string $database)
    {
        $this->schema = $schema;
        $this->prefix = $prefix;
        $this->database = $database;
        if (!$this->schema || !$this->prefix || !$this->database) {
            throw new InvalidArgumentException("params is invalid");
        }
    }

    public function getSchema()
    {
        return $this->schema;
    }

    public function getPrefix()
    {
        return $this->prefix;
    }

    public function getDatabase()
    {
        return $this->database;
    }
}