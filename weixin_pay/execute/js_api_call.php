<?php
/**
 * JS_API支付demo
 * ====================================================
 * 在微信浏览器里面打开H5网页中执行JS调起支付。接口输入输出数据格式为JSON。
 * 成功调起支付需要三个步骤：
 * 步骤1：网页授权获取用户openid
 * 步骤2：使用统一支付接口，获取prepay_id
 * 步骤3：使用jsapi调起支付
*/
	error_reporting(E_ALL & ~E_NOTICE);
	if(!isset($_SESSION)) session_start();
	
	include_once("../../function/config.php");
	include_once("../../function/JSSDK.php");
	include_once("../../function/Script.php");
	include_once("../WxPayPubHelper/WxPayPubHelper.php");
	
	
	$FileName=basename($_SERVER["PHP_SELF"]);
	if($Database->Connect()==false)
	{
		echo "db connect false!";
		return;
	}
	mysql_select_db($DatabaseName);
	//使用jsapi接口
	$jsApi = new JsApi_pub();

	//=========步骤1：网页授权获取用户openid============
	//通过code获得openid
	if (!isset($_GET['code']))
	{
		//触发微信返回code码
		print_r($_GET);
		$url = $jsApi->createOauthUrlForCode(WxPayConf_pub::JS_API_CALL_URL);
		$time=time();
		$id=$time."_".CreateID(10);
		$sql="insert into skip_records (id,target_url,out_state,time) values('{$id}','{$_GET[ReturnUrl]}','{$_GET[OutState]}','{$time}')";//储存跳转地址
		//echo $sql;echo "<br>";
		mysql_query($sql);
		$url=str_replace("STATE#wechat_redirect",$id,$url);
		
		Header("Location: $url"); 
	}else
	{
		//跳转到指定的页面
		//print_r($_GET);echo "<br>";
		//print_r($_POST);echo "<br>";
		$item=GetItemA("skip_records","id",$_GET[state]);
		$url="{$item[target_url]}?code={$_GET[code]}&OutState={$item[out_state]}&state={$_GET[state]}";
		
		$keys=array_keys($_GET);
		foreach($keys as $key)
		{
			LogInFile("{$key}:{$_GET[$key]}","params.txt");
		}
		LogInFile(">>>>>>>>>","params.txt");
		Header("Location:{$url}");
	    /*$code = $_GET['code'];
		$jsApi->setCode($code);
		$openid = $jsApi->getOpenId();
		//echo $openid."<br>";
		//echo dirname(__FILE__)."<br>";*/
	}
	
	
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <title>微信安全支付</title>
</head>
<body>

</body>
</html>