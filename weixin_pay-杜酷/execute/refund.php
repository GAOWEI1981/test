<?php
/**
 * 退款申请接口-demo
 * ====================================================
 * 注意：同一笔单的部分退款需要设置相同的订单号和不同的
 * out_refund_no。一笔退款失败后重新提交，要采用原来的
 * out_refund_no。总退款金额不能超过用户实际支付金额(现
 * 金券金额不能退款)。
*/
	if(!isset($_SESSION)) session_start();
	include_once("../WxPayPubHelper/WxPayPubHelper.php");
	include_once("../../function/config.php");
	
	if($Database->Connect()==false)
	{
		echo "db connect false!";
		return;
	}
	mysql_select_db($DatabaseName);
	
	/*print_r($_GET);
	echo "<br>";
	print_r($_POST);
	echo "<br>";*/

	//输入需退款的订单号
	$item=GetItemA("account_weixin_payment","id",$_GET[PaymentID]);//得到transaction_id
	$transaction_id=$item[transaction_id];


	//申请退款的条目
	$sql="select * from account_weixin_payment where state='refund_apply' and transaction_id='{$transaction_id}'";
	$result=mysql_query($sql);
	$RefundInfo=mysql_fetch_array($result);//退款申请记录
	$RefundInfo[amount]=$_POST[RefundValue]*100*-1;//申请退款金额
	$sql="update account_weixin_payment set amount='{$RefundInfo[amount]}' where transaction_id='{$transaction_id}' and state='refund_apply'";
	$r=mysql_query($sql);//记录退款金额
	//*******************
	
	//最初支付的条目
	$sql="select * from account_weixin_payment where state='ok' and transaction_id='{$transaction_id}'";
	$result=mysql_query($sql);
	$PaymentInfo=mysql_fetch_array($result);
	//**************************
	
	//LogInFile(count($RefundInfo)."<>".count($PaymentInfo),"refund.txt");
	if(count($PaymentInfo)==0 || count($RefundInfo)==0)
	{
		echo "<response>账目信息不全：退款失败</response>";
		retrun;
	}
	//echo $sql;echo "<br>";
	//print_r($OrderInfo);
	$out_trade_no = $PaymentInfo[id];
	$refund_fee = $RefundInfo[amount]*-1;
	/*echo $refund_fee;echo "<br>";
	print_r($RefundInfo);
	echo "<br>";*/
	//商户退款单号，商户自定义，此处仅作举例
	$out_refund_no = $PaymentInfo[id];//提交申请的条目
	//总金额需与订单号out_trade_no对应，demo中的所有订单的总金额为1分
	$total_fee = $PaymentInfo[amount];
	
	//使用退款接口
	$refund = new Refund_pub();
	//设置必填参数
	//appid已填,商户无需重复填写
	//mch_id已填,商户无需重复填写
	//noncestr已填,商户无需重复填写
	//sign已填,商户无需重复填写
	$refund->setParameter("out_trade_no","$out_trade_no");//商户订单号
	$refund->setParameter("out_refund_no","$out_refund_no");//商户退款单号
	$refund->setParameter("total_fee",$total_fee);//总金额
	$refund->setParameter("refund_fee",$refund_fee);//退款金额
	$refund->setParameter("op_user_id",WxPayConf_pub::MCHID);//操作员
	//非必填参数，商户可根据实际情况选填
	//$refund->setParameter("sub_mch_id","XXXX");//子商户号 
	//$refund->setParameter("device_info","XXXX");//设备号 
	//$refund->setParameter("transaction_id","XXXX");//微信订单号
	
	//调用结果
	$refundResult = $refund->getResult();
	
	//商户根据实际情况设置相应的处理流程,此处仅作举例
	if ($refundResult["return_code"] == "FAIL")
	{
		echo "通信出错：".$refundResult['return_msg']."<br>";
		echo "<response>FAIL:退款失败</response>";
	}
	else
	{
		/*echo "业务结果：".$refundResult['result_code']."<br>";
		echo "错误代码：".$refundResult['err_code']."<br>";
		echo "错误代码描述：".$refundResult['err_code_des']."<br>";
		echo "公众账号ID：".$refundResult['appid']."<br>";
		echo "商户号：".$refundResult['mch_id']."<br>";
		echo "子商户号：".$refundResult['sub_mch_id']."<br>";
		echo "设备号：".$refundResult['device_info']."<br>";
		echo "签名：".$refundResult['sign']."<br>";
		echo "微信订单号：".$refundResult['transaction_id']."<br>";
		echo "商户订单号：".$refundResult['out_trade_no']."<br>";
		echo "商户退款单号：".$refundResult['out_refund_no']."<br>";
		echo "微信退款单号：".$refundResult['refund_idrefund_id']."<br>";
		echo "退款渠道：".$refundResult['refund_channel']."<br>";
		echo "退款金额：".$refundResult['refund_fee']."<br>";
		echo "现金券退款金额：".$refundResult['coupon_refund_fee']."<br>";*/
		$content = "业务结果：".$refundResult['result_code']."\r\n";
		$content.= "错误代码：".$refundResult['err_code']."\r\n";
		$content.= "错误代码描述：".$refundResult['err_code_des']."\r\n";
		$content.= "公众账号ID：".$refundResult['appid']."\r\n";
		$content.= "商户号：".$refundResult['mch_id']."\r\n";
		$content.= "子商户号：".$refundResult['sub_mch_id']."\r\n";
		$content.= "设备号：".$refundResult['device_info']."\r\n";
		$content.= "签名：".$refundResult['sign']."\r\n";
		$content.= "微信订单号：".$refundResult['transaction_id']."\r\n";
		$content.= "商户订单号：".$refundResult['out_trade_no']."\r\n";
		$content.= "商户退款单号：".$refundResult['out_refund_no']."\r\n";
		$content.= "微信退款单号：".$refundResult['refund_idrefund_id']."\r\n";
		$content.= "退款渠道：".$refundResult['refund_channel']."\r\n";
		$content.= "退款金额：".$refundResult['refund_fee']."\r\n";
		$content.= "现金券退款金额：".$refundResult['coupon_refund_fee']."\r\n";
		$content.= "************************\r\n";

		
		//$sql="update account_weixin_payment set state='refund' where id='{$OrderInfo[id]}'";
		//mysql_query($sql);
		LogInFile("总额：".$total_fee."<>退款：".$refund_fee,"RefundInfo.txt");
		LogInFile($content,"RefundInfo.txt");
		if(strlen($refundResult['out_refund_no'])>0)
		{
			$time=time();
			$id=$refundResult['out_refund_no']."_REFUND";
			
			$sql="insert into account_weixin_payment
				(transaction_id,id,user_open_id,user_id,amount,state,time,expense_id,notify_data,remark) 
				values('{$RefundInfo['transaction_id']}','{$id}','{$RefundInfo['user_open_id']}','{$RefundInfo['user_id']}','{$RefundInfo[amount]}',
				'refund_approve','{$time}','{$RefundInfo[expense_id]}','{$content}','{$_POST[remark]}')";
			//LogInFile($sql,"refund.txt");
			
			mysql_query($sql);
			$url="{$_SESSION[ReturnUrl]}&err={$refundResult['result_code']}-退款成功";
			header("Location:{$url}");
		}
		else
		{
			$url="{$_SESSION[ReturnUrl]}&err={$refundResult['result_code']}-退款失败 {$refundResult['err_code_des']}";
			header("Location:{$url}");
			//echo "<response>}</response>";
		}
		//$sql="update account_weixin_payment set state='refund-approve',amount='-{$OrderInfo[amount]}' where id='{$_GET[PaymentID]}'";
		//mysql_query($sql);
		//print_r($_SESSION);
		//$new = serialize($stooges);
		//print_r(unserialize($new));
	}
	
?>
