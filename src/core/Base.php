<?php

namespace littlemo\wechat\core;

use littlemo\utils\HttpClient;
use littlemo\wechat\core\lWechatException;

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
    public function __construct($appid = null, $secret = null)
    {
        $this->appid = $appid;
        $this->secret = $secret;
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
    protected function init_result($result, $error_field = 'errcode', $error_code = 0, $errmsg_field = 'errmsg')
    {

        $content =  !empty($result['content']) ? json_decode($result['content'], true) : $result['content'];
        if (!$content) {
            $content = $result['content'];
        }
        if ($result['code'] !== 200 || $content === false) {
            throw new lWechatException($result['error_des'], $result['code'], $content);
        }
        if (is_array($content)) {
            if (isset($content[$error_field]) && $content[$error_field] !== $error_code) {
                throw new lWechatException($content[$errmsg_field], $content[$error_field], $content);
            }
        }

        return $content;
    }
}
