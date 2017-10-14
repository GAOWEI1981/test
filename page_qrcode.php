<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width"/>

<title>我的二维码</title>
<link href="../function/industry.css" rel="stylesheet" type="text/css" />

</head>
<body class="BodyNoSpace">
<?
ob_start();
error_reporting(E_ALL & ~E_NOTICE);
if(!isset($_SESSION)) session_start();

include_once("../function/config.php");
include_once("../function/phpqrcode/phpqrcode.php");
include_once("../function/Script.php");

$LinkEnable=false;
include_once("chunks/chunk_title.php");


if($Database->Connect()==false)
{
	echo "db connect false!";
	return;
}

mysql_select_db($DatabaseName);


$FileName=basename($_SERVER['PHP_SELF']);
//echo dirname($_SERVER['PHP_SELF']);echo "<br>";
$operate=$_GET['operate'];
switch($operate)
{
	case "":
		break;
}
/*$BackUrl="index.php?owner={$_SESSION[WebOwner]}";
$Icon="../icon/icon_order.png";
include_once("chunks/chunk_back_bar.php");*/


$Output="";

if(strlen($_GET[owner])>0 && strlen($_GET[ticket])==0)
{
	$sql="select ID from orders where creater='{$_GET[owner]}'";
			
	$sql="select * from account_book where OrderID in({$sql}) and type<>'Loan' and type<>'loan'";
	$result=mysql_query($sql);
	$num=mysql_num_rows($result);
	//echo $num;echo "<br>";
	if($num>0)
	{
		$url="http://lanxiao.ghostinmetal.com/WeixinPayService/page_get_qrcode.php?id={$_GET[owner]}";
		$ReturnUrl="http://".$_SERVER[HTTP_HOST]."{$_SERVER["PHP_SELF"]}";//返回的链接
			//echo $ReturnUrl;echo "<br>";return;
		//$url="{$UserInfoUrl}&ReturnUrl={$ReturnUrl}&OutState=1234";
		$url.="&ReturnUrl={$ReturnUrl}";
		Header("Location:{$url}");
	}
	else
	{
		?>
        <div class="weui_cells">
            <div class="weui_cell">
                <div class="weui_cell_bd weui_cell_primary" align="left">
                    亲，您未在本商城完成订单，无法生成二维码。
                </div>
                <div class="weui_cell_ft">
                </div>
            </div>
        </div>
	
		<?
	}
	//13977118609
}
else
{
	if(strlen($_GET[owner])>0)
	{
		
		$url="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket={$_GET[ticket]}";
		?>
			<div class="weui_cells">
				<div class="weui_cell">
					<div class="weui_cell_bd weui_cell_primary" align="center">
						<img style="width:80%" src="<?echo $url;?>"/>
					</div>
					<div class="weui_cell_ft">
					</div>
				</div>
			</div>
	
		<?
	}
	else
	{
		?>
		 <div class="weui_cells">
            <div class="weui_cell">
                <div class="weui_cell_bd weui_cell_primary" align="left">
                    亲，请先登陆商城。
                </div>
                <div class="weui_cell_ft">
                </div>
            </div>
        </div>
        <?
	}
	

}
//print_r($_GET);echo "<br>";
?>
</body>
</html>
<script>

</script>


