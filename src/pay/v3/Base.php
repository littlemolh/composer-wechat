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

use littlemo\wechat\core\LWechatException;

use WeChatPay\Builder;
use WeChatPay\Crypto\Rsa;
use WeChatPay\Util\PemUtil;
use WeChatPay\Transformer;
use WeChatPay\Formatter;
use WeChatPay\Crypto\AesGcm;

/**
 * 微信支付V3公共方法
 *
 * @description
 * @example
 * @author LittleMo 25362583@qq.com
 * @since 2021-09-15
 * @version 2021-09-15
 */
class  Base
{

    /**
     * 由微信生成的应用ID，全局唯一
     *
     * @var string
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-11
     * @version 2021-11-11
     */
    protected $appid = null;

    /**
     * 由微信生成的应用ID，全局唯一
     *
     * @var string
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-11
     * @version 2021-11-11
     */
    protected $subAppid = null;


    /**
     * 商户号，由微信支付生成并下发
     *
     * @var string
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-11
     * @version 2021-11-11
     */
    protected $mchid = null;

    /**
     * 子商户号，由微信支付生成并下发
     *
     * @var string
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-11
     * @version 2021-11-11
     */
    protected $subMchid = null;

    /**
     * 微信支付商户APIV3支付密钥
     *
     * @var string
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-11
     * @version 2021-11-11
     */
    protected $apiv3Key = null;

    /**
     * 微信支付证书cert路径
     */
    protected $sslCertPath = '';

    /**
     * 微信支付证书key路径
     */
    protected $sslKeyPath = '';

    /**
     * 微信支付平台证书路径
     */
    protected $platformCertPath = '';

    /**
     * APIv3 客户端实例
     * @var object
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2022-12-26
     * @version 2022-12-26
     */
    protected $instance = null;

    /**
     * 平台公钥实例
     * @var resource
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-03-13
     * @version 2023-03-13
     */
    protected static $platformPublicKeyInstance = null;

    /**
     * 平台公钥序列号 从「微信支付平台证书」中获取的「证书序列号」
     * @var string
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-03-13
     * @version 2023-03-13
     */
    protected static $platformCertificateSerial = null;

    /**
     * 构造函数
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2022-12-27
     * @version 2022-12-27
     * @param array $certPath   证书路径 public:公钥，private:私钥，platform：平台证书
     * @param array $mchid      商户号 sp：商户号，sub：下级商户号
     * @param array $apiv3Key   V3支付密钥
     * @param array $appid      应用ID sp:应用appid，sub:下级应用appid
     */
    public function __construct(array $certPath, array  $mchid,  string $apiv3Key = null, array $appid = [])
    {
        // 证书 - 公钥
        if (isset($certPath['public'])) {
            $this->sslCertPath = 'file:///' . $certPath['public'];
        }
        // 证书 - 私钥
        if (isset($certPath['private'])) {
            $this->sslKeyPath = 'file:///' . $certPath['private'];
        }
        // 证书 - 平台
        if (isset($certPath['platform'])) {
            $this->platformCertPath = 'file:///' . $certPath['platform'];
        }

        // 商户号
        $this->mchid = $mchid['sp'];

        if (isset($mchid['sub'])) {
            $this->subMchid = $mchid['sub'];
        }


        // API KEY
        $this->apiv3Key = $apiv3Key;


        // APPID
        if (isset($appid['sp'])) {
            $this->appid = $appid['sp'];
        }
        if (isset($appid['sub'])) {
            $this->subAppid = $appid['sub'];
        }

        $this->init();
    }

