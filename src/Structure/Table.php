<?php

namespace AksOpenapi\AksInitSdk\Structure;

/**
 * 生成表结构SQL
 */
trait Table
{
    public static function getTableLogsTableName(): string
    {
        return 'init_table_logs';
    }

    protected function createTableLogsStruct(): string
    {
        $tableName = $this->prefix . $this->getTableLogsTableName();
        $sql = <<<SQL
            CREATE TABLE IF NOT EXISTS `{$tableName}` (
              `id` int NOT NULL AUTO_INCREMENT COMMENT '日志id',
              `table` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '' COMMENT '初始化表',
              `after_table` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT '初始化后表名',
              `type` tinyint(1) DEFAULT '1' COMMENT '类型 1初始化 2更新',
              `is_ok` tinyint(1) DEFAULT '0' COMMENT '是否完成 1完成',
              `sql` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci COMMENT '执行的sql',
              `error` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci COMMENT '错误信息',
              `tenant_slug`  varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '' COMMENT '租户标识',
              `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
              `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
              `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
               PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC COMMENT='表更新记录日志表';
        SQL;
        return trim($sql);
    }

    public static function getTableFieldLogsTableName(): string
    {
        return 'init_tablefield_logs';
    }

    protected function createTableFieldLogsStruct(): string
    {
        $tableName = $this->prefix . $this->getTableFieldLogsTableName();
        $sql = <<<SQL
            CREATE TABLE IF NOT EXISTS `{$tableName}` (
              `id` int NOT NULL AUTO_INCREMENT COMMENT '日志id',
              `table` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '' COMMENT '初始化表',
              `after_table` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT '初始化后表名',
              `field_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '' COMMENT '字段名',
              `type` tinyint(1) DEFAULT '1' COMMENT '类型 1新增 2更新',
              `is_ok` tinyint(1) DEFAULT '0' COMMENT '是否完成 1完成',
              `sql` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci COMMENT '执行的sql',
              `error` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci COMMENT '错误信息',
              `tenant_slug`  varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '' COMMENT '租户标识',
              `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
              `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
              `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC COMMENT='表字段更新记录日志表';
        SQL;
        return trim($sql);
    }

    public static function getTableDataLogsTableName(): string
    {
        return 'init_tabledata_logs';
    }

    protected function createTableDataLogsStruct(): string
    {
        $tableName = $this->prefix . $this->getTableDataLogsTableName();
        $sql = <<<SQL
            CREATE TABLE IF NOT EXISTS `{$tableName}` (
              `id` int NOT NULL AUTO_INCREMENT COMMENT '日志id',
              `table` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '' COMMENT '初始化表',
              `after_table` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT '初始化后表名',
              `type` tinyint(1) DEFAULT '1' COMMENT '类型 1初始化 2更新',
              `is_ok` tinyint(1) DEFAULT '0' COMMENT '是否完成 1完成',
              `data` json DEFAULT NULL COMMENT '插入的所有数据，不一定全部同步，具体看relation表看具体数据',
              `error` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci COMMENT '错误信息',
              `tenant_slug`  varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '' COMMENT '租户标识',
              `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
              `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
              `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC COMMENT='表数据更新记录日志表';
        SQL;
        return trim($sql);
    }

    public static function getTableDataRelationLogsTableName(): string
    {
        return 'init_tabledata_relation_logs';
    }

    protected function createTableDataRelationLogsStruct(): string
    {
        $tableName = $this->prefix . $this->getTableDataRelationLogsTableName();
        $sql = <<<SQL
            CREATE TABLE IF NOT EXISTS `{$tableName}` (
              `id` int NOT NULL AUTO_INCREMENT COMMENT '日志id',
              `table` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '' COMMENT '初始化表',
              `after_table` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT '初始化后表名',
              `type` tinyint(1) DEFAULT '1' COMMENT '类型 1初始化 2更新',
              `data` json DEFAULT NULL COMMENT '插入前的数据-对应基础表数据',
              `before_data` json DEFAULT NULL COMMENT '当前表插入前的数据',
              `after_data` json DEFAULT NULL COMMENT '当前表插入后的数据',
              `pk` varchar(20) DEFAULT NULL COMMENT '当前表主键',
              `tid` int DEFAULT NULL COMMENT '对应table表主键',
              `rid` int DEFAULT NULL COMMENT '对应after_table表主键',
              `is_ok` tinyint(1) DEFAULT '0' COMMENT '是否完成 1完成',
              `error` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci COMMENT '错误信息',
              `tenant_slug`  varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '' COMMENT '租户标识',
              `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
              `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
              `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC COMMENT='表数据关联记录日志表';
        SQL;
        return trim($sql);
    }

    protected function getAddFieldSql($table = '', $data = [])
    {
        $nullable = ($data['IS_NULLABLE'] == 'NO') ? 'NOT NULL' : 'NULL';
        $strOrInt = (is_numeric($data['COLUMN_DEFAULT']) || $data['DATA_TYPE'] == 'datetime') ? $data['COLUMN_DEFAULT'] : "\"{$data['COLUMN_DEFAULT']}\"";
        $defaultValue = empty($data['COLUMN_DEFAULT']) ? '' : "DEFAULT {$strOrInt}";
        $charset = $data['CHARACTER_SET_NAME'] ? "CHARACTER SET {$data['CHARACTER_SET_NAME']}" : '';
        $collate = $data['COLLATION_NAME'] ? "COLLATE {$data['COLLATION_NAME']}" : '';

        $sql = <<<SQL
            ALTER TABLE {$table} ADD COLUMN `{$data['COLUMN_NAME']}` {$data['COLUMN_TYPE']} {$charset} {$collate} {$nullable} {$defaultValue} COMMENT '{$data['COLUMN_COMMENT']}';
        SQL;
        return trim($sql);
    }


    protected function getModifyFieldSql($table = '', $data = [])
    {
        $nullable = ($data['IS_NULLABLE'] == 'NO') ? 'NOT NULL' : 'NULL';
        $strOrInt = (is_numeric($data['COLUMN_DEFAULT']) || $data['DATA_TYPE'] == 'datetime') ? $data['COLUMN_DEFAULT'] : "\"{$data['COLUMN_DEFAULT']}\"";
        $defaultValue = empty($data['COLUMN_DEFAULT']) ? '' : "DEFAULT {$strOrInt}";
        $charset = $data['CHARACTER_SET_NAME'] ? "CHARACTER SET {$data['CHARACTER_SET_NAME']}" : '';
        $collate = $data['COLLATION_NAME'] ? "COLLATE {$data['COLLATION_NAME']}" : '';

        $sql = <<<SQL
            ALTER TABLE {$table} MODIFY COLUMN `{$data['COLUMN_NAME']}` {$data['COLUMN_TYPE']} {$charset} {$collate} {$nullable} {$defaultValue} COMMENT '{$data['COLUMN_COMMENT']}';
        SQL;
        return trim($sql);
    }
}