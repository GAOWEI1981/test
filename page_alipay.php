<?php
/* *
 * 功能：支付宝手机网站支付接口(alipay.trade.wap.pay)接口调试入口页面
 * 版本：2.0
 * 修改日期：2016-11-01
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 请确保项目文件有可写权限，不然打印不了日志。
 */

header("Content-type: text/html; charset=utf-8");
include_once("../function/config.php");


include_once("../alipay/normal/wappay/service/AlipayTradeService.php");
//echo "sdfsdfsd<br>";
//require_once dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'buildermodel/AlipayTradeWapPayContentBuilder.php';
include_once("../alipay/normal/wappay/buildermodel/AlipayTradeWapPayContentBuilder.php");
//require dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'./../config.php';
include_once("config_alipay.php");
//echo "sdfsdfsd<br>";


//return;

if($Database->Connect()==false)
{
	echo "db connect false!";
	return;
}
mysql_select_db($DatabaseName);

//print_r($_POST);
$operate=$_GET[operate];
switch($operate)
{
case "":
	//print_r($_GET);
	$sql="select * from account_book where OrderID='{$_GET[OrderID]}' and (title='服装' or title='邮费')";
	
	$result=mysql_query($sql);
	//$item=mysql_fetch_array($result);
	$gross=0;$CountGross=0;
	while($row=mysql_fetch_array($result))
	{
		if($row[type]=="loan" || $row[type]=="Loan")
		{
			//print_r($row);echo "<br>";
			$CountGross+=$row[count];
			$gross+=$row[price]*$row[count];
		}
		//echo $money;echo "<br>";
	}
	if($gross>0)
	{
		//return;
		$item=GetItemA("orders","ID",$_GET[OrderID]);
	
		//商户订单号，商户网站订单系统中唯一订单号，必填
		$out_trade_no = $_GET[OrderID];
	
		//订单名称，必填
		$subject = $item[title];
	
		//付款金额，必填
		$total_amount = $gross;
	
		//商品描述，可空
		$body = "条目总数：{$CountGross}";
	
		//超时时间
		$timeout_express="1m";
	
		$payRequestBuilder = new AlipayTradeWapPayContentBuilder();
		$payRequestBuilder->setBody($body);
		$payRequestBuilder->setSubject($subject);
		$payRequestBuilder->setOutTradeNo($out_trade_no);
		$payRequestBuilder->setTotalAmount($total_amount);
		$payRequestBuilder->setTimeExpress($timeout_express);
	
		$payResponse = new AlipayTradeService($config);
		$result=$payResponse->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);
	}
	else echo "已经支付完成！";

    return ;
}
?>
<!DOCTYPE html>
<html>
	<head>
	<title>支付宝手机网站支付接口</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body text=#000000 bgColor="#ffffff" leftMargin=0 topMargin=4>
</body>
<script language="javascript">
</script>
</html>