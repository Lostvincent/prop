<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages.
    |
    */

    'accepted'             => ':attribute 必须接受。',
    'active_url'           => ':attribute 不是一个有效的网址。',
    'after'                => ':attribute 必须要晚于 :date。',
    'after_or_equal'       => ':attribute 必须要等于 :date 或更晚。',
    'alpha'                => ':attribute 只能由字母组成。',
    'alpha_dash'           => ':attribute 只能由字母、数字和斜杠组成。',
    'alpha_num'            => ':attribute 只能由字母和数字组成。',
    'array'                => ':attribute 必须是一个数组。',
    'before'               => ':attribute 必须要早于 :date。',
    'before_or_equal'      => ':attribute 必须要等于 :date 或更早。',
    'between'              => [
        'numeric' => ':attribute 必须介于 :min - :max 之间。',
        'file'    => ':attribute 必须介于 :min - :max kb 之间。',
        'string'  => ':attribute 必须介于 :min - :max 个字符之间。',
        'array'   => ':attribute 必须只有 :min - :max 个单元。',
    ],
    'boolean'              => ':attribute 必须为布尔值。',
    'confirmed'            => ':attribute 两次输入不一致。',
    'date'                 => ':attribute 不是一个有效的日期。',
    'date_format'          => ':attribute 的格式必须为 :format。',
    'different'            => ':attribute 和 :other 必须不同。',
    'digits'               => ':attribute 必须是 :digits 位的数字。',
    'digits_between'       => ':attribute 必须是介于 :min 和 :max 位的数字。',
    'dimensions'           => ':attribute 图片尺寸不正确。',
    'distinct'             => ':attribute 已经存在。',
    'email'                => ':attribute 不是一个合法的邮箱。',
    'idcard'               => ':attribute 格式不正确。',
    'verify_code'          => '短信验证码错误。',
    'mobile'               => ':attribute 格式不正确。',
    'exists'               => ':attribute 不存在。',
    'file'                 => ':attribute 必须是文件。',
    'filled'               => ':attribute 不能为空。',
    'image'                => ':attribute 必须是图片。',
    'in'                   => '已选的属性 :attribute 非法。',
    'in_array'             => ':attribute 没有在 :other 中。',
    'integer'              => ':attribute 必须是整数。',
    'ip'                   => ':attribute 必须是有效的 IP 地址。',
    'ipv4'                 => ':attribute 必须是有效的 IPv4 地址。',
    'ipv6'                 => ':attribute 必须是有效的 IPv6 地址。',
    'json'                 => ':attribute 必须是正确的 JSON 格式。',
    'max'                  => [
        'numeric' => ':attribute 不能大于 :max。',
        'file'    => ':attribute 不能大于 :max kb。',
        'string'  => ':attribute 不能大于 :max 个字符。',
        'array'   => ':attribute 最多只有 :max 个单元。',
    ],
    'mimes'                => ':attribute 必须是一个 :values 类型的文件。',
    'mimetypes'            => ':attribute 必须是一个 :values 类型的文件。',
    'min'                  => [
        'numeric' => ':attribute 必须大于等于 :min。',
        'file'    => ':attribute 大小不能小于 :min kb。',
        'string'  => ':attribute 至少为 :min 个字符。',
        'array'   => ':attribute 至少有 :min 个单元。',
    ],
    'not_in'               => '已选的属性 :attribute 非法。',
    'numeric'              => ':attribute 必须是一个数字。',
    'present'              => ':attribute 必须存在。',
    'regex'                => ':attribute 格式不正确。',
    'required'             => ':attribute 不能为空。',
    'required_if'          => '当 :other 为 :value 时 :attribute 不能为空。',
    'required_unless'      => '当 :other 不为 :value 时 :attribute 不能为空。',
    'required_with'        => '当 :values 存在时 :attribute 不能为空。',
    'required_with_all'    => '当 :values 存在时 :attribute 不能为空。',
    'required_without'     => '当 :values 不存在时 :attribute 不能为空。',
    'required_without_all' => '当 :values 都不存在时 :attribute 不能为空。',
    'same'                 => ':attribute 和 :other 必须相同。',
    'size'                 => [
        'numeric' => ':attribute 大小必须为 :size。',
        'file'    => ':attribute 大小必须为 :size kb。',
        'string'  => ':attribute 必须是 :size 个字符。',
        'array'   => ':attribute 必须为 :size 个单元。',
    ],
    'string'               => ':attribute 必须是一个字符串。',
    'timezone'             => ':attribute 必须是一个合法的时区值。',
    'unique'               => ':attribute 已经存在。',
    'uploaded'             => ':attribute 上传失败。',
    'url'                  => ':attribute 格式不正确。',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention 'attribute.rule' to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom'               => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of 'email'. This simply helps us make messages a little cleaner.
    |
    */

    'attributes'           => [
        'available'                 =>  '可用的',
        'avatar'                    =>  '头像',

        'buyer_id'                  =>  '买家ID',

        'category_id'               =>  '分类ID',
        'city'                      =>  '城市',
        'content'                   =>  '内容',
        'country'                   =>  '国家',

        'date'                      =>  '日期',
        'day'                       =>  '天',
        'description'               =>  '描述',

        'email'                     =>  '邮箱',
        'ep'                        =>  '剧集',
        'ep_id'                     =>  '剧集ID',

        'first_name'                =>  '名',

        'grade'                     =>  '评分',
        'gender'                    =>  '性别',

        'hour'                      =>  '时',

        'image'                     =>  '图片',
        'images'                    =>  '图片',

        'length'                    =>  '持续时间',
        'link'                      =>  '链接',

        'minute'                    =>  '分',
        'mobile'                    =>  '手机号',
        'month'                     =>  '月',

        'name'                      =>  '名称',
        'new_password'              =>  '新密码',
        'new_password_confirmation' =>  '确认新密码',

        'object_id'                 =>  '对象ID',
        'object_type'               =>  '对象类型',
        'old_password'              =>  '旧密码',

        'password'                  =>  '密码',
        'password_confirmation'     =>  '确认密码',
        'phone'                     =>  '电话',
        'pic'                       =>  '图片',
        'pic_id'                    =>  '图片ID',
        'prop'                      =>  '道具',
        'prop_id'                   =>  '道具ID',
        'province'                  =>  '省份',

        'reason'                    =>  '原因',

        'status'                    =>  '状态',
        'sec'                       =>  '秒',
        'second'                    =>  '秒',
        'sequence'                  =>  '排序',
        'sex'                       =>  '性别',
        'size'                      =>  '大小',
        'slug'                      =>  '调用标识符',
        'subject'                   =>  '番剧',
        'subject_id'                =>  '番剧ID',

        'time'                      =>  '时间',
        'time_range'                =>  '时间范围',
        'title'                     =>  '标题',
        'type'                      =>  '类型',

        'uri'                       =>  '通用资源识别号',
        'user_id'                   =>  '用户ID',
        'username'                  =>  '用户名',

        'year'                      =>  '年',
        'years'                     =>  '年数',

    ],

];
