<?php

namespace AksOpenapi\AksInitSdk\Helper;

class TableDataHelper extends CommHelper
{

    // 初始化表数据
    public static function createTableData(string $code, DbHelper $dbHelper): bool
    {

        $prefix = $dbHelper->getPrefix();
        $errorData = [];
        $error = [
            'tenant_slug' => $code,
            'table' => '',
            'after_table' => '',
            'sql' => '',
            'error' => '',
            'is_ok' => 0,
            'data' =>'[]',
            'type' =>1
        ];

        // 查询所有表
        $tables = $dbHelper->getTables();
        if (empty($tables)) {
            $error['error'] = '初始化表数据失败，失败原因：基础表为空';
            $dbHelper->insert(self::getTableLogsTableName(), $error);
            return false;
        }

        foreach ($tables as $item) {
            $table = $item->TABLE_NAME;
            if (
                in_array($table, self::getSystemTables($dbHelper->getPrefix())) ||
                str_contains($table, 'zu')
            ) continue;

            $afterTable = $table . '_' . $code;
            $error['table'] = $table;
            $error['after_table'] = $afterTable;

            $pk = $dbHelper->getTablePk($afterTable);

            try {

                $table = explode($prefix, $table)[1] ?? '';
                $insertData = [];
                $selectData = $dbHelper->get($table);
                if (!empty($selectData)) {
                    foreach ($selectData as $ditem) {
                        $insertData[] = (array)$ditem;
                    }
                }

                if (!empty($insertData)) {
                    foreach ($insertData as $v) {
                        $data = $v;
                        $rid = $dbHelper->insertGetId($afterTable, $v);
                        $dbHelper->insert(self::getTableDataRelationLogsTableName(), [
                            'tenant_slug' => $code,
                            'table' => $table,
                            'after_table' => $afterTable,
                            'type' => 1,
                            'data' => json_encode($data),
                            'after_data' => json_encode($v),
                            'before_data' => '[]',
                            'pk' => $pk,
                            'is_ok' => 1,
                            'tid' => $v[$pk] ?? 0,
                            'rid' => $rid,
                        ]);
                    }
                }
                $error['is_ok'] = 1;
                $error['data'] = json_encode($insertData);

            } catch (\Exception $e) {
                $error['error'] = '初始化表数据失败，失败原因是：' . $e->getMessage();
            }

            $errorData[] = $error;
        }

        if (!empty($errorData)) {
            $dbHelper->insert(self::getTableDataLogsTableName(), $error);
        }

        return true;
    }

