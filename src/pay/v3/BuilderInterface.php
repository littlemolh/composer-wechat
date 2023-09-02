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
declare(strict_types=1);

namespace littlemo\wechat\pay\v3;


interface  BuilderInterface
{


    public static function create(): BuilderInterface;
    public function cert(string $public_path, string $private_path, string $platform_path = ''): BuilderInterface;
    public function mchid(string $mchid, string $subMchid = ''): BuilderInterface;
    public function apiKey(string $apiKey): BuilderInterface;
    public function appid(string $appid, string $subAppid = ''): BuilderInterface;
    public function build(): BuilderInterface;
}
