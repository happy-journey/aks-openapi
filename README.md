# 接入指南 
1. PHP version >= 8.0
2. 通过composer安装SDK 
3. 创建Config配置类，填入 schema(默认数据库)，prefix(数据库前缀) 和 database(默认数据库) 参数
4. 使用sdk提供的接口进行开发调试


### 安装

#### 安装命令
```php
composer require aks-openapi/aks-init-sdk
```

### 基本用法

```php
    use AksOpenapi\AksInitSdk\Api\InitService;
    
    //实例化一个服务对象
    $initService = new InitService($schema, $prefix, $database);
    
    //调用服务方法
    $initService->createTableLog();

```

### SDK使用步骤

1. 必须先创建所需的日志表，否则会影响后期的同步执行
```php
    use AksOpenapi\AksInitSdk\Api\InitService;
    
    //实例化一个服务对象
    $initService = new InitService($schema, $prefix, $database);
    
    //创建表更新记录日志表
    $initService->createTableLog();
    
    //创建表数据更新记录日志表
    $initService->createTableFieldLog();
    
    //创建表字段更新记录日志表
    $initService->createTableDataLog();
    
    //创建表数据关联记录日志表
    $initService->createTableDataRelationLog();
```

2.  初始化
```php
    use AksOpenapi\AksInitSdk\Api\InitTableService;
    use AksOpenapi\AksInitSdk\Api\InitTableDataService;
    
    $code = '标识';
    
    //创建初始化表
    $initTableService = new InitTableService($schema, $prefix, $database);
    $initTableService->createTable($code);
    
    //创建初始化表数据
    $initTableDataService = new InitTableDataService($schema, $prefix, $database);
    $initTableDataService->createTableData($code);
```

3.  更新
```php
    use AksOpenapi\AksInitSdk\Api\InitTableService;
    use AksOpenapi\AksInitSdk\Api\InitTableDataService;
    use AksOpenapi\AksInitSdk\Api\InitTableFieldService;
    
    $code = '标识';
    
    //更新表
    $initTableService = new InitTableService($schema, $prefix, $database);
    $initTableService->updateTable($code);
    
    //更新表数据
    $initTableDataService = new InitTableDataService($schema, $prefix, $database);
    $initTableDataService->updateTableData($code);
    
    //更新表字段
    $initTableFieldService = new InitTableFieldService($schema, $prefix, $database);
    $initTableFieldService->updateTableField($code);
```


## CHANGELOG:
### [v1.0.0]
    Release Date : 2025-04-10
- [Feature] SDK完整实现
- [Feature] 创建表更新记录日志表
- [Feature] 创建表数据更新记录日志表
- [Feature] 创建表字段更新记录日志表
- [Feature] 创建表数据关联记录日志表
- [Feature] 创建初始化表
- [Feature] 创建初始化表数据
- [Feature] 更新表
- [Feature] 更新表数据
- [Feature] 更新表字段
