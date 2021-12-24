<?php

namespace littlemo\wechat\mp;

use littlemo\wechat\Base;
use littlemo\utils\HttpClient;

/**
 * TODO 小程序码
 * 注意事项 https://developers.weixin.qq.com/miniprogram/dev/framework/open-ability/qr-code.html#%E6%B3%A8%E6%84%8F%E4%BA%8B%E9%A1%B9
 * @author sxd
 * @Date 2019-07-25 10:43
 */
class QRCode extends Base
{

    /**
     * 获取小程序二维码
     *
     * @description 适用于需要的码数量较少的业务场景。通过该接口生成的小程序码，永久有效，有数量限制。
     * 官方文档：https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/qr-code/wxacode.createQRCode.html
     * 
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-07-06
     * @version 2021-07-06
     * @param string $access_token  接口调用凭证
     * @param string $path          扫码进入的小程序页面路径,最大长度 128 字节，不能为空；对于小游戏，可以只传入 query 部分，来实现传参效果，如：传入 "?foo=bar"，即可在 wx.getLaunchOptionsSync 接口中的 query 参数获取到 {foo:"bar"}。
     * @param string $width         二维码的宽度,默认值：430,单位 px。最小 280px，最大 1280px
     * @return array
     */
    public function createQRCode($access_token, $path = '/', $width = '')
    {

        //POST https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=ACCESS_TOKEN

        $url = "https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode";

        //请求参数
        $body = [
            /**
             * 必填
             * 扫码进入的小程序页面路径
             * 最大长度 128 字节，不能为空；对于小游戏，可以只传入 query 部分，来实现传参效果，
             * 如：传入 "?foo=bar"，即可在 wx.getLaunchOptionsSync 接口中的 query 参数获取到 {foo:"bar"}。
             */
            'path' => $path,
            /**
             * 非必填
             * 默认值：430
             * 二维码的宽度
             * 单位 px。最小 280px，最大 1280px
             */
            'width' => $width,
        ];

        $params = [
            'access_token' => $access_token
        ];


        return $this->init_result((new HttpClient())->post($url, $body, $params));
    }

    /**
     * 获取小程序码
     *
     * @description 适用于需要的码数量较少的业务场景。通过该接口生成的小程序码，永久有效，有数量限制
     * 官方文档：https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/qr-code/wxacode.get.html
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-03-11
     * @version 2021-03-11
     * @param string $access_token  接口调用凭证
     * @param string $path
     * @param string $width
     * @param string $auto_color
     * @param string $line_color
     * @param string $is_hyaline
     * @return array   
     */
    public function get($access_token, $path = '/', $width = '', $auto_color = false, $line_color = '', $is_hyaline = false)
    {
        //POST https://api.weixin.qq.com/wxa/getwxacode?access_token=ACCESS_TOKEN

        $url = "https://api.weixin.qq.com/wxa/getwxacode";

        //请求参数
        $body = [
            /**
             * 必填
             * 扫码进入的小程序页面路径
             * 最大长度 128 字节，不能为空；对于小游戏，可以只传入 query 部分，来实现传参效果，
             * 如：传入 "?foo=bar"，即可在 wx.getLaunchOptionsSync 接口中的 query 参数获取到 {foo:"bar"}。
             */
            'path' => $path,
            /**
             * 非必填
             * 默认值：430
             * 二维码的宽度
             * 单位 px。最小 280px，最大 1280px
             */
            'width' =>  $width,
            /**
             * 非必填
             * 默认值：false
             * 自动配置线条颜色
             * 如果颜色依然是黑色，则说明不建议配置主色调
             */
            'auto_color' => $auto_color,
            /**
             * 非必填
             * 默认值：{"r":0,"g":0,"b":0}
             * auto_color 为 false 时生效
             * 使用 rgb 设置颜色 例如 {"r":"xxx","g":"xxx","b":"xxx"} 十进制表示
             */
            'line_color' =>  $line_color,
            /**
             * 非必填
             * 默认值：false
             * 是否需要透明底色
             * 为 true 时，生成透明底色的小程序码
             */
            'is_hyaline' => $is_hyaline,
        ];

        $params = [
            'access_token' => $access_token
        ];

        return $this->init_result((new HttpClient())->post($url, $body, $params));
    }
    /**
     * 获取小程序码
     * 
     * @description 适用于需要的码数量极多的业务场景。通过该接口生成的小程序码，永久有效，数量暂无限制
     * 官方文档：https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/qr-code/wxacode.getUnlimited.html
     * 
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-07-06
     * @version 2021-07-06
     * @param array     $access_token     接口调用凭证      
     * @param string    $scene            最大32个可见字符，只支持数字，大小写英文以及部分特殊字符：!#$&'()*+,/:;=?@-._~，其它字符请自行编码为合法字符（因不支持%，中文无法使用 urlencode 处理，请使用其他编码方式）
     * @param string    $page	          必须是已经发布的小程序存在的页面（否则报错），例如 pages/index/index, 根路径前不要填加 /,不能携带参数（参数请放在scene字段里），如果不填写这个字段，默认跳主页面
     * @param int       $width            二维码的宽度，单位 px，最小 280px，最大 1280px
     * @param boolean   $auto_colorr      自动配置线条颜色，如果颜色依然是黑色，则说明不建议配置主色调，默认 false
     * @param Object    $line_colorr      auto_color 为 false 时生效，使用 rgb 设置颜色 例如 {"r":"xxx","g":"xxx","b":"xxx"} 十进制表示
     * @param boolean   $is_hyalineline   是否需要透明底色，为 true 时，生成透明底色的小程序
     * @return array
     */
    public function getUnlimited($access_token, $scene = null, $page = '', $width = '', $auto_colorr = false, $line_colorr = null, $is_hyalineline = false)
    {
        //POST https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=ACCESS_TOKEN
        $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit";

        $body = [
            /**
             * 必填
             * 最大32个可见字符，只支持数字，大小写英文以及部分特殊字符：!#$&'()*+,/:;=?@-._~，
             * 其它字符请自行编码为合法字符（因不支持%，中文无法使用 urlencode 处理，请使用其他编码方式）
             * 例如：a=1,b=2
             */
            'scene' => $scene,
            /**
             * 非必填
             * 默认值：主页
             * 必须是已经发布的小程序存在的页面（否则报错），
             * 例如 pages/index/index, 根路径前不要填加 /,不能携带参数（参数请放在scene字段里），如果不填写这个字段，默认跳主页面
             */
            'page' => $page,
            /**
             * 非必填
             * 默认值：430
             * 二维码的宽度，单位 px，最小 280px，最大 1280px
             */
            'width' => $width,
            /**
             * 非必填
             * 默认值：false
             * 自动配置线条颜色，如果颜色依然是黑色，则说明不建议配置主色调，默认 false
             */
            'auto_color' => $auto_colorr,
            /**
             * 非必填
             * 默认值：{"r":0,"g":0,"b":0}
             * auto_color 为 false 时生效，使用 rgb 设置颜色 例如 {"r":"xxx","g":"xxx","b":"xxx"} 十进制表示
             */
            'line_color' => $line_colorr,
            /**
             * 非必填
             * 默认值：false
             * 是否需要透明底色，为 true 时，生成透明底色的小程序
             */
            'is_hyaline' => $is_hyalineline,
        ];

        $params = [
            'access_token' => $access_token
        ];


        return $this->init_result((new HttpClient())->post($url, $body, $params));
    }
}
