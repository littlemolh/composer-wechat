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
use littlemo\wechat\pay\v3\Config;
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
    // 支付基础参数
    private $body = [];
    // 支付订单金额
    private $amount = [];
    // 支付支付者信息
    private $payer = '';
    // 支付结算信息
    private $settle_info = '';
    // 支付优惠功能
    private $detail = '';
    // 支付支付场景描述
    private $scene_info = '';



    /**
     * 支付基础信息
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-09-01
     * @version 2023-09-01
     * @param array $body
     * @return Transactions
     */
    public function body(array $body): Transactions
    {
        $this->body = $body;
        return $this;
    }
    /**
     * 结算信息
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-09-01
     * @version 2023-09-01
     * @param array $settle_info
     * @return Transactions
     */
    public function settleInfo(array $settle_info = []): Transactions
    {
        $this->settle_info = $settle_info;
        return $this;
    }
    /**
     * 订单金额
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-09-01
     * @version 2023-09-01
     * @param array $amount
     * @return Transactions
     */
    public function amount(array $amount): Transactions
    {
        $this->amount = [
            'total' => (int)$amount['total'], //订单总金额，单位为分。 示例值：100
            'currency' => 'CNY', //CNY：人民币，境内商户号仅支持人民币。 示例值：CNY
        ];
        return $this;
    }

    public function payer(string $type, string $openid): Transactions
    {
        //支付者信息 下面两个二选一
        // [
        //     'sp_openid' => '', //用户在服务商appid下的唯一标识。 下单前需获取到用户的Openid，Openid获取详见。
        //     'sub_openid' => '', //用户在子商户appid下的唯一标识。若传sub_openid，那sub_appid必填。下单前需获取到用户的Openid，
        // ];
        if (!in_array($type, ['sp_openid', 'sub_openid'])) {
            throw new LWechatException('支付者信息类型不正确，可选项[sp_openid, sub_openid]');
        }
        $this->payer = [
            $type => $openid
        ];
        return $this;
    }
    /**
     * 优惠功能
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-09-01
     * @version 2023-09-01
     * @param array $detail
     * @return Transactions
     */
    public function detail(array $detail = []): Transactions
    {
        $this->detail = $detail;
        return $this;
    }
    /**
     * 场景信息
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-09-01
     * @version 2023-09-01
     * @param array $scene_info
     * @return Transactions
     */
    public function sceneInfo(array $scene_info = []): Transactions
    {
        $this->scene_info = $scene_info;
        return $this;
    }
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
    public function jsapi()
    {

        $chain = 'v3/pay/partner/transactions/jsapi';
        $body = $this->body;

        $body['sub_mchid'] = Config::$subMchid;
        $body['sp_mchid'] = Config::$mchid;
        $body['sp_appid'] = Config::$appid;
        $body['sub_appid'] = Config::$subAppid;

        $body['amount']  = $this->amount;
        $body['payer']  = $this->payer;
        // 结算信息
        if ($this->settle_info) $body['settle_info']  = $this->settle_info;
        // 优惠功能
        if ($this->detail)  $body['detail']  = $this->detail;
        // 支付场景描述
        if ($this->scene_info)  $body['scene_info']  = $this->scene_info;

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

        $params = [
            'appId'     => $this->subAppid,
            'timeStamp' => (string)Formatter::timestamp(),
            'nonceStr'  => Formatter::nonce(),
            'package'   => 'prepay_id=' . $prepay_id,
        ];
        $params += ['paySign' => Rsa::sign(
            Formatter::joinedByLineFeed(...array_values($params)),
            static::$privateKeyInstance
        ), 'signType' => 'RSA'];
        return $params;
    }

    /**
     * Native下单API
     * @description https://pay.weixin.qq.com/wiki/doc/apiv3_partner/apis/chapter4_4_1.shtml
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-09-01
     * @version 2023-09-01
     * @return array
     */
    public function native(): array
    {
        $chain = 'v3/pay/partner/transactions/native';
        $body = $this->body;
        $body['sub_mchid'] = Config::$subMchid;
        $body['sp_mchid'] = Config::$mchid;
        $body['sp_appid'] = Config::$appid;
        $body['sub_appid'] = Config::$subAppid;
        $body['amount']  = $this->amount;
        // 结算信息
        if ($this->settle_info) $body['settle_info']  = $this->settle_info;
        // 优惠功能
        if ($this->detail)  $body['detail']  = $this->detail;
        // 支付场景描述
        if ($this->scene_info)  $body['scene_info']  = $this->scene_info;

        return $this->post($chain, $body);
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
