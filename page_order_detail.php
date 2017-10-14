<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<title>订单详情</title>
<link rel="stylesheet" href="../function/industry.css"/>
<link rel="stylesheet" href="../function/weui/dist/style/weui.css"/>
<link rel="stylesheet" href="../function/weui/dist/style/weui.min.css"/>
</head>
<body class="BodyNoSpace">
<?
ob_start();
error_reporting(E_ALL & ~E_NOTICE);
if(!isset($_SESSION)) session_start();
include_once("../function/config.php");
include_once("../function/JSSDK.php");
include_once("../function/Script.php");
include_once("weixin_pay/WxPayPubHelper/WxPayPubHelper.php");

$operate=$_GET['operate'];
//**************/
//echo $operate;
$FileName=basename($_SERVER["PHP_SELF"]);
if($Database->Connect()==false)
{
	echo "db connect false!";
	return;
}

mysql_select_db($DatabaseName);
//先获取支付state和code
$xml = $GLOBALS['HTTP_RAW_POST_DATA'];//支付确认通知
if(strlen($xml)>0)
{
	//LogInFile($xml,"PayLog.txt");
	$PaymentNotify = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
	//LogInFile($PaymentNotify->result_code,"PayLog.txt");
	
	
	$id=$PaymentNotify->out_trade_no;
	$transaction_id=$PaymentNotify->transaction_id;
	$time=strtotime($PaymentNotify->time_end);
	//"<>".$PaymentNotify->transaction_id."<>".$PaymentNotify->total_fee
	
	
	if(strlen($id)>0 && $PaymentNotify->result_code=="SUCCESS")
	{
		$sql="update account_book set sys_payment='weixin',type='',remark='{$xml}',pay_time='{$time}'  where OrderID='{$id}'";
		LogInFile($sql,"PayLog.txt");
		mysql_query($sql);
		/*$sql="update account_weixin_payment set app_id='{$PaymentNotify->appid}'  where id='{$id}'";
		mysql_query($sql);
		//一旦完成支付就要锁定个人信息
		$item=GetItemA("account_weixin_payment","id",$id);
		$user_id=$item[user_id];
		$sql="update signup_users set state='lock' where openid='{$user_id}'";
		//echo $sql;
		mysql_query($sql);*/
		
		//******************
	}
	return;
}
//print_r($_GET);echo "<br>";
$ReturnUrl=dirname($_SERVER[SCRIPT_URI])."/{$FileName}";//返回的链接
switch($operate)
{
case "PayExe":
	//print_r($_GET);echo "<br>";
	
	if(strlen($_GET[OrderID])>0)
	{
		if(strlen($_GET[shipper])>0)//记录用户名称
		{
			$sql="update signup_users set name='{$_GET[shipper]}' where openid='{$_SESSION[adminID]}'";
			mysql_query($sql);
		}
		if(strlen($_GET[remark])>0)//记录用户名称
		{
			$sql="update orders set remark='{$_GET[remark]}' where ID='{$_GET[OrderID]}'";
			mysql_query($sql);
		}
		$_SESSION[LastPage]="";
		//$WeiXinPayUrl="http://weixin.ghostinmetal.com/weixin_pay_service/execute/js_api_call.php?ReturnUrl={$ReturnUrl}&OutState={$_GET[OrderID]}";
		$WeiXinPayUrl="http://lanxiao.ghostinmetal.com/WeixinPayService/execute/js_api_call.php?ReturnUrl={$ReturnUrl}&OutState={$_GET[OrderID]}";
		//echo $WeiXinPayUrl;echo "<br>";
		Header("Location:{$WeiXinPayUrl}");
		return;
	}
	return;
}
$BackUrl="page_orders_user.php";
$Icon="../icon/icon_pay.png";
include_once("chunks/chunk_back_bar.php");
//include_once("chunks/chunk_title.php");


if(strlen($_GET[OutState])>0)//获得了支付的返回参数
{
	
	$_GET[OrderID]=$_GET[OutState];
	//print_r(GetItemA("orders","ID",$_GET[OrderID]));echo "<br>";
	include_once("chunks/chunk_order_detail.php");
	$NotifyUrl=$ReturnUrl;
	$PageAfterPay="page_order_detail.php?OrderID={$_GET[OrderID]}";
	include_once("chunks/chunk_weixin_order_pay.php");
	echo "<script>callpay();</script>";
	return;
	
}
else
{
	include_once("chunks/chunk_order_detail.php");
}
/*

*/
/*
if(strlen($_SESSION[LastPage])>0)
{
	//print_r($_SESSION);echo "<br>";
	//return;
	//echo "eeeeee<br>";
	$url=$_SESSION[LastPage];
	$_SESSION[LastPage]="";
	Header("Location:{$url}");
}
else
{
	//echo "fffff<br>";
	$_SESSION[LastPage]="page_orders_user.php";
	$_GET[OrderID]=$_GET[OutState];
}*/



//$NotifyUrl=$ReturnUrl;
//echo $NotifyUrl;echo "<br>";


/*$NotifyUrl=$ReturnUrl;
$PageAfterPay="page_orders_user.php";
include_once("chunks/chunk_weixin_order_pay.php");*/
?>
</body>
</html>
<script>
var err="<?echo $_GET[err];?>";
if(err.length>0) alert(err);
</script>