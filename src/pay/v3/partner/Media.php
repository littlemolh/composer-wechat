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

namespace littlemo\wechat\pay\v3\partner;

use littlemo\wechat\pay\v3\Config;

/**
 * Capital
 * @description
 * @example
 * @author LittleMo 25362583@qq.com
 * @since 2025-07-28
 * @version 2025-07-28
 */
class  Media extends \littlemo\wechat\pay\v3\Base
{
    /**
     * ͼƬ�ϴ�
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2025-07-29
     * @version 2025-07-29
     * @param string $path ͼƬ·��(����/�����ַ)
     * @return array
     */
    public function uploadImage(string $path): array
    {
        $chain = '/v3/merchant/media/upload';
        return $this->upload($chain, $path);
    }
    /**
     * ��Ƶ�ϴ�
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2025-07-29
     * @version 2025-07-29
     * @param string $path
     * @return void
     */
    public function uploadVideo(string $path): array
    {
        $chain = '/v3/merchant/media/video_upload';
        return $this->upload($chain, $path);
    }
}
