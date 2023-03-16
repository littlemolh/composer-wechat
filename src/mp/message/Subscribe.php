<?php

namespace littlemo\wechat\mp\message;

use littlemo\utils\HttpClient;

/**
 * 订阅消息
 * @description
 * @example
 * @author LittleMo 25362583@qq.com
 * @since 2023-03-16
 * @version 2023-03-16
 * @var littlemo\wechat\mp\message\Subscribe
 */
class Subscribe extends \littlemo\wechat\mp\Base
{

    /**
     * 发送订阅消息
     * @description https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/mp-message-management/subscribe-message/sendMessage.html
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-03-16
     * @version 2023-03-16
     * @param string $access_token          接口调用凭证，该参数为 URL 参数，非 Body 参数。使用access_token或者authorizer_access_token
     * @param string $template_id           所需下发的订阅模板id
     * @param string $touser                接收者（用户）的 openid
     * @param array $data                   模板内容，格式形如 { "key1": { "value": any }, "key2": { "value": any } }的object
     * @param string $miniprogram_state     跳转小程序类型：developer为开发版；trial为体验版；formal为正式版；默认为正式版
     * @param string $page                  点击模板卡片后的跳转页面，仅限本小程序内的页面。支持带参数,（示例index?foo=bar）。该字段不填则模板无跳转
     * @param string $lang                  进入小程序查看”的语言类型，支持zh_CN(简体中文)、en_US(英文)、zh_HK(繁体中文)、zh_TW(繁体中文)，默认为zh_CN
     * @return array
     */
    public function send(string $access_token, string $template_id,  string $touser, array $data, string $miniprogram_state = 'formal', string $page = '', string $lang = 'zh_CN'): array
    {
        //http请求方式: POST https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token=ACCESS_TOKEN
        $url = "https://api.weixin.qq.com/cgi-bin/message/subscribe/send";
        $params = [
            "access_token" =>  $access_token,
        ];
        $data = json_encode($data);
        $body = compact('template_id', 'touser', 'data');
        if ($miniprogram_state) $body['miniprogram_state'] = $miniprogram_state;
        if ($page)  $body['page'] = $page;
        if ($lang)  $body['lang'] = $lang;
        return $this->init_result((new HttpClient())->post($url, $body, $params));
    }
}
