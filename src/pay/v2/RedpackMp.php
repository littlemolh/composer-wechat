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
 * 现金红包
 * 微信小程序专用
 * @description
 * @example
 * @author LittleMo 25362583@qq.com
 * @since 2021-09-25
 * @version 2021-09-25
 */
class RedpackMp extends Base
{

    /**
     * 当前服务器IP地址
     */
    static $ip = null;

    /**
     * 用户openid
     */
    static $openid = null;

    /**
     * 红包金额
     */
    static $money = null;

    /**
     * 红包内容
     */
    static $redpack = null;

    /**
     * 红包数量
     */
    static $number = 1;

    /**
     * 订单编号
     */
    static $no = '';

    public function setIp($ip = '')
    {
        self::$ip = $ip;
    }

    public function setOpenid($openid = '')
    {
        self::$openid = $openid;
    }

    public function setMoney($money = '0.00')
    {
        self::$money = $money;
    }

    public function setRedpack($send_name = '', $wishing = '', $act_name = '', $remark = '', $scene_id = null)
    {
        $redpack['send_name'] = $send_name; //商户名称 红包发送者名称 注意：敏感词会被转义成字符*

        $redpack['wishing'] = $wishing; //红包祝福语 注意：敏感词会被转义成字符*
        $redpack['act_name'] = $act_name; //活动名称 注意：敏感词会被转义成字符*
        $redpack['remark'] = $remark; //备注信息
        /**
         * 发放红包使用场景，红包金额大于200或者小于1元时必传
         * 1 PRODUCT_1:商品促销
         * 2 PRODUCT_2:抽奖
         * 3 PRODUCT_3:虚拟物品兑奖 
         * 4 PRODUCT_4:企业内部福利
         * 5 PRODUCT_5:渠道分润
         * 6 PRODUCT_6:保险回馈
         * 7 PRODUCT_7:彩票派奖
         * 8 PRODUCT_8:税务刮奖
         */
        !empty($scene_id) && $redpack['scene_id'] = 'PRODUCT_' . $scene_id;

        self::$redpack = $redpack;
    }

    public function setNumber($number = 1)
    {
        self::$number = $number;
    }

    public function setNo(&$no = '')
    {
        $no = $no ?: (date("YmdHis") . rand(100000, 999999));
        self::$no = $no;
    }

    /**
     * 发放小程序红包
     * 文档 https://pay.weixin.qq.com/wiki/doc/api/tools/cash_coupon_xcx.php?chapter=18_2&index=3
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-09-25
     * @version 2021-09-25
     * @param string    $openid     用户openid
     * @param string    $money      红包金额，单位元
     * @param string    $no         订单编号
     * @param array     $redpack    红包内容:活动名称、祝福语和备注      
     * @param int       $total_num  红包发放人数   
     * @return void
     */
    public function create()
    {
        /**
         * 是否需要证书	是（证书及使用说明详见商户证书）
         * 请求方式	POST
         * 超时时间（同笔订单最短重试时间）	1s
         */
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendminiprogramhb';


        $params = [];

        $params['nonce_str'] = Tools::createNonceStr(); //随机字符串

        $params['mch_billno'] = self::$no; //商户订单号	
        $params['mch_id'] = $this->mchid; //商户号
        $params['wxappid'] = $this->appid; //公众账号appid 公众号的appid或小程序的appid（在mp.weixin.qq.com申请的）或APP的appid（在open.weixin.qq.com申请的）
        $params['send_name'] = self::$redpack['send_name']; //商户名称 红包发送者名称 注意：敏感词会被转义成字符*
        $params['re_openid'] = self::$openid; //用户openid	
        $params['total_amount'] = bcmul(self::$money, 100); //付款金额，单位分
        $params['total_num'] = self::$number; //红包发放总人数

        $params['notify_way'] = 'MINI_PROGRAM_JSAPI'; //调用接口的机器Ip地址

        $params['wishing'] = self::$redpack['wishing']; //红包祝福语 注意：敏感词会被转义成字符*
        $params['act_name'] = self::$redpack['act_name']; //活动名称 注意：敏感词会被转义成字符*
        $params['remark'] = self::$redpack['remark']; //备注信息
        /**
         * 发放红包使用场景，红包金额大于200或者小于1元时必传
         * 1 PRODUCT_1:商品促销
         * 2 PRODUCT_2:抽奖
         * 3 PRODUCT_3:虚拟物品兑奖 
         * 4 PRODUCT_4:企业内部福利
         * 5 PRODUCT_5:渠道分润
         * 6 PRODUCT_6:保险回馈
         * 7 PRODUCT_7:彩票派奖
         * 8 PRODUCT_8:税务刮奖
         */
        !empty(self::$redpack['scene_id']) && $params['scene_id'] = self::$redpack['scene_id'];

        $params['sign'] = Tools::createSign($params, ['key' => $this->apiv2key]); //签名

        $result = (new HttpClient())->post($url, HttpClient::array_to_xml($params), [], [], [
            'cert_type' => 'PEM',
            'cert' => $this->sslCertPath,
            'key_type' => 'PEM',
            'key' => $this->sslKeyPath,
        ]);

        return $this->parseResult($result);
    }

