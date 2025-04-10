<?php

namespace AksOpenapi\AksInitSdk\Helper;

use Exception;

class TableHelper extends CommHelper
{

    // 根据基础表复制所有表
    public static function createTable(string $code, DbHelper $dbHelper): bool
    {
        $errorData = [];
        $error = [
            'tenant_slug' => $code,
            'table' => '',
            'after_table' => '',
            'sql' => '',
            'error' => '',
            'is_ok' => 0,
        ];

        // 查询所有表
        $tables = $dbHelper->getTables();
        if (empty($tables)) {
            $error['error'] = '初始化失败，失败原因：基础表为空';
            $dbHelper->insert(self::getTableLogsTableName(), $error);
            return false;
        }

        foreach ($tables as $item) {
            $table = $item->TABLE_NAME;
            if (
                in_array($table, self::getSystemTables($dbHelper->getPrefix())) ||
                str_contains($table, 'zu')
            ) continue;

            $error['table'] = $table;
            $error['after_table'] = $table . '_' . $code;

            $tableContent = $dbHelper->getTablesContent($table);
            $fun = "Create Table";
            $tableSql = $tableContent[0]->{$fun} ?? '';
            if ($tableSql) {

                $newSql = preg_replace(
                    '/(CREATE\s+TABLE\s+)(`)(' . $table . ')(`)/i',
                    '$1$2' . $table . '_' . $code . '$4',
                    $tableSql
                );
                $error['sql'] = $newSql;

                try {

                    $dbHelper->statement($newSql);
                    $error['is_ok'] = 1;

                } catch (Exception $e) {
                    $error['error'] = '初始化失败，失败原因：' . explode('(SQL:', $e->getMessage())[0] ?? $e->getMessage();
                }

            } else {
                $error['error'] = '初始化失败，失败原因表结构解析失败，解析内容是：' . json_encode($tableContent);
            }

            $errorData[] = $error;
        }

        if (!empty($errorData)) {
            $dbHelper->insert(self::getTableLogsTableName(), $errorData);
        }

        return true;
    }


    // 更新表
    public static function updateTable(string $code, DbHelper $dbHelper): bool
    {
        $errorData = $hasTables = [];
        $error = [
            'tenant_slug' => $code,
            'table' => '',
            'after_table' => '',
            'sql' => '',
            'error' => '',
            'is_ok' => 0,
            'type' => 2,
        ];

        // 查询所有表
        $tables = $dbHelper->getTables();
        if (empty($tables)) {
            $error['error'] = '初始化失败，失败原因：基础表为空';
            $dbHelper->insert(self::getTableLogsTableName(), $error);
            return false;
        }


        // 查询租户下的所有表
        $tenantTables = $dbHelper->getTablesByTenant($code);
        if (empty($tenantTables)) {
            $error['error'] = '初始化失败，失败原因：租户下的所有表为空';
            $dbHelper->insert(self::getTableLogsTableName(), $error);
            return false;
        }
        foreach ($tenantTables as $tItem) {
            $hasTables[] = $tItem->TABLE_NAME;
        }

        foreach ($tables as $item) {
            $table = $item->TABLE_NAME;
            if (
                in_array($table, self::getSystemTables($dbHelper->getPrefix())) ||
                str_contains($table, 'zu') ||
                in_array($table . '_' . $code, $hasTables)
            ) continue;

            $error['table'] = $table;
            $error['after_table'] = $table . '_' . $code;

            $tableContent = $dbHelper->getTablesContent($table);
            $fun = "Create Table";
            $tableSql = $tableContent[0]->{$fun} ?? '';
            if ($tableSql) {

                $newSql = preg_replace(
                    '/(CREATE\s+TABLE\s+)(`)(' . $table . ')(`)/i',
                    '$1$2' . $table . '_' . $code . '$4',
                    $tableSql
                );
                $error['sql'] = $newSql;

                try {

                    $dbHelper->statement($newSql);
                    $error['is_ok'] = 1;

                } catch (Exception $e) {
                    $error['error'] = '表更新失败，失败原因：' . explode('(SQL:', $e->getMessage())[0] ?? $e->getMessage();
                }

            } else {
                $error['error'] = '表更新失败，失败原因表结构解析失败，解析内容是：' . json_encode($tableContent);
            }

            $errorData[] = $error;
        }

        if (!empty($errorData)) {
            $dbHelper->insert(self::getTableLogsTableName(), $errorData);
        }

        return true;
    }


}