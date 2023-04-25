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
    static $access_token = '';

    /**
     * 设置接口调用凭证
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-04-25
     * @version 2023-04-25
     * @param string $access_token 接口调用凭证
     * @return this
     */
    public function accessToken(string $access_token): object
    {
        self::$access_token = $access_token;
        return $this;
    }
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
    public function send(string $template_id,  string $touser, array $data, string $miniprogram_state = 'formal', string $page = '', string $lang = 'zh_CN'): array
    {
        //http请求方式: POST https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token=ACCESS_TOKEN
        $url = "https://api.weixin.qq.com/cgi-bin/message/subscribe/send";
        $params = [
            "access_token" =>  self::$access_token,
        ];
        $data = json_encode($data);
        $body = compact('template_id', 'touser', 'data');
        if ($miniprogram_state) $body['miniprogram_state'] = $miniprogram_state;
        if ($page)  $body['page'] = $page;
        if ($lang)  $body['lang'] = $lang;
        return $this->init_result((new HttpClient())->post($url, $body, $params));
    }

    /**
     * 删除模板
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-04-25
     * @version 2023-04-25 
     * @param string $priTmplId 要删除的模板id
     * @return array
     */
    public function deltemplate(string $priTmplId): array
    {
        $url = "https://api.weixin.qq.com/wxaapi/newtmpl/deltemplate";
        $params = [
            "access_token" =>  self::$access_token,
        ];
        $body = compact('priTmplId');
        return $this->init_result((new HttpClient())->post($url, $body, $params));
    }

    /**
     * 获取类目
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-04-25
     * @version 2023-04-25
     * @return array
     */
    public function getcategory(): array
    {
        $url = "https://api.weixin.qq.com/wxaapi/newtmpl/getcategory";
        $params = [
            "access_token" =>  self::$access_token,
        ];
        return $this->init_result((new HttpClient())->get($url, $params));
    }

    /**
     * 获取关键词列表
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-04-25
     * @version 2023-04-25
     * @param string $tid   模板标题 id，可通过接口获取
     * @return array
     */
    public function getpubtemplatekeywords(string $tid): array
    {
        $url = "https://api.weixin.qq.com/wxaapi/newtmpl/getpubtemplatekeywords";
        $params = [
            "access_token" =>  self::$access_token,
            "tid" =>  $tid,
        ];
        return $this->init_result((new HttpClient())->get($url, $params));
    }

    /**
     * 获取所属类目下的公共模板
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-04-25
     * @version 2023-04-25
     * @param string $ids       类目 id，多个用逗号隔开
     * @param string $start     用于分页，表示从 start 开始。从 0 开始计数
     * @param int $limit        用于分页，表示拉取 limit 条记录。最大为 30
     * @return array
     */
    public function getpubtemplatetitles(string $ids, string $start = 0, int $limit = 10): array
    {
        $url = "https://api.weixin.qq.com/wxaapi/newtmpl/getpubtemplatetitles";
        $params = [
            "access_token" =>  self::$access_token,
            "ids" =>  $ids,
            "start" =>  $start,
            "limit" =>  $limit,
        ];
        return $this->init_result((new HttpClient())->get($url, $params));
    }

    /**
     * 获取个人模板列表
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-04-25
     * @version 2023-04-25
     * @return array
     */
    public function gettemplate(): array
    {
        $url = "https://api.weixin.qq.com/wxaapi/newtmpl/gettemplate";
        $params = [
            "access_token" =>  self::$access_token,
        ];
        return $this->init_result((new HttpClient())->get($url, $params));
    }

    /**
     * 添加模板
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-04-25
     * @version 2023-04-25
     * @param string $tid       模板标题 id，可通过接口获取，也可登录小程序后台查看获取
     * @param array $kidList    开发者自行组合好的模板关键词列表，关键词顺序可以自由搭配（例如 [3,5,4] 或 [4,5,3]），最多支持5个，最少2个关键词组合
     * @param string $sceneDesc 服务场景描述，15个字以内
     * @return array
     */
    public function addtemplate(string $tid, array $kidList, string $sceneDesc): array
    {
        $url = "https://api.weixin.qq.com/wxaapi/newtmpl/addtemplate";
        $params = [
            "access_token" =>  self::$access_token,
        ];
        $body = compact('tid', 'kidList', 'sceneDesc');
        return $this->init_result((new HttpClient())->post($url, $body, $params));
    }
}
