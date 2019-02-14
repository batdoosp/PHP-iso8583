<?php

class IsoHttp
{
    /**
     * 请求方法
     *
     * @param string $url 请求URL地址
     * @param string $in  请求报文
     * @return array
     * */
    public static function dataSend($url, $in)
    {
        $in = pack( "H*", $in);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $in);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type:application/octet-stream',
                'Content-Length: ' . strlen($in))
        );
        $out = curl_exec($ch);
        $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        if ($httpCode == 200) {
            $out = unpack('H*', $out);
            if (is_array($out) && !empty($out[1])) {
                return ['00', $out[1]];
            } return ['01', '请求失败'];
        } else {
            return ['02', '请求失败'];
        }
    }
}