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

use littlemo\wechat\core\LWechatException;

use WeChatPay\Crypto\Rsa;

use WeChatPay\Formatter;


/**
 * 基础支付
 *
 * @description
 * @example
 * @author LittleMo 25362583@qq.com
 * @since 2021-09-15
 * @version 2021-09-15
 */
class  Transactions extends \littlemo\wechat\pay\v3\Base
{
    /**
     * JSAPI下单
     * @description https://pay.weixin.qq.com/wiki/doc/apiv3_partner/apis/chapter4_1_1.shtml
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2022-12-27
     * @version 2022-12-27
     * @param array $body           基础参数
     * @param array $amount         订单金额
     * @param array $payer          支付者信息
     * @param array $settle_info    结算信息
     * @param array $detail         优惠功能
     * @param array $scene_info     支付场景描述
     * @return void
     */
    public function jsapi(array $body, array $amount, array $payer, array $settle_info = [], array $detail = [], array $scene_info = [])
    {

        $chain = 'v3/pay/partner/transactions/jsapi';

        $body['sub_mchid'] = $this->subMchid;
        $body['sp_mchid'] = $this->mchid;
        $body['sp_appid'] = $this->appid;
        $body['sub_appid'] = $this->subAppid;
        //订单金额信息
        // [
        //     'total'    => 1, //订单总金额，单位为分。 示例值：100
        //     'currency' => 'CNY' //CNY：人民币，境内商户号仅支持人民币。 示例值：CNY
        // ];

        $body['amount']  = [
            'total' => (int)$amount['total']
        ];

        //支付者信息 下面两个二选一
        // [
        //     'sp_openid' => '', //用户在服务商appid下的唯一标识。 下单前需获取到用户的Openid，Openid获取详见。
        //     'sub_openid' => '', //用户在子商户appid下的唯一标识。若传sub_openid，那sub_appid必填。下单前需获取到用户的Openid，
        // ];
        $body['payer']  = $payer;

        // 结算信息
        if ($settle_info) {
            $body['settle_info']  = $settle_info;
        }

        // 优惠功能
        if ($detail) {
            $body['detail']  = $detail;
        }

        // 支付场景描述
        if ($scene_info) {
            $body['scene_info']  = $scene_info;
        }

        return $this->post($chain, $body);
    }

    /**
     * jsapiSign支付签名
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2022-04-15
     * @version 2022-04-15
     * @param string $prepay_id 预支付交易会话标识
     * @return array
     */
    public function jsapiSign(string $prepay_id): array
    {

        $merchantPrivateKeyFilePath = $this->sslKeyPath;
        $merchantPrivateKeyInstance = Rsa::from($merchantPrivateKeyFilePath);

        $params = [
            'appId'     => $this->subAppid,
            'timeStamp' => (string)Formatter::timestamp(),
            'nonceStr'  => Formatter::nonce(),
            'package'   => 'prepay_id=' . $prepay_id,
        ];
        $params += ['paySign' => Rsa::sign(
            Formatter::joinedByLineFeed(...array_values($params)),
            $merchantPrivateKeyInstance
        ), 'signType' => 'RSA'];
        return $params;
    }

    /**
     * 查询订单API：微信支付订单号查询
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2022-12-28
     * @version 2022-12-28
     * @param string $transaction_id    微信支付系统生成的订单号
     * @return void
     */
    public function getResultById($transaction_id)
    {
        $chain = 'v3/pay/partner/transactions/id/' . $transaction_id;
        $body['sp_mchid'] = $this->mchid;
        $body['sub_mchid'] = $this->subMchid;
        return $this->get($chain, $body);
    }

    /**
     * 查询订单API：商户订单号查询
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2022-12-28
     * @version 2022-12-28
     * @param string $out_trade_no  商户系统内部订单号，只能是数字、大小写字母_-*且在同一个商户号下唯一。
     * @return void
     */
    public function getResultByOutTradeNo($out_trade_no)
    {
        $chain = 'v3/pay/partner/transactions/out-trade-no/{out_trade_no}';
        $body['sp_mchid'] = $this->mchid;
        $body['sub_mchid'] = $this->subMchid;
        return $this->get($chain, $body, compact('out_trade_no'));
    }

    /**
     * 关闭订单API
     * @description 
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2022-12-28
     * @version 2022-12-28
     * @param string $out_trade_no  商户系统内部订单号，只能是数字、大小写字母_-*且在同一个商户号下唯一。
     * @return array
     */
    public function close($out_trade_no)
    {
        $chain = 'v3/pay/partner/transactions/out-trade-no/{out_trade_no}/close';
        $body['sp_mchid'] = $this->mchid;
        $body['sub_mchid'] = $this->subMchid;
        return $this->post($chain, $body, compact('out_trade_no'));
    }

    /**
     * 申请退款API
     * @description https://pay.weixin.qq.com/wiki/doc/apiv3_partner/apis/chapter4_1_10.shtml
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2022-12-28
     * @version 2022-12-28
     * @param array $body           基础参数
     * @param array $amount         订单金额信息
     * @param array $goods_detail   指定商品退款需要传此参数，其他场景无需传递
     * @return array
     */
    public function refunds(array $body, array $amount, array $goods_detail = [])
    {
        $chain = 'v3/refund/domestic/refunds';

        $body['sub_mchid'] = $this->subMchid;

        if (!isset($amount['currency']) || !$amount['currency']) {
            $amount['currency'] = 'CNY';
        }
        $body['amount'] = $amount;

        if ($goods_detail) {
            $body['goods_detail'] = $goods_detail;
        }
        return $this->post($chain, $body);
    }
}
