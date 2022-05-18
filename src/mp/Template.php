<?php

namespace littlemo\wechat\mp;

use littlemo\utils\HttpClient;


/**
 * TODO 模板消息
 *
 * @author sxd
 * @Date 2019-07-25 10:43
 */
class Template extends Base
{

    /**
     * 设置所属行业
     * 文档：https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Template_Message_Interface.html#%E8%AE%BE%E7%BD%AE%E6%89%80%E5%B1%9E%E8%A1%8C%E4%B8%9A
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2022-05-12
     * @version 2022-05-12
     * @param string $access_token      接口调用凭证
     * @param string $industry_id1      公众号模板消息所属行业编号
     * @param string $industry_id2      公众号模板消息所属行业编号
     * @return void
     */
    public function setIndustry($access_token, $industry_id1, $industry_id2)
    {
        //http请求方式: POST https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token=ACCESS_TOKEN
        $url = "https://api.weixin.qq.com/cgi-bin/template/api_set_industry";
        $params = [
            "access_token" =>  $access_token,
        ];
        $body = compact('industry_id1', 'industry_id2');
        return $this->init_result((new HttpClient())->post($url, $body, $params));
    }

    /**
     * 获取设置的行业信息
     * 文档：https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Template_Message_Interface.html#%E8%8E%B7%E5%8F%96%E8%AE%BE%E7%BD%AE%E7%9A%84%E8%A1%8C%E4%B8%9A%E4%BF%A1%E6%81%AF
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2022-05-12
     * @version 2022-05-12
     * @param string $access_token      接口调用凭证
     * @return void
     */
    public function getIndustry($access_token)
    {
        // http请求方式：GET https://api.weixin.qq.com/cgi-bin/template/get_industry?access_token=ACCESS_TOKEN
        $url = "https://api.weixin.qq.com/cgi-bin/template/get_industry";
        $params = [
            "access_token" =>  $access_token,
        ];
        return $this->init_result((new HttpClient())->get($url, $params));
    }

    /**
     * 获得模板ID
     * 文档：https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Template_Message_Interface.html#%E8%8E%B7%E5%8F%96%E8%AE%BE%E7%BD%AE%E7%9A%84%E8%A1%8C%E4%B8%9A%E4%BF%A1%E6%81%AF
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2022-05-12
     * @version 2022-05-12
     * @param string $access_token          接口调用凭证
     * @param string $template_id_short     模板库中模板的编号，有“TM**”和“OPENTMTM**”等形式
     * @return void
     */
    public function add($access_token, $template_id_short)
    {
        // http请求方式: POST https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token=ACCESS_TOKEN
        $url = "https://api.weixin.qq.com/cgi-bin/template/api_add_template";
        $params = [
            "access_token" =>  $access_token,
        ];
        $body = compact('template_id_short');
        return $this->init_result((new HttpClient())->post($url, $body, $params));
    }

    /**
     * 获取模板列表
     * 文档：https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Template_Message_Interface.html#%E8%8E%B7%E5%8F%96%E6%A8%A1%E6%9D%BF%E5%88%97%E8%A1%A8
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2022-05-12
     * @version 2022-05-12
     * @param string $access_token          接口调用凭证
     * @return void
     */
    public function getAllPrivate($access_token)
    {
        // http请求方式：GET https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token=ACCESS_TOKEN
        $url = "https://api.weixin.qq.com/cgi-bin/template/get_all_private_template";
        $params = [
            "access_token" =>  $access_token,
        ];
        return $this->init_result((new HttpClient())->get($url, $params));
    }

    /**
     * 删除模板
     * 文档：https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Template_Message_Interface.html#%E5%88%A0%E9%99%A4%E6%A8%A1%E6%9D%BF
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2022-05-12
     * @version 2022-05-12
     * @param string $access_token          接口调用凭证
     * @return void
     */
    public function delPrivate($access_token, $template_id)
    {
        // http请求方式：POST https://api.weixin.qq.com/cgi-bin/template/del_private_template?access_token=ACCESS_TOKEN
        $url = "https://api.weixin.qq.com/cgi-bin/template/get_all_private_template";
        $params = [
            "access_token" =>  $access_token,
        ];
        $body = compact('template_id');
        return $this->init_result((new HttpClient())->post($url, $body, $params));
    }

    /**
     * 发送模板消息
     * 文档：https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Template_Message_Interface.html#%E5%8F%91%E9%80%81%E6%A8%A1%E6%9D%BF%E6%B6%88%E6%81%AF
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2022-05-12
     * @version 2022-05-12
     * @param string $touser        接收者openid
     * @param string $template_id   模板ID
     * @param string $url           模板跳转链接（海外帐号没有跳转能力）
     * @param array $miniprogram    跳小程序所需数据，不需跳小程序可不用传该数据
     * @param array $data           模板数据
     * @param string $access_token  接口调用凭证
     * @return void
     */
    public function send($access_token, $touser, $template_id, array $data, $url = '', $miniprogram = [])
    {

        $api = "https://api.weixin.qq.com/cgi-bin/message/template/send";

        $params = compact('access_token');
        $body = compact('touser', 'template_id',  'data', 'url', 'miniprogram');

        return $this->init_result((new HttpClient())->post($api, $body, $params));
    }
}
