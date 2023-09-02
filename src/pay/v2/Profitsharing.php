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

namespace littlemo\wechat\pay\v2;

use littlemo\utils\HttpClient;
use littlemo\utils\Tools;

/**
 * 微信分帐
 * 
 * @example
 * @since 2021-11-25
 * @version 2021-11-25
 */
class Profitsharing extends Base
{


    /**
     * 添加分账接收方
     * 官方文档 https://pay.weixin.qq.com/wiki/doc/apiv3/open/pay/chapter4_1_4.shtml
     * @description
     * @example
     * @author xiaowei
     * @since 2021-11-25
     * @version 2021-11-25
     * @param $openid
     * @param $type
     * @param $relation_type
     *  @return void
     */
    public function addSharingUser($openid, $type, $relation_type)
    {

        $tmp = array(
            'type'          =>  $type,
            'account'        => $openid,
            'relation_type'  => $relation_type,
        );
        $receiver = json_encode($tmp, JSON_UNESCAPED_UNICODE);
        // API参数
        $params = [
            'appid'     => $this->appid, //商户账号appid
            'mch_id'    => $this->mchid, //商户号
            'nonce_str' => md5(uniqid()),
            'receiver'  => $receiver,
        ];
        // 生成签名
        $params['sign'] = Tools::createSign($params, ['key' => $this->key]);
        // 请求API
        $url = 'https://api.mch.weixin.qq.com/pay/profitsharingaddreceiver';

        $result = (new HttpClient())->post($url, HttpClient::array_to_xml($params));

        return $this->parseResult($result);
    }


    /**
     * 删除分账接收方
     * 官方文档 https://pay.weixin.qq.com/wiki/doc/apiv3/open/pay/chapter4_1_4.shtml
     * 
     * @param $openid
     * @param $type
     * @return bool
     * @throws BaseException
     */
    public function deleteSharingUser($openid, $type)
    {

        // API参数
        $params = [
            'appid'     => $this->appid, //商户账号appid
            'type'      => $type, //商户号
            'account'   => $openid,

        ];
        // 生成签名
        $params['sign'] = Tools::createSign($params, ['key' => $this->key]);
        // 请求API
        $url = 'https://api.mch.weixin.qq.com/v3/profitsharing/receivers/delete';

        $result = (new HttpClient())->post($url, HttpClient::array_to_xml($params));

        return $this->parseResult($result);
    }


    /**
     * 分账
     * @param $order_no
     * @param $out_order_no
     * @param $openid
     * @param $amount
     * @param $type
     * @return bool
     * @throws BaseException
     */
    public function profitsharing($order_no, $out_order_no, $openid, $amount, $desc, $type)
    {
        $tmp = array(
            'type'        => $type,
            'account'     => $openid,
            'amount'      => intval($amount),
            'description' => $desc,
        );
        $receivers[] = $tmp;
        $receivers = json_encode($receivers, JSON_UNESCAPED_UNICODE);
        // API参数
        $params = [
            'appid'          => $this->appid, //商户账号appid
            'mch_id'         => $this->mchid, //商户号
            'nonce_str'      => md5(uniqid()),
            'transaction_id' => $out_order_no,
            'out_order_no'   => $order_no,
            'receivers'      => $receivers
        ];
        // 生成签名
        $params['sign'] = Tools::createSign($params, ['key' => $this->key]);
        // 请求API
        $url = 'https://api.mch.weixin.qq.com/secapi/pay/profitsharing';

        $result = (new HttpClient())->post($url, HttpClient::array_to_xml($params));

        return $this->parseResult($result);
    }


    /**
     * 分账完结
     * @param $order_no
     * @param $out_order_no
     * @return bool
     * @throws BaseException
     */
    public function profitSharingFinish($transaction_id, $out_order_no)
    {
        // API参数
        $params = [
            'appid'          => $this->appid, //商户账号appid
            'mch_id'         => $this->mchid, //商户号
            'nonce_str'      => md5(uniqid()),
            'transaction_id' => $transaction_id,
            'out_order_no'   => $out_order_no,
            'description'    => '分账已完成'
        ];
        // 生成签名
        $params['sign'] = Tools::createSign($params, ['key' => $this->key]);
        // 请求API
        $url = 'https://api.mch.weixin.qq.com/secapi/pay/profitsharingfinish';

        $result = (new HttpClient())->post($url, HttpClient::array_to_xml($params));

        return $this->parseResult($result);
    }
}
