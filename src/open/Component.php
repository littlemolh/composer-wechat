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
use littlemo\wechat\Base;

/**
 * 微信第三方平台相关接口
 *
 * @description
 * @example
 * @author LittleMo 25362583@qq.com
 * @since 2021-03-25
 * @version 2021-03-25
 */
class Component extends Base
{
    /**
     * 获取令牌
     * 文档：https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/ThirdParty/token/component_access_token.html
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-03-23
     * @version 2021-03-23
     * @param array $config
     * @return void
     */
    public  function getComponentAccessToken($verifyTicket)
    {

        /**
         * 调用接口获取预授权码
         * POST
         * https://api.weixin.qq.com/cgi-bin/component/api_component_token
         */

        $url = 'https://api.weixin.qq.com/cgi-bin/component/api_component_token';

        $body = [
            'component_appid' => $this->appid, //第三方平台 appid
            'component_appsecret' =>  $this->secret, //第三方平台 appsecret
            'component_verify_ticket' => $this->verifyTicket
        ];
        return $this->init_result((new HttpClient())->post($url, $body));
    }

    /**
     * 获取预授权码
     * 由于平台共用，所以这个暂且存在缓存
     * 文档：https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/ThirdParty/token/pre_auth_code.html
     * @description 
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-03-23
     * @version 2021-03-23
     * @param array $config 配置信息
     * @return void
     */
    public function getPreAuthCode($component_access_token)
    {

        /**
         * 调用接口获取预授权码
         * POST
         * https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token=COMPONENT_ACCESS_TOKEN
         */
        $url = 'https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode';
        $body = [
            'component_appid' => $this->appid
        ];
        $params = [
            'component_access_token' => $component_access_token
        ];

        return $this->init_result((new HttpClient())->post($url, $body, $params));
    }



    /**
     * 生成授权地址授权
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-03-25
     * @version 2021-03-25
     * @param array $config 配置信息
     * @param string $pre_auth_code 预授权码
     * @param string $redirect_uri get通知的url
     * @param integer $auth_type   授权的帐号类型 
     *                              1 则商户扫码后，手机端仅展示公众号
     *                              2 表示仅展示小程序
     *                              3 表示公众号和小程序都展示
     *                              如果为未指定，则默认小程序和公众号都展示。第三方平台开发者可以使用本字段来控制授权的帐号类型。
     * @return void
     */
    public function auth($pre_auth_code, $redirect_uri = '', $auth_type = 1)
    {
        $url = 'https://mp.weixin.qq.com/cgi-bin/componentloginpage?';
        $params = [
            'component_appid' => $this->appid, //第三方平台方 appid
            'pre_auth_code' => $pre_auth_code, //预授权码
            'redirect_uri' => urlencode($redirect_uri),
            'auth_type' => $auth_type //授权的帐号类型 
        ];
        return (new HttpClient())->buildUrl($url, $params);
    }




    /**
     * 获取授权方的帐号基本信息
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/ThirdParty/token/api_get_authorizer_info.html
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-03-23
     * @version 2021-03-23
     * @param string $authorizer_appid //授权方 appid
     * @param array $component_access_token 第三方平台component_access_token，不是authorizer_access_token
     * @return void
     */
    public function getAuthorizerInfo($authorizer_appid = '', $component_access_token = '')
    {


        /**
         * 获取授权方的帐号基本信息
         * POST
         * https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info
         */
        $url = 'https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info';

        $body = [
            'component_access_token' => $component_access_token,
            'component_appid' => $this->appid,
            'authorizer_appid' => $authorizer_appid,
        ];
        return $this->init_result((new HttpClient())->post($url, $body));
    }

    /**
     * 使用授权码获取授权信息
     * https://developers.weixin.qq.com/doc/oplatform/Third-party_Platforms/2.0/api/ThirdParty/token/authorization_info.html
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-03-25
     * @version 2021-03-25
     * @param string $component_access_token    第三方平台component_access_token，不是authorizer_access_token
     * @param string $authorization_code        授权码, 会在授权成功时返回给第三方平台，详见第三方平台授权流程说明
     * @return void
     */
    public function getQueryAuth($component_access_token, $authorization_code = '')
    {
        /**
         * POST
         * https://api.weixin.qq.com/cgi-bin/component/api_query_auth
         */

        $url = 'https://api.weixin.qq.com/cgi-bin/component/api_query_auth';
        $body = [
            'component_access_token' => $component_access_token,
            'component_appid' => $this->appid,
            'authorization_code' => $authorization_code,
        ];

        return $this->init_result((new HttpClient())->post($url, $body));
    }
}
