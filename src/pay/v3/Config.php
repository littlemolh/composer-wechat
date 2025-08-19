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
use WeChatPay\Builder;

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
     * 「商户API私钥」
     * 「商户API私钥」会用来生成请求的签名
     */
    public static $merchantPrivateKeyInstance = null;
    /**
     * 「商户API证书」的「证书序列号」
     */
    public static $merchantCertificateSerial = null;
    /**
     * 「微信支付平台证书」
     * 可由内置CLI工具下载到，用来验证微信支付应答的签名
     * or
     * 「微信支付公钥」
     * 可以从「微信支付平台证书」文件解析，也可以在 商户平台 -> 账户中心 -> API安全 查询到
     */
    public static $platformPublicKeyInstance = null;

    /**
     * 「微信支付平台证书」的「平台证书序列号」
     * 可以从「微信支付平台证书」文件解析，也可以在 商户平台 -> 账户中心 -> API安全 查询到
     * or
     * 「微信支付公钥」的「微信支付公钥ID」
     * 需要在 商户平台 -> 账户中心 -> API安全 查询
     */
    public static $platformCertificateSerial = null;

    /**
     * APIv3 客户端实例
     */
    public static $instance = null;
    public static function create()
    {
        return new self;
    }

    /**
     * 商户API证书
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2025-07-29
     * @version 2025-07-29
     * @param string $certificatePath
     * @param string $privateKeyFilePath
     * @return Config
     */
    public function merchantCert($certificatePath, $privateKeyFilePath): Config
    {
        static::$merchantCertificateSerial = PemUtil::parseCertificateSerialNo('file://' . $certificatePath);
        static::$merchantPrivateKeyInstance = Rsa::from('file://' . $privateKeyFilePath, Rsa::KEY_TYPE_PRIVATE);
        return $this;
    }
    /**
     * 微信支付平台证书/微信支付公钥
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2025-07-28
     * @version 2025-07-28
     * @param string $certificateSerial
     * @param string $certFilePath
     * @return Config
     */
    public function platformCert($certFilePath): Config
    {
        static::$platformCertificateSerial = PemUtil::parseCertificateSerialNo('file://' . $certFilePath);;
        static::$platformPublicKeyInstance = Rsa::from('file://' . $certFilePath, Rsa::KEY_TYPE_PUBLIC);
        return $this;
    }
    /**
     * 微信支付公钥
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2025-07-28
     * @version 2025-07-28
     * @param string $id            微信支付公钥ID 需要在 商户平台 -> 账户中心 -> API安全 查询
     * @param string $certFilePath  微信支付公钥文件路径
     * @return Config
     */
    public function publicKey(string $id, string $certFilePath): Config
    {
        static::$platformCertificateSerial = $id;
        static::$platformPublicKeyInstance = Rsa::from('file://' . $certFilePath, Rsa::KEY_TYPE_PUBLIC);
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
    public function subMchid(string $subMchid = ''): Config
    {
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
     * @since 2025-07-28
     * @version 2025-07-28
     * @return bool
     */
    public function buildInstance(): bool
    {
        static::$instance = Builder::factory([
            'mchid'      => Config::$mchid,
            'serial'     => Config::$merchantCertificateSerial,
            'privateKey' => Config::$merchantPrivateKeyInstance,
            'certs'      => [
                Config::$platformCertificateSerial => Config::$platformPublicKeyInstance
            ],
        ]);
        return true;
    }
}
