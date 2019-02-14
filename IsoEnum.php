<?php


class IsoEnum
{
    //签到 -- 请求
    public static $type0800 = [
        //处理代码
        3   => ['dataType'=>'BCD','length'=>6],
        //合作方流水
        11  => ['dataType'=>'BCD','length'=>6],
        //附带数据
        48  => ['dataType'=>'ASCII','length'=>'LLLVAR'],
        //信息验证码
        64  => ['dataType'=>'BIN','length'=>8]
    ];
    //签到 -- 响应
    public static $type0810 = [
        //处理代码
        3   => ['dataType'=>'BCD','length'=>6],
        //合作方流水
        11  => ['dataType'=>'BCD','length'=>6],
        //交易时间
        12  => ['dataType'=>'BCD','length'=>6],
        //交易日期
        13  => ['dataType'=>'BCD','length'=>4],
        //合作方标识
        32  => ['dataType'=>'BCD','length'=>'LLVAR'],
        //系统参考号
        37  => ['dataType'=>'ASCII','length'=>12],
        //交易的结果代码
        39  => ['dataType'=>'ASCII','length'=>2],
        //更新密钥
        44  => ['dataType'=>'BCD','length'=>'LLVAR']
    ];
    //银行卡消费 -- 请求
    public static $type0200 = [
        //主帐号
        2    => ['dataType'=>'BCD', 'length'=>'LLVAR'],
        //处理代码
        3    => ['dataType'=>'BCD', 'length'=>6],
        //交易金额
        4    => ['dataType'=>'BCD', 'length'=>12],
        //交易传输时间
        7    => ['dataType'=>'BCD', 'length'=>10],
        //POS流水号
        11   => ['dataType'=>'BCD', 'length'=>6],
        //交易时间
        12   => ['dataType'=>'BCD', 'length'=>6],
        //交易日期\
        13   => ['dataType'=>'BCD', 'length'=>4],
        //卡有效期
        14   => ['dataType'=>'BCD', 'length'=>4],
        //POS输入方式
        22   => ['dataType'=>'BCD', 'length'=>3],
        //卡片序列号
        23   => ['dataType'=>'BCD', 'length'=>3],
        //服务点条件码
        25   => ['dataType'=>'BCD', 'length'=>2],
        //二磁道内容
        35   => ['dataType'=>'BCD', 'length'=>'LLVAR'],
        //三磁道内容
        36   => ['dataType'=>'BCD', 'length'=>'LLLVAR'],
        //终端基本信息
        40   => ['dataType'=>'ASCII', 'length'=>'LLLVAR'],
        //终端号
        41   => ['dataType'=>'ASCII', 'length'=>8],
        //商户号
        42   => ['dataType'=>'ASCII', 'length'=>15],
        //附加数据_私有
        48   => ['dataType'=>'ASCII', 'length'=>'LLLVAR'],
        //货币代码
        49   => ['dataType'=>'ASCII', 'length'=>3],
        //个人密码密文
        52   => ['dataType'=>'BCD', 'length'=>16],
        //安全控制信息 有安全要求必选
        53   => ['dataType'=>'BCD', 'length'=>16],
        //IC卡数据域
        55   => ['dataType'=>'BCD', 'length'=>'LLLBCD'],
        60   => ['dataType'=>'ASCII', 'length'=>'LLLVAR'],
        //票据号
        62   => ['dataType'=>'ASCII', 'length'=>'LLLVAR'],
        //消息鉴定码
        64   => ['dataType'=>'BIN', 'length'=>8]
    ];
    //银行卡消费 -- 响应
    public static $type0210 = [
        //主帐号
        2    => ['dataType'=>'BCD', 'length'=>'LLVAR'],
        //处理代码
        3    => ['dataType'=>'BCD', 'length'=>6],
        //交易金额
        4    => ['dataType'=>'BCD', 'length'=>12],
        //POS流水号
        11   => ['dataType'=>'BCD', 'length'=>6],
        //交易时间
        12   => ['dataType'=>'BCD', 'length'=>6],
        //交易日期
        13   => ['dataType'=>'BCD', 'length'=>4],
        //卡有效期
        14   => ['dataType'=>'BCD', 'length'=>4],
        //服务点条件码
        25   => ['dataType'=>'BCD', 'length'=>2],
        //系统参考号
        37   => ['dataType'=>'ASCII', 'length'=>12],
        //授权码
        38   => ['dataType'=>'ASCII', 'length'=>6],
        //返回码
        39   => ['dataType'=>'ASCII', 'length'=>2],
        //终端号
        41   => ['dataType'=>'ASCII', 'length'=>8],
        //商户号
        42   => ['dataType'=>'ASCII', 'length'=>15],
        //附加响应
        44   => ['dataType'=>'ASCII', 'length'=>'LLVAR'],
        48   => ['dataType'=>'ASCII', 'length'=>'LLLVAR'],
        49   => ['dataType'=>'ASCII', 'length'=>3],
        //IC卡数据域
        55   => ['dataType'=>'BCD', 'length'=>'LLLBCD'],
        //保留域
        63   => ['dataType'=>'ASCII', 'length'=>'LLLVAR'],
        //消息鉴定码
        64   => ['dataType'=>'BIN', 'length'=>8]
    ];
}