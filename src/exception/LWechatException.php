<?php

namespace littlemo\wechat\exception;


/**
 * 微信支付异常抛出
 * @description
 * @example
 * @author LittleMo 25362583@qq.com
 * @since 2022-04-29
 * @version 2022-04-29
 */
class lWechatException extends \Exception
{
    /**
     * DbException constructor.
     * @param string    $message
     * @param array     $config
     * @param string    $sql
     * @param int       $code
     */

    public function __construct($message = "", $code = 0, $data = [])
    {
        $this->message = $message;
        $this->code = $code;
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }
}
