<?php
/* *
 * 功能：支付宝服务器异步通知页面
 * 版本：2.0
 * 修改日期：2016-11-01
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。

 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
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






$arr=$_POST;
$alipaySevice = new AlipayTradeService($config); 
$alipaySevice->writeLog(var_export($_POST,true));
$result = $alipaySevice->check($arr);

/* 实际验证过程建议商户添加以下校验。
1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
4、验证app_id是否为该商户本身。
*/

/*$keys=array_keys($_POST);
foreach($keys as $key)
{
	$content.=$key.":".$_POST[$key]."\r\n";
}*/
if($Database->Connect()==false)
{
	echo "db connect false!";
	return;
}
mysql_select_db($DatabaseName);

$sql="select * from account_alipay_payment where trade_no='{$_POST[trade_no]}'";
$result=mysql_query($sql);
//echo $sql;echo "<br>";
if(mysql_num_rows($result)>0)//如果已经记录了就不通知了
{
}
else
{
	$time=time();
	$Columns="notify_id,			buyer_id,				buyer_logon_id,				trade_status,				out_trade_no,				trade_no,				receipt_amount,				buyer_pay_amount,			time";
	$Values="'{$_POST[notify_id]}',	'{$_POST[buyer_id]}',	'{$_POST[buyer_logon_id]}',	'{$_POST[trade_status]}',	'{$_POST[out_trade_no]}',	'{$_POST[trade_no]}',	'{$_POST[receipt_amount]}',	'{$_POST[buyer_pay_amount]}',	'{$time}'";
	$sql="insert into account_alipay_payment ({$Columns}) values({$Values})";
	mysql_query($sql);
	
	
	if(strlen($_POST[out_trade_no])>0 && $_POST[trade_status]=="TRADE_SUCCESS")
	{
		$time=time();
		$sql="update orders set time='{$time}' where ID='{$_POST[out_trade_no]}'";
		mysql_query($sql);
		$sql="update account_book set type='' where OrderID='{$_POST[out_trade_no]}'";
		mysql_query($sql);
		$sql="update orders set processing_schedule='processing' where ID='{$_POST[out_trade_no]}'";
		mysql_query($sql);
		
		/*//通知公众号
		$item=GetItemA("orders","ID",$_POST[out_trade_no]);
		$msg="客户通过支付宝支付了订单";
		$remark="队名-$_POST[subject]";
		$url="http://weixin.ghostinmetal.com/sys_CustomerService/OutSideInterface.php?id=ox9Bqw_1-PioRB-gIrleLpFduDQU&msg={$msg}&remark={$remark}&OrderID={$_POST[out_trade_no]}";
		//echo $url;echo "<br>";
		$content=file_get_contents($url);*/
	}
}


if($result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代

	
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
	
    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
	
	//商户订单号

	$out_trade_no = $_POST['out_trade_no'];

	//支付宝交易号

	$trade_no = $_POST['trade_no'];

	//交易状态
	$trade_status = $_POST['trade_status'];


    if($_POST['trade_status'] == 'TRADE_FINISHED') {

		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//请务必判断请求时的total_amount与通知时获取的total_fee为一致的
			//如果有做过处理，不执行商户的业务程序
				
		//注意：
		//退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
		
    }
    else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//请务必判断请求时的total_amount与通知时获取的total_fee为一致的
			//如果有做过处理，不执行商户的业务程序			
		//注意：
		//付款完成后，支付宝系统发送该交易状态通知
    }
	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
        
	echo "success";		//请不要修改或删除
		
}else {
    //验证失败
    echo "fail";	//请不要修改或删除

}

?>

