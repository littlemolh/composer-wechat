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

namespace littlemo\wechat\pay\v3\partner\merchant;

use littlemo\wechat\pay\v3\Config;

/**
 * 特约商户配置
 * @description
 * @example
 * @author LittleMo 25362583@qq.com
 * @since 2025-07-31
 * @version 2025-07-31
 */
class  Configs extends \littlemo\wechat\pay\v3\Base
{

    /**
     * 查询最大分账比例
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2025-07-31
     * @version 2025-07-31
     * @param string $sub_mchid   【子商户号】微信支付分配的子商户号，即分账的出资商户号。
     * @return array
     */
    public function profitsharing(string $sub_mchid): array
    {

        $chain = '/v3/profitsharing/merchant-configs/{sub_mchid}';
        $query = [];

        return $this->get($chain, $query, compact('sub_mchid'), ['Wechatpay-Serial' => Config::$platformCertificateSerial]);
    }
}
