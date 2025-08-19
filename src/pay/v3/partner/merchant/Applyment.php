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
use littlemo\wechat\pay\v3\partner\Media;

/**
 * 特约商户进件
 * @description
 * @example
 * @author LittleMo 25362583@qq.com
 * @since 2025-07-28
 * @version 2025-07-28
 */
class  Applyment extends \littlemo\wechat\pay\v3\Base
{
    /**
     * 业务申请编号
     * @var string
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2025-07-29
     * @version 2025-07-29
     */
    private $business_code = '';
    /**
     * 超级管理员信息
     * @var array
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2025-07-29
     * @version 2025-07-29
     */
    private $contact_info = [];
    /**
     * 主体资料
     * @var array
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2025-07-29
     * @version 2025-07-29
     */
    private $subject_info = [];
    /**
     * 经营资料
     * @var array
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2025-07-29
     * @version 2025-07-29
     */
    private $business_info = [];
    /**
     * 结算规则
     * @var array
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2025-07-29
     * @version 2025-07-29
     */
    private $settlement_info = [];
    /**
     * 【结算银行账户】请填写商家提现收款的银行账户信息
     * @var array
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2025-07-29
     * @version 2025-07-29
     */
    private $bank_account_info = [];
    /**
     * 【补充材料】 根据实际审核情况，会额外要求商家提供指定的补充资料
     * @var array
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2025-07-29
     * @version 2025-07-29
     */
    private $addition_info = [];

    private $media = null;
    /**
     * 创建一个新的实例
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2025-07-29
     * @version 2025-07-29
     * @return Applyment
     */
    public static function create($media = null): self
    {
        $obj = new self();
        $obj->media = $media ?: new Media();
        return $obj;
    }

