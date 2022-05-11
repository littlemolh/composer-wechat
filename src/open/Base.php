<?php

namespace littlemo\wechat\open;

use littlemo\utils\HttpClient;
use littlemo\wechat\core\LWechatException;

/**
 * 公众号\小程序基础对象
 *
 * @description
 * @example
 * @author LittleMo 25362583@qq.com
 * @since 2021-11-05
 * @version 2021-11-05
 */
class Base
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
     * 小程序唯一凭证密钥，即 AppSecret，获取方式同 appid
     *
     * @var string
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-11
     * @version 2021-11-11
     */
    protected $secret = null;


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
     * @param string $key       商户号支付密钥
     */
    public function __construct($appid = null, $secret = null)
    {
        $this->appid = $appid;
        $this->secret = $secret;
    }



    /**
     * 整理接口返回结果
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-12
     * @version 2021-11-12
     * @param [type] $result
     * @return void
     */
    protected function init_result($result, $error_field = 'errcode', $error_code = '', $errmsg_field = 'errmsg')
    {

        $content =  !empty($result['content']) ? json_decode($result['content'], true) : $result['content'];
        if (!$content) {
            $content = $result['content'];
        }
        if ($result['code'] !== 200 || $content === false) {
            throw new LWechatException($result['error_des'], $result['code'], $content);
        }
        if (is_array($content)) {
            if (isset($content[$error_field]) && $content[$error_field] !== $error_code) {
                throw new LWechatException($content[$errmsg_field], $content[$error_field], $content);
            }
        }

        return $content;
    }
}
