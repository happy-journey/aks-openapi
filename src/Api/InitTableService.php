<?php

namespace AksOpenapi\AksInitSdk\Api;

use AksOpenapi\AksInitSdk\Strcut\Table;

/**
 * 初始化表
 */
class InitTableService extends Table
{

    /** 查询店铺信息
     * @param $shop_id 店铺Id
     * @return mixed
     */
    public function get_shop($shop_id)
    {
        return $this->client->call("eleme.shop.getShop", array("shopId" => $shop_id));
    }
}