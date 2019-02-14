<?php

include_once 'Iso8583.php';
include_once 'IsoHttp.php';

class IsoRequest extends Iso8583
{
    /**
     * IsoRequest constructor.
     * @param string $messageType 消息类型
     * @throws Exception
     */
    public function __construct($messageType)
    {
        //设置报文消息类型
        $this->messageType = $messageType;
        $this->checkMessageType();
    }
    /**
     * 域数据添加
     *
     * @param integer $index 域下标
     * @param string  $value 域值
     * @throws \Exception 域下标未预设存在
     * */
    public function setValue($index, $value)
    {
        //检查预设域是否存在
        if (!array_key_exists($index, $this->typeDomain))
            throw new \Exception($index.'域不存在', '20002');
        $this->dataMap[$index] = $value;
    }
    /*
     * 位图数据封装
     * */
    private function getBitMap()
    {
        $bitMap = '';
        $dataMap = $this->dataMap;
        //轮询判断64个域中的存在域
        for ($i=1; $i<=64; $i++) $bitMap .= (array_key_exists($i, $dataMap)) ? '1' : '0';
        $hexBitMap = '';
        //每四位二进制转十六进制
        for ($i=0; $i<64; $i+=4) $hexBitMap .= base_convert(substr($bitMap, $i, 4), 2, 16);
        return $hexBitMap;
    }
    /**
     * 长度计算、内容格式转换 拼接
     *
     * @param string  $dataType         域类型
     * @param string  $templateLength   域长度
     * @param integer $index            传入域下标
     * @param string  $value            传入域值
     * @return string
     * @throws
     * */
    private function calculate($dataType, $templateLength, $index, $value)
    {
        if ($dataType == 'BCD') {
            $length = strlen($value);
            //判断长度是否固定 且满足预设条件
            if (is_numeric($templateLength)) {
                $value = sprintf("%0{$templateLength}s", $value);
                if (strlen($value) % 2 != 0) $value = '0'.$value;
            }
            $strLength = '';
            if ($templateLength == 'LLVAR') {
                if ($length > 99) throw new \Exception($index.'域长度溢出');
                $strLength = sprintf("%2s", strlen($value));
                //数据长度为奇数，需右补码0
                if ($length % 2 != 0) $value = $value.'0';
            } else if ($templateLength == 'LLLVAR') {
                if ($length > 999) throw new \Exception($index.'域长度溢出');
                $strLength = sprintf("%04s", strlen($value));
                //数据长度为奇数，需右补码0
                if ($length % 2 != 0) $value = $value.'0';
            } else if ($templateLength == 'LLLBCD') {
                if ($length > 999) throw new \Exception($index.'域长度溢出');
                //如:55域为ic域，长度按ASCII 需除2处理，内容按BCD直接拼接
                $strLength = sprintf("%04s", strlen($value) / 2);
            }
            $value = $strLength.$value;
        } else if ($dataType == 'ASCII') {
            $value = bin2hex($value);
            $length = strlen($value) / 2;
            $strLength = '';
            if (is_numeric($templateLength)) {
                //数据长度为奇数，需左补码0
                if (strlen($value) % 2 != 0) $value = '0'.$value;
            } else if ($templateLength == 'LLVAR') {
                if ($length > 99) throw new \Exception($index.'域长度溢出');
                $strLength = sprintf("%02s", $length);
            } else if ($templateLength == 'LLLVAR') {
                if ($length > 999) throw new \Exception($index.'域长度溢出');
                $strLength = sprintf("%04s", $length);
            }
            $value = $strLength.$value;
        } else if ($dataType == 'BIN') {
            //目前接口 BIN 类型无LLVAR 和 LLLVAR
            //$length = strlen($value) / 2;
            if (!is_numeric($value)) {
                $arr = unpack('H*', $value);
                $value = substr($arr[1], 0, $templateLength * 2);
            }
        }
        return $value;
    }
    /**
     * 数据格式化
     * */
    public function format()
    {
        //域定义模版
        $typeDomain = $this->typeDomain;
        //已设置数据
        $dataMap = $this->dataMap;
        ksort($dataMap);
        $content = $this->messageType.$this->getBitMap();
        //已设置的数据 针对模版 进行格式转换
        foreach ($dataMap as $key => $value) {
            if ($key == 64 && $value == '0000000000000000') continue;
            $template = $typeDomain[$key];  //指定域模版
            //获取转换后的域数据
            $result = $this->calculate($template['dataType'], $template['length'], $key, $value);
            $content .= $result;
        }
        $this->dataContent = strtoupper($content);
    }
    /**
     * 获取明文数据
     * */
    public function getDataMap()
    {
        return $this->dataMap;
    }
    /**
     * 获取格式化拼装后的数据
     * */
    public function getDataContent()
    {
        return $this->dataContent;
    }
    /**
     * 发送数据
     * @return array
     * @throws
     * */
    public function send()
    {
        //设置通信传输模式
        $this->format();
        $content = $this->dataContent;

        echo '发送报文:'.$content."<br />";

        //请求URL地址
        $url = '';
        $result = IsoHttp::dataSend($url, $content);
        //返回结果
        return $result;
    }
}