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
use WeChatPay\BuilderChainable;
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
     * @var \littlemo\wechat\pay\v3\Builder
     */
    protected  $builder = null;

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
    public function __construct()
    {
    }

    public function encrypt(string $msg): string
    {
        return Rsa::encrypt($msg, Config::$platformPublicKeyInstance);
    }

    /**
     * POST请求
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-03-24
     * @version 2023-03-24
     * @param string $chain
     * @param array $json       JSON传参
     * @param array $headers
     * @return array
     */
    protected function post(string $chain, array $json, array $headers = []): array
    {
        return $this->request('post',  $chain,  $json,  $headers, [], []);
    }

    /**
     * GET请求
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-03-24
     * @version 2023-03-24
     * @param string $chain
     * @param array $query  URL传参
     * @param array $patn   路径参数
     * @return array
     */
    protected function get(string $chain, array $query, array $patn = []): array
    {
        return $this->request('get',  $chain, [], [],  $query,  $patn);
    }

    protected function request(string $method, string $chain, array $json, array $headers = [], array $query, array $patn = []): array
    {
        try {
            // 构造一个 APIv3 客户端实例
            $instance = Builder::factory([
                'mchid'      => Config::$mchid,
                'serial'     => Config::$merchantCertificateSerial,
                'privateKey' => Config::$merchantPrivateKeyInstance,
                'certs'      => [
                    Config::$platformCertificateSerial => Config::$platformPublicKeyInstance,
                ],
            ])->chain($chain);

            $resp = $instance
                ->$method(array_merge(compact('query', 'json', 'headers'), $patn));
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

        // 检查通知时间偏移量，允许5分钟之内的偏移
        $timeOffsetStatus = 300 >= abs(Formatter::timestamp() - (int)$inWechatpayTimestamp);
        if (!$timeOffsetStatus) {
            throw new LWechatException('超过五分钟了');
        }
        $verifiedStatus = Rsa::verify(
            // 构造验签名串
            Formatter::joinedByLineFeed($inWechatpayTimestamp, $inWechatpayNonce, $inBody),
            $inWechatpaySignature,
            Config::$platformPublicKeyInstance
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
        $inBodyResource = AesGcm::decrypt($ciphertext, Config::$apiKey, $nonce, $aad);
        // 把解密后的文本转换为PHP Array数组
        $inBodyResourceArray = (array)json_decode($inBodyResource, true);
        // print_r($inBodyResourceArray);// 打印解密后的结果
        return $inBodyResourceArray;
    }

    /**
     * 整理接口返回结果(暂时没用到)
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
