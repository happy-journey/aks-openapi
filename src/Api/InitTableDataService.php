<?php

namespace AksOpenapi\AksInitSdk\Api;

use AksOpenapi\AksInitSdk\Helper\DbHelper;
use AksOpenapi\AksInitSdk\Helper\TableDataHelper;

/**
 * 表数据相关功能
 */
class InitTableDataService extends DbHelper
{

    /**
     * 初始化表 复制所有基础表数据
     * @param string $code 标识
     * @return mixed
     */
    public function createTableData(string $code): mixed
    {
        return TableDataHelper::createTableData($code);
    }

    /**
     * 更新表数据
     * @param string $code 标识
     * @return mixed
     */
    public function updateTableData(string $code): mixed
    {
        return TableDataHelper::updateTableData($code);
    }
}