    // 更新表数据
    public static function updateTableData(string $code, DbHelper $dbHelper): bool
    {

        $prefix = $dbHelper->getPrefix();
        $errorData = [];
        $error = [
            'tenant_slug' => $code,
            'table' => '',
            'after_table' => '',
            'sql' => '',
            'error' => '',
            'is_ok' => 0,
            'data' =>'[]',
            'type' =>2
        ];

        // 查询所有表
        $tables = $dbHelper->getTables();
        if (empty($tables)) {
            $error['error'] = '更新表数据失败，失败原因：基础表为空';
            $dbHelper->insert(self::getTableDataLogsTableName(), $error);
            return false;
        }

        foreach ($tables as $num => $item) {
            $table = $item->TABLE_NAME;
            if (
                in_array($table, self::getSystemTables($dbHelper->getPrefix())) ||
                str_contains($table, 'zu')
            ) continue;

            $afterTable = $table . '_' . $code;
            $error['table'] = $table;
            $error['after_table'] = $afterTable;

            $pk = $dbHelper->getTablePk($afterTable);
            if(empty($pk)){
                $error['error'] = '更新表数据失败，失败原因是：当前表没有主键，无法更新';
                $errorData[] = $error;
                continue;
            }

            try {

                $table = explode($prefix, $table)[1] ?? '';
                $insertData = [];
                $selectData = $dbHelper->get($table);
                if (!empty($selectData)) {
                    foreach ($selectData as $ditem) {
                        $insertData[] = (array)$ditem;
                    }
                }


                if(!empty($insertData)){
                    foreach ($insertData as $v) {
                        $data = $v;
                        $relationInfo = $dbHelper->first(self::getTableDataRelationLogsTableName(),[
                            'table'=>$table,
                            'after_table'=>$afterTable,
                            'tid'=>$v[$pk],
                            'is_ok'=>1
                        ]);
                        if(empty($relationInfo)){
                            $rid = $dbHelper->insertGetId($afterTable,$v);
                            $dbHelper->insert(self::getTableDataRelationLogsTableName(), [
                                'tenant_slug' => $code,
                                'table' => $table,
                                'after_table' => $afterTable,
                                'type' => 2,
                                'data' => json_encode($data),
                                'after_data' => json_encode($v),
                                'before_data' => '[]',
                                'pk' => $pk,
                                'rid' => $rid,
                                'tid' => $data[$pk] ?? 0,
                                'is_ok' => 1
                            ]);
                        }else{

                            $info = $dbHelper->first($afterTable,[$pk=>$relationInfo->rid]);
                            if(!empty($info)){
                                // 判断更新时间是否发生改变，如果改变则说不改条数据被更改，被更改的数据不更新
                                // 此处必须以$relationInfo->data->updated_at的更新时间对比，因为基础表和原表都会更改，更新时间会变
                                $updateAt = json_decode($relationInfo->data,true)['updated_at'] ?? 0;
                                if($v['updated_at'] && $updateAt && $updateAt == $info->updated_at){
                                    unset($v[$pk]);
                                    $dbHelper->update($afterTable,$v,[$pk=>$relationInfo->rid]);
                                    $dbHelper->insert(self::getTableDataRelationLogsTableName(), [
                                        'tenant_slug' => $code,
                                        'table' => $table,
                                        'after_table' => $afterTable,
                                        'type' => 2,
                                        'data' => json_encode($data),
                                        'after_data' => json_encode($v),
                                        'before_data' => json_encode((array)$info),
                                        'pk' => $pk,
                                        'tid' => $data[$pk] ?? 0,
                                        'rid' => $relationInfo->rid,
                                        'is_ok' => 1
                                    ]);

                                }else{
                                    $dbHelper->insert(self::getTableDataRelationLogsTableName(), [
                                        'tenant_slug' => $code,
                                        'table' => $table,
                                        'after_table' => $afterTable,
                                        'type' => 2,
                                        'data' => json_encode($v),
                                        'after_data' => '[]',
                                        'before_data' => '[]',
                                        'pk' => $pk,
                                        'tid' => $data[$pk] ?? 0,
                                        'rid' => $relationInfo->rid,
                                        'is_ok' => 0,
                                        'error' => '更新表数据失败，失败原因是:数据异常，没有updated_at字段或者该字段已被当前站点修改'
                                    ]);
                                }

                            }else{
                                $dbHelper->insert(self::getTableDataRelationLogsTableName(), [
                                    'tenant_slug' => $code,
                                    'table' => $table,
                                    'after_table' => $afterTable,
                                    'type' => 2,
                                    'data' => json_encode($v),
                                    'after_data' => '[]',
                                    'before_data' => '[]',
                                    'pk' => $data[$pk] ?? 0,
                                    'tid' => $data[$pk] ?? 0,
                                    'rid' => $relationInfo->rid,
                                    'is_ok' => 0,
                                    'error' => '更新表数据失败，失败原因是:数据已被删除'
                                ]);
                            }
                        }
                    }
                }
                $error['is_ok'] = 1;
                $error['data'] = json_encode($insertData);

            }catch (\Exception $e){
                $error['error'] = '更新表数据失败，失败原因是：' . $e->getMessage();
            }

            $errorData[] = $error;
        }

        if(!empty($errorData)){
            $dbHelper->insert(self::getTableDataLogsTableName(), $error);
        }

        return true;
    }

}