    /**
     * 领取红包接口
     * 文档 https://pay.weixin.qq.com/wiki/doc/api/tools/cash_coupon_xcx.php?chapter=18_3&index=4
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-09-25
     * @version 2021-09-25
     * @param string    $package    商户将红包信息组成该串，具体方案参见package的说明，package需要进行urlencode再传给页面
     * @return void
     */
    public function biz_red_packet($package, $timeStamp = '', $nonceStr = '')
    {
        $timeStamp = (string)($timeStamp ?: time());
        $nonceStr = $nonceStr ?: Tools::createNonceStr();
        /**
         * 小程序端调用方式
         *
            wx. sendBizRedPacket ({
                "timeStamp": "", // 支付签名时间戳，
                "nonceStr": "", // 支付签名随机串，不长于 32 位
                "package": "", //扩展字段，由商户传入
                "signType": "", // 签名方式，
                "paySign": "", // 支付签名
                "success":function(res){},
                "fail":function(res){},
                "complete":function(res){}
            })
         */
        $params = [];
        $params['timeStamp'] = $timeStamp; // 支付签名时间戳
        $params['nonceStr'] =  $nonceStr; //随机字符串// 支付签名随机串，不长于 32 位
        $params['package'] =  urlencode($package); //扩展字段，由商户传入
        $params['paySign'] = Tools::createSign($params); // 支付签名

        $params['signType'] =  'MD5'; //签名方式

        return  $params;
    }

    /**
     * 查询红包记录
     * 文档 https://pay.weixin.qq.com/wiki/doc/api/tools/cash_coupon_xcx.php?chapter=18_6&index=5
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-09-25
     * @version 2021-09-25
     * @param [type] $openid
     * @param [type] $money
     * @param [type] $no
     * @param string $desc
     * @param string $userName
     * @return void
     */
    public function get($no, $bill_type = 'MCHT')
    {
        /**
         * 是否需要证书	是（证书及使用说明详见商户证书）
         * 请求方式	POST
         * 超时时间（同笔订单最短重试时间）	1s
         */
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/gethbinfo';


        $params = [];

        $params['nonce_str'] = Tools::createNonceStr(); //随机字符串
        $params['mch_billno'] = self::$no; //商户订单号	
        $params['mch_id'] = $this->mchid; //商户号
        $params['appid'] = $this->appid; //公众账号appid 公众号的appid或小程序的appid（在mp.weixin.qq.com申请的）或APP的appid（在open.weixin.qq.com申请的）
        $params['bill_type'] = $bill_type; //MCHT:通过商户订单号获取红包信息。

        $params['sign'] = Tools::createSign($params, ['key' => $this->apiv2key]); //签名

        $result = (new HttpClient())->post($url, HttpClient::array_to_xml($params), [], [], [
            'cert_type' => 'PEM',
            'cert' => $this->sslCertPath,
            'key_type' => 'PEM',
            'key' => $this->sslKeyPath,
        ]);

        return $this->parseResult($result);
    }
}
