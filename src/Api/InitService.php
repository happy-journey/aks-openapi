<?php

namespace AksOpenapi\AksInitSdk\Api;

use AksOpenapi\AksInitSdk\Config\Config;
use AksOpenapi\AksInitSdk\Structure\Table;

/**
 * 初始化前置条件
 * 创建需要的日志表
 */
class InitService extends Config
{
    use Table;

    /** 创建表更新记录日志表
     * @return mixed
     */
    public function createTableLog(): mixed
    {
        return $this->statement(self::createTableLogsStruct());
    }

    /** 表字段更新记录日志表
     * @return mixed
     */
    public function createTableFieldLog(): mixed
    {
        return $this->statement(self::createTableFieldLogsStruct());
    }

    /** 表数据更新记录日志表
     * @return mixed
     */
    public function createTableDataLog(): mixed
    {
        return $this->statement(self::createTableDataLogsStruct());
    }

    /** 表数据关联记录日志表
     * @return mixed
     */
    public function createTableDataRelationLog(): mixed
    {
        return $this->statement(self::createTableDataRelationLogsStruct());
    }
}