    /**
     * 【业务申请编号】
     * @description 1、只能由数字、字母或下划线组成，建议前缀为服务商商户号；2、服务商自定义的唯一编号；3、每个编号对应一个申请单，每个申请单审核通过后会生成一个微信支付商户号。
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2025-07-29
     * @version 2025-07-29
     * @param [type] $business_code
     * @return Applyment
     */
    public function businessCode(string $business_code): self
    {
        $this->business_code = $business_code;
        return $this;
    }
    /**
     * 【超级管理员信息】
     * @description 超级管理员需在开户后进行签约，并可接收日常重要管理信息和进行资金操作，请确定其为商户法定代表人或负责人。
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2025-07-29
     * @version 2025-07-29
     * @param array $contact_info
     * @return self
     */
    public function contactInfo(array $contact_info): self
    {
        // 【超级管理员姓名】
        if (isset($contact_info['contact_name']) && $contact_info['contact_name']) {
            $contact_info['contact_name'] = static::encrypt($contact_info['contact_name']);
        }
        // 【超级管理员身份证件号码】
        if (isset($contact_info['contact_id_number']) && $contact_info['contact_id_number']) {
            $contact_info['contact_id_number'] = static::encrypt($contact_info['contact_id_number']);
        }
        // 【超级管理员证件正面照片】
        if (isset($contact_info['contact_id_doc_copy']) && $contact_info['contact_id_doc_copy']) {
            $contact_info['contact_id_doc_copy'] = $this->media->uploadImage($contact_info['contact_id_doc_copy'])['media_id'];
        }
        // 【超级管理员证件反面照片】
        if (isset($contact_info['contact_id_doc_copy_back']) && $contact_info['contact_id_doc_copy_back']) {
            $contact_info['contact_id_doc_copy_back'] = $this->media->uploadImage($contact_info['contact_id_doc_copy_back'])['media_id'];
        }
        // 【业务办理授权函】
        if (isset($contact_info['business_authorization_letter']) && $contact_info['business_authorization_letter']) {
            $contact_info['business_authorization_letter'] = $this->media->uploadImage($contact_info['business_authorization_letter'])['media_id'];
        }
        // 【联系手机】
        if (isset($contact_info['mobile_phone']) && $contact_info['mobile_phone']) {
            $contact_info['mobile_phone'] = static::encrypt($contact_info['mobile_phone']);
        }
        // 【联系手机】
        if (isset($contact_info['contact_email']) && $contact_info['contact_email']) {
            $contact_info['contact_email'] = static::encrypt($contact_info['contact_email']);
        }
        $this->contact_info = $contact_info;
        return $this;
    }
    /**
     * 【主体资料】
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2025-07-29
     * @version 2025-07-29
     * @param array $subject_info
     * @return self
     */
    public function subjectInfo(array $subject_info): self
    {
        // 【营业执照照片】
        if (isset($subject_info['business_license_info']['license_copy']) && $subject_info['business_license_info']['license_copy']) {
            event('wechat.partner.pay.v3.instance');
            $subject_info['business_license_info']['license_copy'] = $this->media->uploadImage($subject_info['business_license_info']['license_copy'])['media_id'];
        }
        // 【登记证书照片】
        if (isset($subject_info['certificate_info']['cert_copy']) && $subject_info['certificate_info']['cert_copy']) {
            $subject_info['certificate_info']['cert_copy'] = $this->media->uploadImage($subject_info['certificate_info']['cert_copy'])['media_id'];
        }
        // 【单位证明函照片】
        if (isset($subject_info['certificate_letter_copy']) && $subject_info['certificate_letter_copy']) {
            $subject_info['certificate_letter_copy'] = $this->media->uploadImage($subject_info['certificate_letter_copy'])['media_id'];
        }
        // 【金融机构许可证图片】
        if (isset($subject_info['finance_institution_info']['finance_license_pics']) && $subject_info['finance_institution_info']['finance_license_pics']) {
            $subject_info['finance_institution_info']['finance_license_pics'] = $this->media->uploadImage($subject_info['finance_institution_info']['finance_license_pics'])['media_id'];
        }
        // 【法定代表人说明函】
        if (isset($subject_info['identity_info']['authorize_letter_copy']) && $subject_info['identity_info']['authorize_letter_copy']) {
            $subject_info['identity_info']['authorize_letter_copy'] = $this->media->uploadImage($subject_info['identity_info']['authorize_letter_copy'])['media_id'];
        }
        // 【身份证信息】【身份证人像面照片】
        if (isset($subject_info['identity_info']['id_card_info']['id_card_copy']) && $subject_info['identity_info']['id_card_info']['id_card_copy']) {
            $subject_info['identity_info']['id_card_info']['id_card_copy'] = $this->media->uploadImage($subject_info['identity_info']['id_card_info']['id_card_copy'])['media_id'];
        }
        // 【身份证信息】【身份证国徽面照片】
        if (isset($subject_info['identity_info']['id_card_info']['id_card_national']) && $subject_info['identity_info']['id_card_info']['id_card_national']) {
            $subject_info['identity_info']['id_card_info']['id_card_national'] = $this->media->uploadImage($subject_info['identity_info']['id_card_info']['id_card_national'])['media_id'];
        }
        // 【身份证信息】【身份证姓名】
        if (isset($subject_info['identity_info']['id_card_info']['id_card_name']) && $subject_info['identity_info']['id_card_info']['id_card_name']) {
            $subject_info['identity_info']['id_card_info']['id_card_name'] = static::encrypt($subject_info['identity_info']['id_card_info']['id_card_name']);
        }
        // 【身份证信息】【身份证号码】
        if (isset($subject_info['identity_info']['id_card_info']['id_card_number']) && $subject_info['identity_info']['id_card_info']['id_card_number']) {
            $subject_info['identity_info']['id_card_info']['id_card_number'] = static::encrypt($subject_info['identity_info']['id_card_info']['id_card_number']);
        }
        // 【身份证信息】【身份证居住地址】
        if (isset($subject_info['identity_info']['id_card_info']['id_card_address']) && $subject_info['identity_info']['id_card_info']['id_card_address']) {
            $subject_info['identity_info']['id_card_info']['id_card_address'] = static::encrypt($subject_info['identity_info']['id_card_info']['id_card_address']);
        }
        // 【其他类型证件信息】【证件正面照片】
        if (isset($subject_info['identity_info']['id_doc_info']['id_doc_copy']) && $subject_info['identity_info']['id_doc_info']['id_doc_copy']) {
            $subject_info['identity_info']['id_doc_info']['id_doc_copy'] = $this->media->uploadImage($subject_info['identity_info']['id_doc_info']['id_doc_copy'])['media_id'];
        }
        // 【其他类型证件信息】【证件反面照片】
        if (isset($subject_info['identity_info']['id_doc_info']['id_doc_copy_back']) && $subject_info['identity_info']['id_doc_info']['id_doc_copy_back']) {
            $subject_info['identity_info']['id_doc_info']['id_doc_copy_back'] = $this->media->uploadImage($subject_info['identity_info']['id_doc_info']['id_doc_copy_back'])['media_id'];
        }
        // 【其他类型证件信息】【证件姓名】
        if (isset($subject_info['identity_info']['id_doc_info']['id_doc_name']) && $subject_info['identity_info']['id_doc_info']['id_doc_name']) {
            $subject_info['identity_info']['id_doc_info']['id_doc_name'] = static::encrypt($subject_info['identity_info']['id_doc_info']['id_doc_name']);
        }
        // 【其他类型证件信息】【证件号码】
        if (isset($subject_info['identity_info']['id_doc_info']['id_doc_number']) && $subject_info['identity_info']['id_doc_info']['id_doc_number']) {
            $subject_info['identity_info']['id_doc_info']['id_doc_number'] = static::encrypt($subject_info['identity_info']['id_doc_info']['id_doc_number']);
        }
        // 【其他类型证件信息】【证件居住地址】
        if (isset($subject_info['identity_info']['id_doc_info']['id_doc_address']) && $subject_info['identity_info']['id_doc_info']['id_doc_address']) {
            $subject_info['identity_info']['id_doc_info']['id_doc_address'] = static::encrypt($subject_info['identity_info']['id_doc_info']['id_doc_address']);
        }
        // 【最终受益人信息列表(UBO)】 仅企业需要填写。
        if (isset($subject_info['ubo_info_list']) && is_array($subject_info['ubo_info_list']) && count($subject_info['ubo_info_list']) > 0) {
            foreach ($subject_info['ubo_info_list'] as &$item) {
                $item['ubo_id_doc_copy'] = $this->media->uploadImage($item['ubo_id_doc_copy'])['media_id'];
                $item['ubo_id_doc_copy_back'] = $this->media->uploadImage($item['ubo_id_doc_copy_back'])['media_id'];
                $item['ubo_id_doc_name'] = static::encrypt($item['ubo_id_doc_name']);
                $item['ubo_id_doc_number'] = static::encrypt($item['ubo_id_doc_number']);
                $item['ubo_id_doc_address'] = static::encrypt($item['ubo_id_doc_address']);
            }
        }

        $this->subject_info = $subject_info;
        return $this;
    }
    /**
     * 【经营资料】 请填写商家的经营业务信息、售卖商品/提供服务场景信息
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2025-07-29
     * @version 2025-07-29
     * @param array $business_info
     * @return self
     */
    public function businessInfo(array $business_info): self
    {
        // 【经营场景】【线下场所场景】【线下场所门头照片】
        if (isset($business_info['sales_info']['biz_store_info']['store_entrance_pic']) && $business_info['sales_info']['biz_store_info']['store_entrance_pic']) {
            foreach ($business_info['sales_info']['biz_store_info']['store_entrance_pic'] as &$item) {
                $item = $this->media->uploadImage($item)['media_id'];
            }
        }
        // 【经营场景】【线下场所场景】【线下场所内部照片】
        if (isset($business_info['sales_info']['biz_store_info']['indoor_pic']) && $business_info['sales_info']['biz_store_info']['indoor_pic']) {
            foreach ($business_info['sales_info']['biz_store_info']['indoor_pic'] as &$item) {
                $item = $this->media->uploadImage($item)['media_id'];
            }
        }
        // 【经营场景】【服务号或公众号场景】【服务号或公众号页面截图】
        if (isset($business_info['sales_info']['mp_info']['mp_pics']) && $business_info['sales_info']['mp_info']['mp_pics']) {
            foreach ($business_info['sales_info']['mp_info']['mp_pics'] as &$item) {
                $item = $this->media->uploadImage($item)['media_id'];
            }
        }
        // 【经营场景】【小程序场景】【小程序截图】
        if (isset($business_info['sales_info']['mini_program_info']['mini_program_pics']) && $business_info['sales_info']['mini_program_info']['mini_program_pics']) {
            foreach ($business_info['sales_info']['mini_program_info']['mini_program_pics'] as &$item) {
                $item = $this->media->uploadImage($item)['media_id'];
            }
        }
        // 【经营场景】【App场景】【App截图】
        if (isset($business_info['sales_info']['app_info']['app_pics']) && $business_info['sales_info']['app_info']['app_pics']) {
            foreach ($business_info['sales_info']['app_info']['app_pics'] as &$item) {
                $item = $this->media->uploadImage($item)['media_id'];
            }
        }
        // 【经营场景】【互联网网站场景】【网站授权函】
        if (isset($business_info['sales_info']['web_info']['web_authorisation']) && $business_info['sales_info']['web_info']['web_authorisation']) {
            $business_info['sales_info']['web_info']['web_authorisation'] = $this->media->uploadImage($business_info['sales_info']['web_info']['web_authorisation'])['media_id'];
        }
        // 【经营场景】【企业微信场景】【企业微信页面截图】
        if (isset($business_info['sales_info']['wework_info']['wework_pics']) && $business_info['sales_info']['wework_info']['wework_pics']) {
            foreach ($business_info['sales_info']['wework_info']['wework_pics'] as &$item) {
                $item = $this->media->uploadImage($item)['media_id'];
            }
        }
        $this->business_info = $business_info;
        return $this;
    }
    /**
     * 【结算规则】
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2025-07-29
     * @version 2025-07-29
     * @param array $settlement_info
     * @return self
     */
    public function settlementInfo(array $settlement_info): self
    {
        $settlement_info['settlement_id'] = (string)$settlement_info['settlement_id'];
        $settlement_info['activities_rate'] = (string)$settlement_info['activities_rate'];
        $settlement_info['debit_activities_rate'] = (string)$settlement_info['debit_activities_rate'];
        $settlement_info['credit_activities_rate'] = (string)$settlement_info['credit_activities_rate'];
        // 【特殊资质图片】
        if (isset($settlement_info['qualifications']) && $settlement_info['qualifications']) {
            foreach ($settlement_info['qualifications'] as &$item) {
                $item = $this->media->uploadImage($item)['media_id'];
            }
        }
        // 【优惠费率活动补充材料】
        if (isset($settlement_info['activities_additions']) && $settlement_info['activities_additions']) {
            foreach ($settlement_info['activities_additions'] as &$item) {
                $item = $this->media->uploadImage($item)['media_id'];
            }
        }
        $this->settlement_info = $settlement_info;
        return $this;
    }
    /**
     * 【结算银行账户】
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2025-07-29
     * @version 2025-07-29
     * @param array $settlement_info
     * @return self
     */
    public function bankAccountInfo(array $bank_account_info): self
    {
        // 【开户名称】
        if (isset($bank_account_info['account_name']) && $bank_account_info['account_name']) {
            $bank_account_info['account_name'] = static::encrypt($bank_account_info['account_name']);
        }
        // 【银行账号】
        if (isset($bank_account_info['account_number']) && $bank_account_info['account_number']) {
            $bank_account_info['account_number'] = static::encrypt($bank_account_info['account_number']);
        }
        $this->bank_account_info = $bank_account_info;
        return $this;
    }
    /**
     * 【补充材料】 根据实际审核情况，会额外要求商家提供指定的补充资料
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2025-07-29
     * @version 2025-07-29
     * @param array $addition_info
     * @return self
     */
    public function additionInfo(array $addition_info): self
    {

        // 【法人开户承诺函】
        if (isset($addition_info['legal_person_commitment']) && $addition_info['legal_person_commitment']) {
            $addition_info['legal_person_commitment'] = $this->media->uploadImage($addition_info['legal_person_commitment'])['media_id'];
        }
        // 【法人开户意愿视频】
        if (isset($addition_info['legal_person_video']) && $addition_info['legal_person_video']) {
            $addition_info['legal_person_video'] = $this->media->uploadVideo($addition_info['legal_person_video'])['media_id'];
        }
        // 【法人开户意愿视频】
        if (isset($addition_info['business_addition_pics']) && $addition_info['business_addition_pics']) {
            foreach ($addition_info['business_addition_pics'] as &$item) {
                $item = $this->media->uploadImage($item)['media_id'];
            }
        }
        $this->addition_info = $addition_info;
        return $this;
    }
    public function submit(): array
    {
        $chain = '/v3/applyment4sub/applyment/';
        $josn = [
            'business_code' => $this->business_code,
            'contact_info' => $this->contact_info,
            'subject_info' => $this->subject_info,
            'business_info' => $this->business_info,
            'settlement_info' => $this->settlement_info,
            'bank_account_info' => $this->bank_account_info,
        ];
        if ($this->addition_info) $josn += ['addition_info' => $this->addition_info];
        return $this->post($chain, $josn, ['Wechatpay-Serial' => Config::$platformCertificateSerial]);
    }
    public function getStatusByApplymentId($applyment_id)
    {
        $chain = '/v3/applyment4sub/applyment/applyment_id/{applyment_id}';
        return $this->get($chain, [], compact('applyment_id'), ['Wechatpay-Serial' => Config::$platformCertificateSerial]);
    }
}
