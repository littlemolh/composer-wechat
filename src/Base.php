<?php

namespace littlemo\wechat;

use littlemo\utils\HttpClient;


/**
 * 公众号\小程序基础对象
 *
 * @description
 * @example
 * @author LittleMo 25362583@qq.com
 * @since 2021-11-05
 * @version 2021-11-05
 */
class Base
{

    /**
     * 小程序的appid
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
     * 小程序唯一凭证密钥，即 AppSecret，获取方式同 appid
     *
     * @var string
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-11
     * @version 2021-11-11
     */
    protected $secret = null;

    /**
     * 微信支付商户号
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
     * 微信支付商户支付密钥
     *
     * @var string
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-11
     * @version 2021-11-11
     */
    protected $key = null;

    /**
     * 微信支付证书cert路径
     */
    protected $sslCertPath = '';

    /**
     * 微信支付证书key路径
     */
    protected $sslKeyPath = '';


    /**
     * 成功消息
     *
     * @var [type]
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-12
     * @version 2021-11-12
     */
    protected static $message = null;

    /**
     * 错误消息
     *
     * @var [type]
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-12
     * @version 2021-11-12
     */
    protected static $error_msg = null;

    /**
     * 完整的消息
     *
     * @var array
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-12
     * @version 2021-11-12
     */
    protected static $intact_msg = [];


    /**
     * 构造函数
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-05
     * @version 2021-11-05
     * @param string $appid     公众号/小程序的appid
     * @param string $secret    公众号/小程序唯一凭证密钥，即 AppSecret，获取方式同 appid
     * @param string $mchid     商户号
     * @param string $key       商户号支付密钥
     */
    public function __construct($appid = null, $secret = null, $mchid = null, $key = null, $certPath = '', $keyPath = '')
    {
        $this->appid = $appid;
        $this->secret = $secret;

        $this->mchid = $mchid;
        $this->key = $key;

        $this->sslCertPath = $certPath;
        $this->sslKeyPath = $keyPath;
    }

    /**
     * 获取全局Access token（支持：公众号、小程序）  
     * 
     * 文档：https://developers.weixin.qq.com/doc/offiaccount/Basic_Information/Get_access_token.html
     * 
     * @description access_token是公众号的全局唯一接口调用凭据，公众号调用各接口时都需使用access_token。开发者需要进行妥善保存。
     * @description access_token的存储至少要保留512个字符空间。access_token的有效期目前为2个小时，需定时刷新，重复获取将导致上次获取的access_token失效。
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-05
     * @version 2021-11-05
     * @param string    $grant_type     获取access_token填写client_credential
     * @return void
     */
    public function token($grant_type = 'client_credential')
    {
        $url = "https://api.weixin.qq.com/cgi-bin/token";
        $params = [
            "grant_type" => $grant_type,
            "appid" =>  $this->appid,
            "secret" =>  $this->secret,
        ];
        return $this->init_result((new HttpClient())->get($url, $params));
    }

    /**
     * 整理接口返回结果
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-12
     * @version 2021-11-12
     * @param [type] $result
     * @return void
     */
    protected function init_result($result, $error_field = 'errcode', $error_code = 0)
    {
        static::$intact_msg[] = $result;

        $content = $result['content'];
        $content =  !empty($content) ? json_decode($content, true) : $content;
        $error_des = $result['error_des'];

        if ($result['code'] === 0 || $content === false) {
            static::$error_msg = $error_des;
            return false;
        } else {
            if (isset($content[$error_field]) && $content[$error_field] !== $error_code) {
                static::$error_msg = $error_des ?: $content;
                return false;
            }
            static::$message = $content;
            return true;
        }
    }

    /**
     * 返回成功消息
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-12
     * @version 2021-11-12
     * @return void
     */
    public function getMessage()
    {
        return self::$message;
    }

    /**
     * 返回失败消息
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-12
     * @version 2021-11-12
     * @return void
     */
    public function getErrorMsg()
    {
        return self::$error_msg;
    }

    /**
     * 返回完整的消息
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-12
     * @version 2021-11-12
     * @return void
     */
    public function getIntactMsg()
    {
        return self::$intact_msg;
    }
}
