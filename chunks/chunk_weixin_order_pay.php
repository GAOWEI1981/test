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


//使用jsapi接口
$jsApi = new JsApi_pub();
//print_r($_GET);echo "<br>";
//echo $_SERVER['REQUEST_URI'];echo "<br>";
//=========步骤1：网页授权获取用户openid============
//通过code获得openid
if (isset($_GET['code']))
{
	//获取code码，以获取openid
	//print_r($_GET);echo "sdfsdf<br>";
	//return;
	$code = $_GET['code'];
	$jsApi->setCode($code);
	$openid = $jsApi->getOpenId();
	if(strlen($openid)==0) Header("Location:page_order_detail.php?OrderID={$_GET[OrderID]}");
}else
{
	echo "<script>alert('参数获取失败');</script>";
	return;
	//echo $openid."<br>";
	//echo dirname(__FILE__)."<br>";
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
$OrderInfo=GetItemA("orders","ID",$_GET[OrderID]);
//echo "eee:{$openid}<br>";
$unifiedOrder->setParameter("openid",$openid);//商品描述
$unifiedOrder->setParameter("body",$OrderInfo[title]);//商品描述
//自定义订单号，此处仅作举例
$timeStamp = time();
//$out_trade_no = "sdfsdf".$_GET[OrderID];//固定格式不要改
/*$sql="select * from account_book where OrderID='{$_GET[OrderID]}'";
$result=mysql_query($sql);
$PaymentInfo=mysql_fetch_array($result);*/
//echo $MoneyTotal;echo "<br>";
//echo $ExpressMoney;echo "<br>";
$MoneyTotal=intval($MoneyTotal*100+$ExpressMoney*100);
$unifiedOrder->setParameter("out_trade_no",$_GET[OrderID]);//商户订单号 
$unifiedOrder->setParameter("total_fee",$MoneyTotal);//总金额
$unifiedOrder->setParameter("notify_url",$NotifyUrl);//通知地址 
$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
//echo WxPayConf_pub::NOTIFY_URL;echo "<br>";
//非必填参数，商户可根据实际情况选填
//$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号  
//$unifiedOrder->setParameter("device_info","XXXX");//设备号 
//$unifiedOrder->setParameter("attach","XXXX");//附加数据 
//$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
//$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间 
//$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记 
//$unifiedOrder->setParameter("openid","XXXX");//用户标识
//$unifiedOrder->setParameter("product_id","XXXX");//商品ID

$prepay_id = $unifiedOrder->getPrepayId();
//=========步骤3：使用jsapi调起支付============
$jsApi->setPrepayId($prepay_id);

$jsApiParameters = $jsApi->getParameters();
//echo $jsApiParameters;
?>
<script type="text/javascript">

//调用微信JS api 支付
//alert("");

function jsApiCall()
{
	WeixinJSBridge.invoke(
		'getBrandWCPayRequest',
		<?php echo $jsApiParameters; ?>,
		function(res){
			WeixinJSBridge.log(res.err_msg);
			//alert(res.err_code+res.err_desc+res.err_msg);
			switch(res.err_msg)
			{
			case "get_brand_wcpay_request:ok":
				var url="<?echo "{$PageAfterPay}&err=支付成功";?>";
				//alert(url);
				location=url;
				break;
			case "get_brand_wcpay_request:cancel":
				//location="<?echo "{$FileName}?OrderID={$_GET[OrderID]}";?>";
				location="<?echo "{$PageAfterPay}&err=支付已退出";?>";
				break;
			case "get_brand_wcpay_request:fail":
				//location="../../sys_sign_up/UserPage.php";
				alert("该订单已经支付过了。");
				break;
			}
		}
	);
}
//var stateObj = { foo: "bar" };
//history.pushState(stateObj, "page 2", "page_orders_user.php");

function callpay()
{
	if (typeof WeixinJSBridge == "undefined"){
		if( document.addEventListener ){
			document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		}else if (document.attachEvent){
			document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
			document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		}
	}else{
		jsApiCall();
	}
}
</script>
