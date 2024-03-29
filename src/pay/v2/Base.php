<?php

// +----------------------------------------------------------------------
// | Little Mo - Tool [ WE CAN DO IT JUST TIDY UP IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2023 http://ggui.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: littlemo <25362583@qq.com>
// +----------------------------------------------------------------------

namespace littlemo\wechat\pay\v2;

use littlemo\wechat\core\LWechatException;

/**
 * 微信支付公共方法
 *
 * @description
 * @example
 * @author LittleMo 25362583@qq.com
 * @since 2021-09-15
 * @version 2021-09-15
 */
class  Base
{

    /**
     * 小程序的appid
     *
     * @var string
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-11
     * @version 2021-11-11
     */
    protected $appid = null;


    /**
     * 微信支付商户号
     *
     * @var string
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-11
     * @version 2021-11-11
     */
    protected $mchid = null;

    /**
     * 微信支付商户APIV2支付密钥
     *
     * @var string
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-11
     * @version 2021-11-11
     */
    protected $apiv2key = null;
    /**
     * 微信支付商户APIV3支付密钥
     *
     * @var string
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-11
     * @version 2021-11-11
     */
    protected $apiv3key = null;

    /**
     * 微信支付证书cert路径
     */
    protected $sslCertPath = '';

    /**
     * 微信支付证书key路径
     */
    protected $sslKeyPath = '';

    /**
     * 构造函数
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-05
     * @version 2021-11-05
     * @param string $appid     公众号/小程序的appid
     * @param string $secret    公众号/小程序唯一凭证密钥，即 AppSecret，获取方式同 appid
     * @param string $mchid     商户号
     * @param string $apiv2key       商户号支付密钥
     */
    public function __construct($appid = null, $mchid = null, $apiv2key = null, $certPath = '', $keyPath = '')
    {
        $this->appid = $appid;

        $this->mchid = $mchid;

        $this->apiv2key = $apiv2key;

        $this->sslCertPath = $certPath;
        $this->sslKeyPath = $keyPath;
    }

    public function setApiv3key($key)
    {
        $this->apiv3key = $key;
        return $this;
    }

    /**
     * 整理接口返回结果
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-12
     * @version 2021-11-12
     * @param sring $result 回调内容
     * @param sring $code_field 状态码字段
     * @param sring $success_code 成功状态码
     * @return void
     */
    protected function parseResult(string $result = '', string $code_field = 'result_code', string $success_code = 'SUCCESS')
    {

        $code = $result['code'];
        $content = $result['content'];

        if ($code !== 200 || $content === false) {
            throw new LWechatException($result['error_des']);
        }
        $content = simplexml_load_string($content, "SimpleXMLElement", LIBXML_NOCDATA);
        $content = $content !== false ? json_encode($content) : $content;
        $content = $content !== false ? json_decode($content) : $content;
        if ($content->$code_field != $success_code) {
            throw new LWechatException($content->err_code_des, $content->err_code, $content);
        }
        return $content;
    }
}
