<?php
// +----------------------------------------------------------------------
// | Little Mo - Tool [ WE CAN DO IT JUST TIDY UP IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2021 http://ggui.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: littlemo <25362583@qq.com>
// +----------------------------------------------------------------------

namespace littlemo\wechat\pay;

use littlemo\utils\Common;
use littlemo\utils\HttpClient;

/**
 * 微信付款到到零钱
 * 
 * @description 用于向微信用户个人付款 目前支持向指定微信用户的openid付款。
 * @example
 * @author LittleMo 25362583@qq.com
 * @since 2021-09-15
 * @version 2021-09-15
 */
class Transfers extends PayBase
{


    /**
     * 校验用户姓名选项	
     * NO_CHECK：不校验真实姓名 
     * FORCE_CHECK：强校验真实姓名
     */
    protected $checkName = 'NO_CHECK';

    /**
     * 设置 校验用户姓名选项
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-09-15
     * @version 2021-09-15
     * @param boolean $status
     */
    public function setCheckName($status = false)
    {
        if ($status === true) {
            $this->checkName = 'FORCE_CHECK';
        } else {
            $this->checkName = 'NO_CHECK';
        }
    }

    /**
     * 创建付款订单
     * 
     * 官方文档 https://pay.weixin.qq.com/wiki/doc/api/tools/mch_pay.php?chapter=14_2
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-09-15
     * @version 2021-09-15
     * @param string $openid    用户openid
     * @param string $money     付款金额，单位：元
     * @param string $orderNo   订单号
     * @param string $desc      说明、描述
     * @param string $userName  用户真实姓名
     * @return void
     */
    public function create($openid,  $money, &$orderNo, $desc = '提现', $userName = '')
    {
        $orderNo = $orderNo ?: date("YmdHis") . rand(10000, 99999);

        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';

        //整理请求参数
        $params = [
            'mch_appid' => $this->appid, //商户账号appid
            'mchid' => $this->mchid, //商户号
            'nonce_str' => Common::createNonceStr(), //随机字符串
            'partner_trade_no' => $orderNo, //商户订单号	
            'openid' => $openid, //用户openid	
            'check_name' => $this->checkName, //校验用户姓名选项
            're_user_name' => $userName, //收款用户姓名	
            'amount' => bcmul($money, 100), //金额
            'desc' => $desc, //付款备注
        ];

        $params['sign'] = Common::createSign($params, ['key' => $this->key]); //签名

        $result = (new HttpClient())->post($url, HttpClient::array_to_xml($params), [], [], [
            'cert_type' => 'PEM',
            'cert' => $this->sslCertPath,
            'key_type' => 'PEM',
            'key' => $this->sslKeyPath,
        ]);

        return $this->init_result($result);
    }

    /**
     * 查询付款结果
     * 
     * 官方文档 https://pay.weixin.qq.com/wiki/doc/api/tools/mch_pay.php?chapter=14_3
     * 
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-09-15
     * @version 2021-09-15
     * @param [type] $orderNo
     * @return void
     */
    public function get($orderNo)
    {
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/gettransferinfo';

        //整理请求参数
        $params = [
            'appid' => $this->appid, //商户账号appid
            'mch_id' => $this->mchid, //商户号
            'partner_trade_no' => $orderNo, //商户订单号	
            'nonce_str' => Common::createNonceStr(), //随机字符串
        ];
        $params['sign'] = Common::createSign($params, ['key' => $this->key]); //签名

        $result = (new HttpClient())->post($url, HttpClient::array_to_xml($params), [], [], [
            'cert_type' => 'PEM',
            'cert' => $this->sslCertPath,
            'key_type' => 'PEM',
            'key' => $this->sslKeyPath,
        ]);

        return $this->init_result($result);
    }
}
