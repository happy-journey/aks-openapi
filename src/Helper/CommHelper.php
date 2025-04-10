<?php

namespace AksOpenapi\AksInitSdk\Helper;

use AksOpenapi\AksInitSdk\Structure\Table;

class CommHelper
{
    use Table;

    public static function getSystemTables(string $prefix) :array
    {
        return [
            "{$prefix}init_tabledata_logs",
            "{$prefix}init_tablefield_logs",
            "{$prefix}init_tabledata_relation_logs",
            "{$prefix}init_table_logs"
        ];
    }
}