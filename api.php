<?php
/*!
@name:Myurl API
@description:Myurl接口文件
@author:墨渊 
@version:1.2
@time:2017-11-14
@copyright:优启梦&墨渊
*/
include './includes/api.inc.php';
$longurl = (isset($_GET['url'])) ?$_GET['url']:$_POST['url'];
$format = (isset($_GET['format'])) ?$_GET['format']:$_POST['format'];

if(!$longurl){
	show_result(0,"the url cannot be empty",10001);
  	exit();
}
if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$longurl)) {
	show_result(0,"url is incorrect",10002);
  	exit();
}
//查域名是否报毒

$result =curl_get('https://api.yum6.cn/qqurls1.php?url='.$longurl,1);
$arr=json_decode($result,true);
$result =$arr['whitetype'];
if ($result === 2) {
	//1未知 2报毒 3绿标 4腾讯域名
	$jump = curl_get($fh_api.'/dwz.php?longurl='.$longurl);
	$arr=json_decode($jump,true);
	$longurl = $arr['ae_url'];
}

$uid=shorturl($longurl);
$myrow=$DB->get_row("select * from wjoy_log where longurl='".base64_encode($longurl)."' limit 1");
if(!$myrow){
	//不存在
	$sql=$DB->query("insert into `wjoy_log` (`uid`,`longurl`) values ('".$uid."','".base64_encode($longurl)."')");
	if($sql){
	    show_result($uid,"success",1);
	}else{
	    show_result(0,"failure",10003);
	}
	
}else{
	//存在
	show_result($uid,"existence",1);
}

$DB->close();

function show_result($code,$msg,$result){
	global $format;
	if ($format === 'txt') {
		if ($code === 0 ){
			echo $msg;
		}else{
			echo $code;
		}
	}else{
		$result=array("code"=>$code,"msg"=>$msg,"result"=>$result);
		echo json_encode($result);
	}

}