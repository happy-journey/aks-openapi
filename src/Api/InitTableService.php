<?php

namespace AksOpenapi\AksInitSdk\Api;

use AksOpenapi\AksInitSdk\Helper\DbHelper;
use AksOpenapi\AksInitSdk\Helper\TableHelper;

/**
 * 表相关功能
 */
class InitTableService extends DbHelper
{

    /**
     * 初始化表 根据基础表复制所有表
     * @param string $code 标识
     * @return mixed
     */
    public function createTable(string $code): mixed
    {
        return TableHelper::createTable($code);
    }


    /**
     * 更新所有表
     * @param string $code 标识
     * @return mixed
     */
    public function updateTable(string $code): mixed
    {
        return TableHelper::updateTable($code);
    }
}