    public function init()
    {
        // 设置参数

        // 商户号
        $merchantId = $this->mchid; //'1633500065';

        // 从本地文件中加载「商户API私钥」，「商户API私钥」会用来生成请求的签名
        // $merchantPrivateKeyFilePath = $this->sslKeyPath; //'file:///' . dirname(__DIR__, 3) . '/demo/pay/cert/apiclient_key.pem';
        // $merchantPublicKeyFilePath = $this->sslCertPath; //'file:///' . dirname(__DIR__, 3) . '/demo/pay/cert/apiclient_key.pem';
        $merchantPrivateKeyInstance = Rsa::from($this->sslKeyPath, Rsa::KEY_TYPE_PRIVATE);
        // 「商户API证书」的「证书序列号」
        $merchantCertificateSerial = PemUtil::parseCertificateSerialNo($this->sslCertPath);
        // $merchantCertificateSerial = '74D657DDCBB881F684BA1D94A0CCE6453B4E86F7';
        // 从本地文件中加载「微信支付平台证书」，用来验证微信支付应答的签名
        // $platformCertificateFilePath = $this->platformCertPath; //'file:///' . dirname(__DIR__, 3) . '/demo/pay/cert/wechatpay_39C8FA681DDDB06C88C8D851294CDA5E86376089.pem';
        static::$platformPublicKeyInstance = Rsa::from($this->platformCertPath, Rsa::KEY_TYPE_PUBLIC);

        // 从「微信支付平台证书」中获取「证书序列号」
        static::$platformCertificateSerial = PemUtil::parseCertificateSerialNo($this->platformCertPath);

        // 构造一个 APIv3 客户端实例
        $this->instance = Builder::factory([
            'mchid'      => $merchantId,
            'serial'     => $merchantCertificateSerial,
            'privateKey' => $merchantPrivateKeyInstance,
            'certs'      => [
                static::$platformCertificateSerial => static::$platformPublicKeyInstance,
            ],
        ]);

        // // 发送请求
        // $resp = $instance->chain('v3/certificates')->get(
        //     ['debug' => true] // 调试模式，https://docs.guzzlephp.org/en/stable/request-options.html#debug
        // );
        // echo $resp->getBody(), PHP_EOL;
    }

    public static function encrypt(string $msg): string
    {
        return Rsa::encrypt($msg, static::$platformPublicKeyInstance);
    }

    protected function post(string $chain, array $json, array $headers = [])
    {
        // var_dump($json);
        // var_dump($headers);
        try {
            $resp = $this->instance
                ->chain($chain)
                ->post(compact('json', 'headers'));
            // var_dump($resp->getStatusCode());
            // var_dump($resp->getBody());
            return (array)json_decode($resp->getBody(), true);
        } catch (\Exception $e) {
            // 进行错误处理
            $code = $e->getCode();
            // var_dump($e->getCode());
            $message = $e->getMessage();
            $content = compact('code', 'message');
            // var_dump($e->getMessage());
            if ($e instanceof \GuzzleHttp\Exception\RequestException && $e->hasResponse()) {
                $r = $e->getResponse();
                // var_dump($r->getStatusCode());
                // var_dump($r->getReasonPhrase());
                // var_dump(json_decode($r->getBody(), true));
                $content = json_decode($r->getBody(), true);
            }
            // var_dump($e->getTraceAsString());
            throw new LWechatException($content['message'], $content['code'], $content);
        }
    }

    protected function get(string $chain, array $query, array $patn = [])
    {
        // var_dump($query);
        try {
            $resp = $this->instance
                ->chain($chain)
                ->get(array_merge(compact('query'), $patn));
            // var_dump($resp->getStatusCode());
            // var_dump($resp->getBody());
            return (array)json_decode($resp->getBody(), true);
        } catch (\Exception $e) {
            // 进行错误处理
            $code = $e->getCode();
            // var_dump($e->getCode());
            $message = $e->getMessage();
            $content = compact('code', 'message');
            // var_dump($e->getMessage());
            if ($e instanceof \GuzzleHttp\Exception\RequestException && $e->hasResponse()) {
                $r = $e->getResponse();
                // var_dump($r->getStatusCode());
                // var_dump($r->getReasonPhrase());
                // var_dump(json_decode($r->getBody(), true));
                $content = json_decode($r->getBody(), true);
            }
            // var_dump($e->getTraceAsString());
            throw new LWechatException($content['message'], $content['code'], $content);
        }
    }

