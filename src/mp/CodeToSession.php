<?php

namespace littlemo\wechat\mp;

use littlemo\wechat\mp\common\WXBizDataCrypt;

use littlemo\wechat\Base;
use littlemo\wechat\exception\LWechatException;
use littlemo\wechat\mp\common\ErrorCode;

/**
 * TODO 小程序登录凭证校验。通过 wx.login 接口获得临时登录凭证 code 换取openid。
 *
 * @author sxd
 * @Date 2019-07-25 10:43
 */
class CodeToSession extends Base
{

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
    public function get($encrypted_data, $iv = '', $session_key = '')
    {

        $pc = new WXBizDataCrypt($this->appid, $session_key);

        $errCode = $pc->decryptData($encrypted_data, $iv, $data); // 其中$data包含用户的所有数据
        if ($errCode != 0) {
            throw new LWechatException(ErrorCode::getErrorMsg($errCode), $errCode, $data);
        }
        return $data;
    }
}
