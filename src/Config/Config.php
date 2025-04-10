<?php

namespace AksOpenapi\AksInitSdk\Config;

use InvalidArgumentException;
use Exception;
use support\Db;

class Config
{
    protected $schema;
    protected $prefix;
    protected $database;
    protected $db;

    public function __construct($schema, $prefix, $database)
    {
        $this->schema = $schema;
        $this->prefix = $prefix;
        $this->database = $database;
        if (!$this->schema || !$this->prefix || !$this->database) {
            throw new InvalidArgumentException("params is invalid");
        }
        $this->db = new Db;
    }

    protected function statement(string $sql = ''): bool
    {
        try {
           return $this->db->statement($sql);
        }catch (Exception $e){
            throw new Exception('操作失败，失败原因：' . explode('(SQL:', $e->getMessage())[0] ?? $e->getMessage());
        }
    }
}