    /**
     * 验证并解析支付通知
     * @description https://pay.weixin.qq.com/wiki/doc/apiv3_partner/apis/chapter4_1_5.shtml
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2022-12-28
     * @version 2022-12-28
     * @return void
     */
    public function notify()
    {
        $inWechatpaySignature = $_SERVER['HTTP_WECHATPAY_SIGNATURE'] ?? ''; // 请根据实际情况获取
        $inWechatpayTimestamp = $_SERVER['HTTP_WECHATPAY_TIMESTAMP'] ?? ''; // 请根据实际情况获取
        $inWechatpaySerial = $_SERVER['HTTP_WECHATPAY_SERIAL'] ?? ''; // 请根据实际情况获取
        $inWechatpayNonce = $_SERVER['HTTP_WECHATPAY_NONCE'] ?? ''; // 请根据实际情况获取
        $inRequestID = $_SERVER['HTTP_REQUEST_ID'] ?? ''; // 请根据实际情况获取
        $inBody = file_get_contents('php://input'); // 请根据实际情况获取，例如: file_get_contents('php://input');

        $apiv3Key =  $this->apiv3Key; // 在商户平台上设置的APIv3密钥

        // 根据通知的平台证书序列号，查询本地平台证书文件，
        // 假定为 `/path/to/wechatpay/inWechatpaySerial.pem`
        $platformPublicKeyInstance = Rsa::from($this->platformCertPath, Rsa::KEY_TYPE_PUBLIC);

        // 检查通知时间偏移量，允许5分钟之内的偏移
        $timeOffsetStatus = 300 >= abs(Formatter::timestamp() - (int)$inWechatpayTimestamp);
        if (!$timeOffsetStatus) {
            throw new LWechatException('超过五分钟了');
        }
        $verifiedStatus = Rsa::verify(
            // 构造验签名串
            Formatter::joinedByLineFeed($inWechatpayTimestamp, $inWechatpayNonce, $inBody),
            $inWechatpaySignature,
            $platformPublicKeyInstance
        );
        if (!$verifiedStatus) {
            throw new LWechatException('签名验证失败');
        }
        // 转换通知的JSON文本消息为PHP Array数组
        $inBodyArray = (array)json_decode($inBody, true);
        // 使用PHP7的数据解构语法，从Array中解构并赋值变量
        ['resource' => [
            'ciphertext'      => $ciphertext,
            'nonce'           => $nonce,
            'associated_data' => $aad
        ]] = $inBodyArray;
        // 加密文本消息解密
        $inBodyResource = AesGcm::decrypt($ciphertext, $apiv3Key, $nonce, $aad);
        // 把解密后的文本转换为PHP Array数组
        $inBodyResourceArray = (array)json_decode($inBodyResource, true);
        // print_r($inBodyResourceArray);// 打印解密后的结果
        return $inBodyResourceArray;
    }

    /**
     * 整理接口返回结果
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-12
     * @version 2021-11-12
     * @param sring $result
     * @return void
     */
    protected function parseResult($result = '', $error_field = 'result_code', $error_code = 'SUCCESS')
    {

        $code = $result['code'];
        $content = $result['content'];

        if ($code !== 200 || $content === false) {
            throw new LWechatException($result['error_des']);
        }
        $content = simplexml_load_string($content, "SimpleXMLElement", LIBXML_NOCDATA);
        $content = Transformer::toArray($content);;
        $content = $content !== false ? json_encode($content) : $content;
        $content = $content !== false ? json_decode($content) : $content;
        if ($content->$error_field != $error_code) {
            throw new LWechatException($content->err_code_des, $content->err_code, $content);
        }
        return $content;
    }
}
