<?php
include 'QueryList.class.php';
$type = isset($_GET['type'])?$_GET['type']:null; //json,arr
$session = isset($_GET['session'])?$_GET['session']:null; //RZ04AyEnS4Whk60dqHDrr2w6l72aUzauthRZ04GZ00
$dateRange = isset($_GET['dateRange'])?$_GET['dateRange']:'oneMonth'; //today,sevenDays,oneMonth,threeMonths
$keyValue = isset($_GET['keyValue'])?$_GET['keyValue']:null;
if($session == null){
	exit('NO ALPAY SESSION');
}else{
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://consumeprod.alipay.com/record/advanced.htm?dateRange={$dateRange}&status=success&keyword=generalInfo&keyValue={$keyValue}&dateType=createDate&fundFlow=in&tradeModes=FP&tradeType=TRANSFER&_input_charset=utf-8");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_COOKIE, 'ALIPAYJSESSIONID='.$session);
$cache = mb_convert_encoding(curl_exec($ch), "utf-8", "gb2312");
curl_close($ch);

$reg = array(
        "time" => array(".time .time-d","text"),
        "title" => array(".name .consume-title a","text"),
        "tradeNo" => array(".tradeNo p","text"),
        "name" => array(".other .name","text"),
        "amount" => array(".amount","text"),
        "status" => array(".status p:not([class])","text")
    );
$hj = QueryList::Query($cache,$reg,'.ui-container .ui-record-table tbody tr');
switch ($type){
case 'arr':
    $arr = $hj->jsonArr;
    print_r($arr);
    break;
default:
    $json = $hj->getJSON();
    echo $json;
}
}
?>