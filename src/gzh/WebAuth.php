<?php

namespace littlemo\wechat\gzh;

use littlemo\utils\HttpClient;
use littlemo\wechat\Base;

/**
 * TODO 微信网页授权
 *
 * @author sxd
 * @Date 2019-07-25 10:43
 */
class WebAuth extends Base
{

    /**
     * 通过code换取网页授权access_token
     * 
     * 文档：https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/Wechat_webpage_authorization.html#1
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-09-15
     * @version 2021-09-15
     * @param string $code
     * @return array
     */
    public function access_token($code)
    {

        $grant_type    = 'authorization_code';
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token";
        $params = [
            "appid" =>  $this->appid,
            "secret" =>  $this->secret,
            "code" => $code,
            "grant_type" => $grant_type
        ];
        return $this->init_result((new HttpClient())->get($url, $params));
    }

    /**
     * 刷新access_token（如果需要）
     *
     * 文档：https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/Wechat_webpage_authorization.html#2
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-03-11
     * @version 2021-03-11
     * @param array $refresh_token 填写通过access_token获取到的refresh_token参数
     * @return array
     */
    public function refresh_token($refresh_token)
    {
        $grant_type    = 'refresh_token';
        $url = "https://api.weixin.qq.com/sns/oauth2/refresh_token";
        $params = [
            "appid" =>  $this->appid,
            "grant_type" => $grant_type,
            "refresh_token" => $refresh_token,
        ];
        return $this->init_result((new HttpClient())->get($url, $params));
    }

    /**
     * 拉取用户信息(需scope为 snsapi_userinfo)
     * 文档：https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/Wechat_webpage_authorization.html#3
     * @description 如果网页授权作用域为snsapi_userinfo，则此时开发者可以通过access_token和openid拉取用户信息了。
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-01
     * @version 2021-11-01
     * @param [type] $access_token
     * @param [type] $openid
     * @param string $lang
     * @return void
     */
    public function userinfo($access_token, $openid, $lang = 'zh_CN')
    {
        $url = "https://api.weixin.qq.com/sns/userinfo";
        $params = [
            "access_token" =>  $access_token,
            "openid" => $openid,
            "lang" => $lang,
        ];
        return $this->init_result((new HttpClient())->get($url, $params));
    }
}
