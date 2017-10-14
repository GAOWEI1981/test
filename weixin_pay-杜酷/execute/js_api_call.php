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

	include_once("../WxPayPubHelper/WxPayPubHelper.php");
	include_once("../../function/config.php");
	
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
		$url = $jsApi->createOauthUrlForCode(WxPayConf_pub::JS_API_CALL_URL);
		$url=str_replace("STATE#wechat_redirect",$_GET[PaymentID],$url);
		//print_r($_GET);
		//$url.="&state=111";
		//return;
		Header("Location: $url"); 
	}else
	{
		//获取code码，以获取openid
		//print_r($_GET);
	    $code = $_GET['code'];
		$jsApi->setCode($code);
		$openid = $jsApi->getOpenId();
		//echo $openid."<br>";
		//echo dirname(__FILE__)."<br>";
		$PaymentInfo=GetItemA("account_weixin_payment","id",$_GET['state']);
		$ExpenseInfo=GetItemA("signup_expense_items","id",$PaymentInfo['expense_id']);
		//print_r($_GET);
		//print_r($ExpenseInfo);return;
		$NotifyUrl=$PaymentInfo['notify_url'];
		//$LastPage="../../sys_sign_up/{$LastPage}";
		//$LastPage="http://www.90worldengine.com/sys_sign_up/PaymentPage.php";
		$ExpenseName=$ExpenseInfo['name'];
		$Price=$PaymentInfo['amount'];
		
		//echo $NotifyUrl;return;
		
		
	}
	
	//=========步骤2：使用统一支付接口，获取prepay_id============
	//使用统一支付接口
	$unifiedOrder = new UnifiedOrder_pub();
	
	//设置统一支付接口参数
	//设置必填参数
	//appid已填,商户无需重复填写
	//mch_id已填,商户无需重复填写
	//noncestr已填,商户无需重复填写
	//spbill_create_ip已填,商户无需重复填写
	//sign已填,商户无需重复填写
	$unifiedOrder->setParameter("openid","$openid");//商品描述
	$unifiedOrder->setParameter("body",$ExpenseName);//商品描述
	//自定义订单号，此处仅作举例
	
	$timeStamp = time();
	//$out_trade_no = WxPayConf_pub::APPID."$timeStamp";
	$out_trade_no =$_GET['state'];//
	$unifiedOrder->setParameter("out_trade_no","$out_trade_no");//商户订单号 
	$unifiedOrder->setParameter("total_fee",$Price);//总金额
	//$unifiedOrder->setParameter("notify_url",WxPayConf_pub::NOTIFY_URL);//通知地址 
	$unifiedOrder->setParameter("notify_url",$NotifyUrl);//通知地址 
	$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
	//非必填参数，商户可根据实际情况选填
	//$unifiedOrder->setParameter("sub_mch_id","999");//子商户号  
	//$unifiedOrder->setParameter("device_info","777");//设备号 
	//$unifiedOrder->setParameter("attach",$_GET[state]);//附加数据 
	//$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
	//$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间 
	//$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记 
	//$unifiedOrder->setParameter("openid","444");//用户标识
	$unifiedOrder->setParameter("product_id","333");//商品ID

	$prepay_id = $unifiedOrder->getPrepayId();
	
	//=========步骤3：使用jsapi调起支付============
	$jsApi->setPrepayId($prepay_id);

	$jsApiParameters = $jsApi->getParameters();
	LogInFile($jsApiParameters,"eeeee.txt");
	//echo "如果页面停留在次页面，说明支付失败，有可能您使用的是电脑版微信！";
?>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <title>微信安全支付</title>

	<script type="text/javascript">
	//alert("fefe");

		//调用微信JS api 支付
		function jsApiCall()
		{
			WeixinJSBridge.invoke(
				'getBrandWCPayRequest',
				<?php echo $jsApiParameters; ?>,
				function(res){
					WeixinJSBridge.log(res.err_msg);
					//alert(res.err_msg);
					switch(res.err_msg)
					{
					case "get_brand_wcpay_request:ok":
						location="../../sys_sign_up/UserPage.php?operate=PayOk&PaymentID=<?echo $_GET['state']?>";
						break;
					case "get_brand_wcpay_request:cancel":
						location="../../sys_sign_up/UserPage.php";
						break;
					case "get_brand_wcpay_request:fail":
						location="../../sys_sign_up/UserPage.php";
						break;
					}
				}
			);
		}

		function callpay()
		{
			if (typeof WeixinJSBridge == "undefined")
			{
			
			    if( document.addEventListener )
				{
			        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
			    }else if (document.attachEvent)
				{
			        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
			        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
			    }
			}else
			{
			    jsApiCall();
			}
		}
		callpay();
	</script>
    <style type="text/css">
<!--
.STYLE1 {
	font-size: xx-large;
	background-color: #0066FF;
}
-->
    </style>
</head>
<body>
	
</div>
</body>
</html>