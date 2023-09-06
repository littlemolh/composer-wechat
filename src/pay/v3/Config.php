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

namespace littlemo\wechat\pay\v3;


use WeChatPay\Crypto\Rsa;
use WeChatPay\Util\PemUtil;

/**
 * 微信支付V3公共方法
 *
 * @description
 * @example
 * @author LittleMo 25362583@qq.com
 * @since 2021-09-15
 * @version 2021-09-15
 */
class  Config
{

    /**
     * 由微信生成的应用ID，全局唯一
     */
    public static $appid = null;

    /**
     * 由微信生成的应用ID，全局唯一
     */
    public static $subAppid = null;


    /**
     * 商户号，由微信支付生成并下发
     */
    public static $mchid = null;

    /**
     * 子商户号，由微信支付生成并下发
     */
    public static $subMchid = null;

    /**
     * 微信支付商户APIV3支付密钥
     */
    public static $apiKey = null;


    /**
     * 商户私钥实例
     */
    public static $merchantPrivateKeyInstance = null;
    /**
     * 商户证书序列号
     */
    public static $merchantCertificateSerial = null;
    /**
     * 平台公钥实例
     */
    public static $platformPublicKeyInstance = null;

    /**
     * 微信支付平台证书序列号」
     */
    public static $platformCertificateSerial = null;

    public static function create()
    {
        return new self;
    }
    /**
     * 设置证书
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-09-01
     * @version 2023-09-01
     * @param string $merchantCertificateFilePath  商户证书路径
     * @param string $merchantPrivateKeyFilePath   商户私钥路径
     * @param string $platformCertificateFilePath  微信平台证书路径 获取方式https://github.com/wechatpay-apiv3/wechatpay-php/blob/main/bin/README.md
     * @return Config
     */
    public function cert(string $merchantCertificateFilePath, string $merchantPrivateKeyFilePath, string $platformCertificateFilePath = ''): Config
    {

        // 从本地文件中加载「商户API私钥」，「商户API私钥」会用来生成请求的签名
        static::$merchantPrivateKeyInstance = Rsa::from('file:///' . $merchantPrivateKeyFilePath, Rsa::KEY_TYPE_PRIVATE);
        // 「商户API证书」的「证书序列号」
        static::$merchantCertificateSerial = PemUtil::parseCertificateSerialNo('file:///' . $merchantCertificateFilePath);
        // $merchantCertificateSerial = '74D657DDCBB881F684BA1D94A0CCE6453B4E86F7';
        // 从本地文件中加载「微信支付平台证书」，用来验证微信支付应答的签名
        // $platformCertificateFilePath = $this->platformCertPath; //'file:///' . dirname(__DIR__, 3) . '/demo/pay/cert/wechatpay_39C8FA681DDDB06C88C8D851294CDA5E86376089.pem';
        static::$platformPublicKeyInstance = Rsa::from('file:///' . $platformCertificateFilePath, Rsa::KEY_TYPE_PUBLIC);
        // 从「微信支付平台证书」中获取「证书序列号」
        static::$platformCertificateSerial = PemUtil::parseCertificateSerialNo('file:///' . $platformCertificateFilePath);

        return $this;
    }

    /**
     * 设置商户号
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-09-01
     * @version 2023-09-01
     * @param string $mchid         商户号
     * @param string $subMchid      子商户号
     * @return Config
     */
    public function mchid(string $mchid, string $subMchid = ''): Config
    {
        // 商户号
        self::$mchid = $mchid;
        // 子商户号
        self::$subMchid = $subMchid;

        return $this;
    }

    /**
     * 设置 API KEY
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-09-01
     * @version 2023-09-01
     * @param string $apiKey
     * @return Config
     */
    public function apiKey(string $apiKey): Config
    {
        // API KEY
        self::$apiKey = $apiKey;
        return $this;
    }

    /**
     * 设置APPID
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-09-01
     * @version 2023-09-01
     * @param string $appid     appid
     * @param string $subAppid  子级appid
     * @return Config
     */
    public function appid(string $appid, string $subAppid = ''): Config
    {
        // APPID
        self::$appid = $appid;

        self::$subAppid = $subAppid;

        return $this;
    }


    /**
     * 构造一个 APIv3 客户端实例
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-03-24
     * @version 2023-09-01
     * @return Config
     */
    public function build(): Config
    {
        return $this;
    }
}
