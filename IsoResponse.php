<?php

include_once 'Iso8583.php';

class IsoResponse extends Iso8583
{
    /**
     * @param string $data 返回报文
     * */
    public function __construct($data)
    {
        $this->dataContent = $data;
    }
    /**
     * 位图转64位二进制格式，用于查询存在域
     *
     * @param string $data 报文
     * @return array
     * */
    private function getBitMap($data)
    {
        $bitMap = [];
        $temp = '';
        for ($i=0; $i<16; $i++) {
            $temp .= sprintf("%04s", base_convert($data[$i], 16, 2));
        }
        for ($i=0; $i<64; $i++) {
            if ($temp[$i] == '1') $bitMap[$i+1] = true;
        }
        return $bitMap;
    }
    /**
     * 报文解码
     * */
    public function decode()
    {
        $data = $this->dataContent;
        //获取消息响应类型
        $messageType = substr($data, 0, 4);
        $this->messageType = $messageType;
        $this->checkMessageType();
        $data = substr($data, 4);

        //设置64bit位的位图信息
        $bitMap = $this->getBitMap($data);
        $data = substr($data, 16);

        //开始域数据解析
        $result = [];
        $typeDomain = $this->typeDomain;
        foreach ($bitMap as $key => $value) {
            if (!array_key_exists($key, $typeDomain)) throw new \Exception($key.'返回报文域不存在', '2000');
            //预设域配置
            $dataDomain = $typeDomain[$key];

            //以下主要是计算 域数据的长度
            //而不同的数据类型，长度占位计算不同
            if ($dataDomain['dataType'] == 'BCD') {
                if (is_numeric($dataDomain['length'])) {
                    $length = $dataDomain['length'];
                    if ($length % 2 != 0) $length++;
                } else if ($dataDomain['length'] == 'LLVAR') {
                    $length = substr($data, 0, 2);
                    $data = substr($data, 2);
                } else if ($dataDomain['length'] == 'LLLVAR') {
                    $length = substr($data, 0, 4);
                    $data = substr($data, 4);
                } else if ($dataDomain['length'] == 'LLLBCD') {
                    //如:55域 特殊情况数据格式为BCD，长度按ASCII方式 除2
                    //因此实际获取数据应乘2
                    $length = substr($data, 0, 4);
                    $data = substr($data, 4);
                    if ($key == 55) $length *= 2;
                }
                if (is_string($length)) $length = (int)ltrim($length, '0');
                $result[$key] = substr($data, 0, $length);
                $data = substr($data, $length);

                //如果长度为奇数，则存在左补码0，需忽略
                if (is_numeric($dataDomain['length']) && $dataDomain['length'] % 2 != 0) $result[$key] = substr($result[$key], 1);
            } else if ($dataDomain['dataType'] == 'ASCII') {
                if (is_numeric($dataDomain['length'])) $length = $dataDomain['length'] * 2;
                else if ($dataDomain['length'] == 'LLVAR') {
                    //计算长度
                    $length = (int)substr($data, 0, 2);
                    $data = substr($data, 2);
                    $length = intval($length) * 2;
                } else if ($dataDomain['length'] == 'LLLVAR') {
                    //计算长度
                    $length = (int)substr($data, 0, 4);
                    $data = substr($data, 4);
                    $length = str_replace('0', '', $length);
                    $length = intval($length) * 2;
                }
                $result[$key] = substr($data, 0, $length);
                $data = substr($data, $length);
                //存在gbk报文，所以在pack处理后，需进行格式转换成utf8
                $result[$key] = pack('H*', $result[$key]);
                $encoding = mb_detect_encoding($result[$key], mb_detect_order(), false);
                if($encoding == "UTF-8") {
                    $result[$key] = mb_convert_encoding($result[$key], 'UTF-8', 'gbk');
                }
                $result[$key] = iconv(mb_detect_encoding($result[$key], mb_detect_order(), false), "UTF-8//IGNORE", $result[$key]);
            } else if ($dataDomain['dataType'] == 'BIN') {
                $length = $dataDomain['length'] * 4;
                $result[$key] = substr($data, 0, $length);
                //特殊情况16个0，则纯做拼接操作
                if ($result[$key] != '0000000000000000') $result[$key] = pack('H*', $result[$key]);
                $data = substr($data, $length);
            }
        }
        return $result;
    }
}