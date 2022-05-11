<?php

// +----------------------------------------------------------------------
// | Little Mo - Tool [ WE CAN DO IT JUST TIDY UP IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2021 http://ggui.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: littlemo <25362583@qq.com>
// +----------------------------------------------------------------------

namespace littlemo\wechat\open;



use littlemo\utils\HttpClient;


/**
 * 微信第三方平台相关接口
 *
 * @description
 * @example
 * @author LittleMo 25362583@qq.com
 * @since 2021-03-25
 * @version 2021-03-25
 */
class Authorizer extends Base
{
    /**
     * 获取/刷新接口调用令牌
     * 文档：https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/ThirdParty/token/api_authorizer_token.html#%E8%AF%B7%E6%B1%82%E5%9C%B0%E5%9D%80
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-03-23
     * @version 2021-03-23
     * @param array $config
     * @return void
     */
    /**
     * Undocumented function
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2022-05-11
     * @version 2022-05-11
     * @param string $component_access_token        第三方平台component_access_token
     * @param string $authorizer_appid              授权方 appid
     * @param string $authorizer_refresh_token      刷新令牌，获取授权信息时得到
     * @return void
     */
    public  function refreshToken($component_access_token, $authorizer_appid, $authorizer_refresh_token)
    {

        /**
         * 调用接口获取预授权码
         * POST
         * https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token?component_access_token=COMPONENT_ACCESS_TOKEN
         */

        $url = 'https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token';
        $params = [
            'component_access_token' => $component_access_token
        ];
        $body = [
            'component_appid' => $this->appid, //第三方平台 appid
            'authorizer_appid' =>  $authorizer_appid, //授权方 appid
            'authorizer_refresh_token' => $authorizer_refresh_token
        ];
        return $this->init_result((new HttpClient())->post($url, $body, $params));
    }
}
