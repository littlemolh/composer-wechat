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

use Exception;
use littlemo\wechat\pay\v3\Config;
use WeChatPay\Util\MediaUtil;

/**
 * Capital
 * @description
 * @example
 * @author LittleMo 25362583@qq.com
 * @since 2025-07-28
 * @version 2025-07-28
 */
class  MediaStandby extends \littlemo\wechat\pay\v3\Base
{
    /**
     * 图片上传
     * @description
     * @example {"media_id":"V1_dMTY_W-vvGPkutcoZzovPB57d99-DmHZh0w0lGj3sogZCHz2l7_AMiX_5R1CougDDHCgTM8IsWTEUxSwrE4Fpc_hCQxYfQ9hsKcnyfisSnBL"}
     * @author LittleMo 25362583@qq.com
     * @since 2025-07-29
     * @version 2025-07-29
     * @param string $path 图片路径(本地/网络地址)
     * @return array
     */
    public function uploadImage(string $path): array
    {
        $chain = '/v3/merchant/media/upload';
        return $this->upload($chain, $path);
    }
    /**
     * 视频上传
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2025-07-29
     * @version 2025-07-29
     * @param string $path
     * @return void
     */
    public function uploadVideo(string $path): array
    {
        $chain = '/v3/merchant/media/video_upload';
        return $this->upload($chain, $path);
    }
    /**
     * 上传文件
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2025-07-30
     * @version 2025-07-30
     * @param string $chain
     * @param string $filePath
     * @return array
     */
    public function upload(string $chain, string $filePath): array
    {

        $media = new MediaUtil($filePath);

        $url = 'https://api.mch.weixin.qq.com' . $chain;
        $merchant_id = Config::$mchid;
        $serial_no = Config::$merchantCertificateSerial;
        // $mch_private_key = $this->getPrivateKey(ROOT_PATH . 'cert/wxpay_shoufutong/apiclient_key.pem');       //商户私钥
        $mch_private_key = Config::$merchantPrivateKeyInstance;       //商户私钥
        $fi = new \finfo(FILEINFO_MIME_TYPE);
        $mime_type =  $media->getContentType();
        $filename = basename($filePath);
        $data['filename'] = $filename;
        $meta['filename'] = $filename;
        $meta['sha256'] = hash_file('sha256', $filePath);
        $boundary = uniqid(); //分割符号
        $date = time();
        $nonce = $this->nonce_str();
        $sign = $this->sign($chain, 'POST', $date, $nonce, json_encode($meta), $mch_private_key, $merchant_id, $serial_no); //$http_method要大写
        $header[] = 'User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.108 Safari/537.36';
        $header[] = 'Accept:application/json';
        $header[] = 'Authorization:WECHATPAY2-SHA256-RSA2048 ' . $sign;
        $header[] = 'Content-Type:multipart/form-data;boundary=' . $boundary;

        $boundaryStr = "--{$boundary}\r\n";
        $out = $boundaryStr;
        $out .= 'Content-Disposition: form-data; name="meta"' . "\r\n";
        $out .= 'Content-Type: application/json' . "\r\n";
        $out .= "\r\n";
        $out .= json_encode($meta) . "\r\n";
        $out .=  $boundaryStr;
        $out .= 'Content-Disposition: form-data; name="file"; filename="' . $data['filename'] . '"' . "\r\n";
        $out .= 'Content-Type: ' . $mime_type . ';' . "\r\n";
        $out .= "\r\n";
        $out .= file_get_contents($filePath) . "\r\n";
        $out .= "--{$boundary}--\r\n";
        $r = $this->doCurl($url, $out, $header);
        if ($r['code'] || $r['error']) {
            throw new Exception($r['error'], $r['code']);
        }
        return json_decode($r['content'], true);
    }

    private function nonce_str()
    {
        return date('YmdHis', time()) . rand(10000, 99999);
    }
    public function doCurl($url, $data, $header = array(), $referer = '', $timeout = 30): array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        //避免https 的ssl验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSLVERSION, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        // 模拟来源
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        return array(
            'code' => $code,
            'content' => $response,
            'error' => $error,
        );
    }

    //获取私钥
    public static function getPrivateKey($filepath)
    {
        return openssl_get_privatekey(file_get_contents($filepath));
    }

    //签名
    private function sign($url, $http_method, $timestamp, $nonce, $body, $mch_private_key, $merchant_id, $serial_no)
    {

        $url_parts = parse_url($url);
        $canonical_url = ($url_parts['path'] . (!empty($url_parts['query']) ? "?${url_parts['query']}" : ""));
        $message =
            $http_method . "\n" .
            $canonical_url . "\n" .
            $timestamp . "\n" .
            $nonce . "\n" .
            $body . "\n";
        openssl_sign($message, $raw_sign, $mch_private_key, 'sha256WithRSAEncryption');
        $sign = base64_encode($raw_sign);
        $schema = 'WECHATPAY2-SHA256-RSA2048 ';
        $token = sprintf(
            'mchid="%s",nonce_str="%s",timestamp="%d",serial_no="%s",signature="%s"',
            $merchant_id,
            $nonce,
            $timestamp,
            $serial_no,
            $sign
        );
        return $token;
    }
}
