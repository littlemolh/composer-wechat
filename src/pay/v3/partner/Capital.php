<?php

// +----------------------------------------------------------------------
// | Little Mo - Tool [ WE CAN DO IT JUST TIDY UP IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2023 http://ggui.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: littlemo <25362583@qq.com>
// +----------------------------------------------------------------------

namespace littlemo\wechat\pay\v3\partner;

use littlemo\wechat\pay\v3\Config;

/**
 * Capital
 * @description
 * @example
 * @author LittleMo 25362583@qq.com
 * @since 2025-07-28
 * @version 2025-07-28
 */
class  Capital extends \littlemo\wechat\pay\v3\Base
{

    /**
     * 查询支行列表
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2025-07-28
     * @version 2025-07-28
     * @param string $bank_alias_code   【银行别名编码】银行别名的编码，查询支行接口仅支持需要填写支行的银行别名编码。
     * @param int $city_code            【城市编码】城市编码，唯一标识一座城市，用于结合银行别名编码查询支行列表
     * @param int $limit                【本次请求最大查询条数】非0非负的整数，该次请求可返回的最大资源条数。
     * @param int $offset               【本次查询偏移量】非负整数，表示该次请求资源的起始位置，从0开始计数。调用方选填，默认为0。offset为20，limit为100时，查询第21-120条数据
     * @return array
     */
    public function banksBranches(string $bank_alias_code, int $city_code,  int $offset = 0, int $limit = 100): array
    {

        $chain = '/v3/capital/capitallhh/banks/{bank_alias_code}/branches';
        $query = [
            'city_code' => $city_code,
            'limit' => $limit,
            'offset' => $offset,
        ];

        return $this->get($chain, $query, compact('bank_alias_code'), ['Wechatpay-Serial' => Config::$platformCertificateSerial]);
    }
    /**
     * 获取对私银行卡号开户银行
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2025-07-29
     * @version 2025-07-29
     * @param string $account_number    【银行卡号】1、仅支持对私的个人银行卡号
     * @return array
     */
    public function banksSearchBanksByBankAccount(string $account_number): array
    {
        $chain = '/v3/capital/capitallhh/banks/search-banks-by-bank-account';
        $query = [
            'account_number' => static::encrypt($account_number),
        ];

        return $this->get($chain, $query, [], ['Wechatpay-Serial' => Config::$platformCertificateSerial]);
    }
    /**
     * 查询支持对公业务的银行列表
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2025-07-29
     * @version 2025-07-29
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function banksCorporateBanking($offset = 0, $limit = 100): array
    {
        $chain = '/v3/capital/capitallhh/banks/corporate-banking';
        $query = [
            'offset' => $offset,
            'limit' => $limit,
        ];

        return $this->get($chain, $query, [], ['Wechatpay-Serial' => Config::$platformCertificateSerial]);
    }
    /**
     * 查询支持个人业务的银行列表
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2025-07-29
     * @version 2025-07-29
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function banksPersonalBanking($offset = 0, $limit = 100): array
    {
        $chain = '/v3/capital/capitallhh/banks/personal-banking';
        $query = [
            'offset' => $offset,
            'limit' => $limit,
        ];

        return $this->get($chain, $query, [], ['Wechatpay-Serial' => Config::$platformCertificateSerial]);
    }
    /**
     * 查询省份列表
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2025-07-29
     * @version 2025-07-29
     * @return array
     */
    public function areasProvinces(): array
    {
        $chain = '/v3/capital/capitallhh/areas/provinces';
        return $this->get($chain, [], [], ['Wechatpay-Serial' => Config::$platformCertificateSerial]);
    }
    /**
     * 查询城市列表
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2025-07-29
     * @version 2025-07-29
     * @param int $province_code
     * @return void
     */
    public function areasProvincesCities(int $province_code)
    {
        $chain = '/v3/capital/capitallhh/areas/provinces/{province_code}/cities';
        $path = ['province_code' => $province_code];
        return $this->get($chain, [], $path, ['Wechatpay-Serial' => Config::$platformCertificateSerial]);
    }
}
