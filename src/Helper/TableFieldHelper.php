<?php

namespace AksOpenapi\AksInitSdk\Helper;


class TableFieldHelper extends CommHelper
{


    // 更新表字段
    public function updateTableField(string $code, DbHelper $dbHelper): bool
    {
        $execSql = [];

        // 查询所有表
        $tables = $dbHelper->getTables();
        if (empty($tables)) {
            $error['error'] = '初始化表数据失败，失败原因：基础表为空';
            $dbHelper->insert(self::getTableFieldLogsTableName(), $error);
            return false;
        }

        foreach ($tables as $item) {
            $table = $item->TABLE_NAME;
            if (
                in_array($table, self::getSystemTables($dbHelper->getPrefix())) ||
                str_contains($table, 'zu')
            ) continue;

            $afterTable = $table . '_' . $code;

            // 查询原表字段
            $tableFieldsArr = [];
            $tableFields = $dbHelper->getTablesFieldsByTable($table);
            foreach ($tableFields as $fItem) {
                $tableFieldsArr[] = (array)$fItem;
            }

            // 查询现表字段
            $tableFieldsZuhuArr = [];
            $tableFieldsZuhu = $dbHelper->getTablesFieldsByTable($afterTable);
            foreach ($tableFieldsZuhu as $fItemzh) {
                $tableFieldsZuhuArr[] = (array)$fItemzh;
            }

            // 新增原表新增的字段
            $addFields = array_diff_key(
                array_column($tableFieldsArr, null, 'COLUMN_NAME'),
                array_column($tableFieldsZuhuArr, null, 'COLUMN_NAME')
            );
            if (!empty($addFields)) {
                foreach ($addFields as $rs) {
                    $execSql[] = [
                        'table' => $table,
                        'after_table' => $afterTable,
                        'field_name' => $rs['COLUMN_NAME'],
                        'sql' => self::getAddFieldSql($afterTable,$rs),
                        'type' => 1
                    ];
                }
            }

            // 更新原表的字段
            if (!empty(array_column($tableFieldsArr, null, 'COLUMN_NAME'))) {
                foreach (array_column($tableFieldsArr, null, 'COLUMN_NAME') as $field => $value) {
                    if (isset(array_column($tableFieldsZuhuArr, null, 'COLUMN_NAME')[$field])) {
                        $execSql[] = [
                            'table' => $table,
                            'after_table' => $afterTable,
                            'field_name' => $value['COLUMN_NAME'],
                            'sql' => self::getModifyFieldSql($afterTable,$value),
                            'type' => 2
                        ];
                    }
                }
            }
        }

        if (!empty($execSql)) {
            foreach ($execSql as $sqlitem) {
                $error = [
                    'tenant_slug' => $code,
                    'table' => $sqlitem['table'],
                    'after_table' => $sqlitem['after_table'],
                    'field_name' => $sqlitem['field_name'],
                    'sql' => $sqlitem['sql'],
                    'error' => '',
                    'is_ok' => 0,
                    'type' => $sqlitem['type']
                ];
                try {

                    $dbHelper->statement($sqlitem['sql']);
                    $error['is_ok'] = 1;

                } catch (\Exception $e) {
                    $error['error'] = '更新表字段失败，失败原因是：' . json_encode($e->getMessage());
                }
                $dbHelper->insert(self::getTableFieldLogsTableName(), $error);
            }
        }

        return true;
    }

}