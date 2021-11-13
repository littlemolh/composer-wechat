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

namespace littlemo\wechat\pay;

use littlemo\wechat\Base;


/**
 * 微信支付公共方法
 *
 * @description
 * @example
 * @author LittleMo 25362583@qq.com
 * @since 2021-09-15
 * @version 2021-09-15
 */
class PayBase extends Base
{

    /**
     * 整理接口返回结果
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-11-12
     * @version 2021-11-12
     * @param sring $result
     * @return void
     */
    protected function init_result($result = '', $error_field = 'result_code', $error_code = 'SUCCESS')
    {
        var_dump($result);
        $content = $result['content'];
        $obj = simplexml_load_string($content, "SimpleXMLElement", LIBXML_NOCDATA);
        $result['content'] = $obj !== false ? json_encode($obj) : $content;

        parent::init_result($result, $error_field, $error_code);
    }
}
