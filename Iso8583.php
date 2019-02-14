<?php

include_once 'IsoEnum.php';

/*
 * 默认为64域报文
 * */
abstract class Iso8583
{
    //信息类型
    protected $messageType;
    //预设域区间
    protected $typeDomain;
    //添加数据
    protected $dataMap = [];
    //组装后的十六进制数据
    protected $dataContent;

    protected function checkMessageType()
    {
        //判断Iso报文类型是否正确
        switch ($this->messageType) {
            case '0800':    //平台重置密钥请求
                $this->typeDomain = IsoEnum::$type0800;
                break;
            case '0810':    //平台重置密钥响应
                $this->typeDomain = IsoEnum::$type0810;
                break;
            case '0200':
                $this->typeDomain = IsoEnum::$type0200;
                break;
            case '0210':
                $this->typeDomain = IsoEnum::$type0210;
                break;
            default:
                throw new \Exception('Iso消息类型错误', '20001');
        }
    }
}