<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<link href="../function/InterfaceStyle.css" rel="stylesheet" type="text/css" />
<title>支付信息</title>
<style type="text/css">
<!--
.STYLE1 {font-size: x-small}
-->
</style>
</head>
<body class="BodyNoSpace">
<?php
include_once("../WxPayPubHelper/WxPayPubHelper.php");

	$bill_date = ”20170318“;

	//使用对账单接口
	$downloadBill = new DownloadBill_pub();
	//设置对账单接口参数
	//设置必填参数
	//appid已填,商户无需重复填写
	//mch_id已填,商户无需重复填写
	//noncestr已填,商户无需重复填写
	//sign已填,商户无需重复填写
	//使用对账单接口
	$downloadBill = new DownloadBill_pub();
	//设置对账单接口参数
	//设置必填参数
	//appid已填,商户无需重复填写
	//mch_id已填,商户无需重复填写
	//noncestr已填,商户无需重复填写
	//sign已填,商户无需重复填写
	$downloadBill->setParameter("bill_date","$bill_date");//对账单日期 
	$downloadBill->setParameter("bill_type","ALL");//账单类型 
	//非必填参数，商户可根据实际情况选填
	//$downloadBill->setParameter("device_info","XXXX");//设备号  
	
	//对账单接口结果
	$downloadBillResult = $downloadBill->getResult();
	echo $downloadBillResult['return_code'];
	
	if ($downloadBillResult['return_code'] == "FAIL") {
		echo "通信出错：".$downloadBillResult['return_msg'];
	}else{
		 print_r('<pre>');
		 echo "【对账单详情】"."</br>";
		 print_r($downloadBill->response);
		 print_r('</pre>');
	}

?>
</body>
</html>
<script>
</script>