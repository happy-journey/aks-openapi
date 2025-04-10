<?php

namespace AksOpenapi\AksInitSdk\Api;

use AksOpenapi\AksInitSdk\Helper\DbHelper;
use AksOpenapi\AksInitSdk\Helper\TableFieldHelper;
use AksOpenapi\AksInitSdk\Helper\TableHelper;

/**
 * 表字段相关功能
 */
class InitTableFieldService extends DbHelper
{
    /**
     * 更新表字段
     * @param string $code 标识
     * @return mixed
     */
    public function updateTableField(string $code): mixed
    {
        return TableFieldHelper::updateTableField($code);
    }
}