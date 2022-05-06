<?php

namespace littlemo\wechat\mp;

use littlemo\wechat\mp\common\WXBizDataCrypt;

use littlemo\utils\HttpClient;
use littlemo\wechat\Base;

/**
 * TODO 小程序登录凭证校验。通过 wx.login 接口获得临时登录凭证 code 换取openid。
 * 将在下个大版本（1.3+）删除，使用CodeToSession接替
 *
 * @author sxd
 * @Date 2019-07-25 10:43
 */
class Code2Session extends Base
{


    /**
     * 获取用户小程序openid
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-09-15
     * @version 2021-09-15
     * @param string $code
     * @return array
     */
    public function get($code, $grant_type = 'authorization_code')
    {

        $url = "https://api.weixin.qq.com/sns/jscode2session";
        $params = [
            "appid" =>  $this->appid,
            "secret" =>  $this->secret,
            "js_code" => $code,
            "grant_type" => $grant_type
        ];
        return $this->init_result((new HttpClient())->get($url, $params));
    }
}
