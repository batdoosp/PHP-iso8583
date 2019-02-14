<?php
include 'IsoRequest.php';
include 'IsoResponse.php';

header('Content-type:text/html;charset=utf-8');

$iso8583 = new IsoRequest('0800');
$iso8583->setValue(3, '120000');
$iso8583->setValue(11, '000030');
$iso8583->setValue(48, 'ABCDTPTP');
$iso8583->setValue(64, '0000000000000000');

//发送请求
$data = $iso8583->send();
//返回数据
//var_dump($data);

//模拟解析报文
$str = '08002020000000010001120000000030000841424344545054500000000000000000';
echo "返回报文:".$str."<br />";
//解析返回报文
$response = new IsoResponse($str);
$res = $response->decode();
var_dump($res);