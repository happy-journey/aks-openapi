<?php

namespace AksOpenapi\AksInitSdk\Helper;

use InvalidArgumentException;
use support\Db;
use Exception;

class DbHelper
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

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    // 执行SQL
    public function statement(string $sql = ''): bool
    {
        try {
            return Db::statement($sql);
        } catch (Exception $e) {
            throw new Exception('操作失败，失败原因：' . explode('(SQL:', $e->getMessage())[0] ?? $e->getMessage());
        }
    }

    // 获取当前连接数据库所有表
    public function getTables(): array
    {
        return Db::select("SELECT * FROM information_schema.TABLES WHERE TABLE_SCHEMA='{$this->database}';");
    }

    // 获取表字段
    public function getTablesFieldsByTable(string $table = ''): array
    {
        return Db::select("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='{$this->database}' AND TABLE_NAME='{$table}'");
    }

    // 获取当前连接数据库跟code相关的所有表
    public function getTablesByTenant(string $code = ''): array
    {
        return Db::select("SELECT * FROM information_schema.TABLES WHERE TABLE_SCHEMA='{$this->database}' and TABLE_NAME like '%{$code}%'");
    }

    // 获取表结构信息
    public function getTablesContent(string $table = ''): array
    {
        return Db::select("SHOW CREATE TABLE `{$this->database}`.`{$table}`;");
    }

    // 获取表主键
    public function getTablePk(string $table = ''): string
    {
        return Db::select("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='{$this->database}' AND TABLE_NAME='{$table}' AND COLUMN_KEY='PRI'")[0]->COLUMN_NAME ?? '';
    }

    // 插入
    public function insert(string $table, array $data): bool
    {
        return Db::table($table)->insert($data);
    }

    // 插入并返回ID
    public function insertGetId(string $table, array $data): bool
    {
        return Db::table($table)->insertGetId($data);
    }

    // 列表
    public function get(string $table, array $where = [])
    {
        return Db::table($table)->where($where)->get();
    }

    // 更新
    public function update(string $table, array $data, array $where): bool
    {
        return Db::table($table)->where($where)->update($data);
    }

    // 单个
    public function first(string $table, array $where = [], $order = 'id', $desc = 'desc')
    {
        return Db::table($table)->where($where)->orderBy($order, $desc)->first();
    }
}