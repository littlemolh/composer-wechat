<?php

namespace littlemo\wechat\mp;

use littlemo\wechat\mp\common\WXBizDataCrypt;

use littlemo\utils\HttpClient;
use littlemo\wechat\Base;

/**
 * TODO 小程序登录凭证校验。通过 wx.login 接口获得临时登录凭证 code 换取openid。
 *
 * @author sxd
 * @Date 2019-07-25 10:43
 */
class CodeToSession extends Base
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
    public function CodeToOpenid($code, $grant_type = 'authorization_code')
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

    /**
     * 解 加密后的敏感数据
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-03-11
     * @version 2021-03-11
     * @param array $config
     * @return array
     */
    public function CodeToData($encrypted_data, $iv = '', $session_key = '')
    {
        $result = [
            'code' => 200,
            'content' => [],
            'error_des' => '',
        ];

        $pc = new WXBizDataCrypt($this->appid, $session_key);

        $errCode = $pc->decryptData($encrypted_data, $iv, $data); // 其中$data包含用户的所有数据
        if ($errCode == 0) {
            $result['content'] = $data;
        } else {
            $result['error_des'] = $errCode;
            $result['code'] = 0;
        }

        return $this->init_result($result);
    }
}
