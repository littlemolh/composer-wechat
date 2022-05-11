<?php

namespace littlemo\wechat\mp;

use littlemo\utils\HttpClient;


/**
 * TODO 微信网页授权
 *
 * @author sxd
 * @Date 2019-07-25 10:43
 */
class Template extends Base
{

    /**
     * 通过code换取网页授权access_token
     * 
     * 文档：https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Template_Message_Interface.html
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-09-15
     * @version 2021-09-15
     * @param string $code
     * @return array
     */
    public function send($touser, $template_id, $url = '', $miniprogram = [], $data = [], $access_token = '')
    {

        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send";
        $params = [
            "access_token" =>  $access_token,
        ];
        $body = compact('touser', 'template_id', 'url', 'miniprogram', 'data');
        return $this->init_result((new HttpClient())->post($url, $body, $params));
    }
}
