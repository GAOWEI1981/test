<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<title>我的订单</title>
<link rel="stylesheet" href="../function/industry.css"/>
<link rel="stylesheet" href="../function/weui/dist/style/weui.css"/>
<link rel="stylesheet" href="../function/weui/dist/style/weui.min.css"/>
</head>
<body ontouchstart>
<?
ob_start();
error_reporting(E_ALL & ~E_NOTICE);
if(!isset($_SESSION)) session_start();
include_once("../function/config.php");
include_once("../function/JSSDK.php");
include_once("../function/Script.php");
include_once("LocalConfig.php");


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

//include_once("chunks/chunk_title.php");

$UserInfo=GetItemA("signup_users","openid",$_SESSION[adminID]);
if(strlen($UserInfo[phone])==0)
{
	$_SESSION[adminID]="";
	Header("Location:page_user_login.php?err=请登录");
	return;
}
//待支付
$LoanSql="SELECT
sum(cast((account_book.price * account_book.count+account_book.cost_express) as decimal(8,3))) as MoneyTotal,
orders.ID,
orders.title,
orders.address,
orders.PostWay,
orders.receiver,
account_book.product,
account_book.type,
orders.creater,
max(account_book.time) as time
FROM
account_book
LEFT JOIN orders ON account_book.OrderID = orders.ID
WHERE (account_book.type='Loan' or account_book.type='loan') and orders.creater='{$_SESSION[adminID]}'
GROUP BY
account_book.OrderID
";
//已经支付
$PaymentSql="SELECT
sum(cast((account_book.price * account_book.count+account_book.cost_express) as decimal(8,3))) as MoneyTotal,
orders.ID,
orders.title,
orders.address,
orders.PostWay,
orders.receiver,
account_book.product,
account_book.type,
orders.creater,
max(account_book.time) as time
FROM
account_book
LEFT JOIN orders ON account_book.OrderID = orders.ID
WHERE (account_book.type='' or account_book.type is null) and orders.creater='{$_SESSION[adminID]}'
GROUP BY
account_book.OrderID
";

switch($operate)
{
case "SwitchView":
	$_SESSION[OrderViewModal]=$_GET[view];
	Header("Location:{$FileName}");
	return;
case "CancelOrder":
	$_SESSION[OrderParam]="";
	Header("Location:{$FileName}");
	return;
case "":
	//print_r($_POST);echo "<br>";

	if(strlen($_SESSION[adminID])>0)
	{
		if(strlen($_SESSION[OrderParam])>0)
		{	
			//添加订单记录
			$time=time();
			$OrderInfo=unserialize($_SESSION[OrderParam]);
			$CurrentTime=time();
			$TeamName="web_trade";
			$ID=CreateID(10)."_{$CurrentTime}";
			$sql="INSERT INTO orders (ID,title,PostWay,address,receiver,phone,creater,PostNum,time,state,processing_schedule)";
			$sql.="VALUES ('{$ID}','{$TeamName}', '韵达寄付', '{$_POST[address]}','{$_POST[receiver]}','{$_POST[phone]}','{$_SESSION[adminID]}','','{$CurrentTime}','新的','draft')";
			mysql_query($sql);
			//添加订单条目
			//print_r($OrderInfo);echo "<br>";
			for($i=0;$i<$OrderInfo[count];$i++)
			{
				$sql="insert into items (OrderID,TeamName,stature,avoirdupois,Size,SizeType,Model,time)";
				$sql.=" values('{$ID}','{$TeamName}','-1','-1','{$OrderInfo[size]}','CHN','{$OrderInfo[modal]}_{$OrderInfo[color]}','{$time}')";
				mysql_query($sql);
				echo $sql;echo "<br>";
			}
	
			//添加账单
			$MoneyInfo=GetItemA("products","modal",$OrderInfo[modal]);
			
			
			$Price=GetPrice($_SESSION[adminID],$OrderInfo[modal]);
			
			//print_r($MoneyInfo);echo "<br>";
			$gross=($MoneyInfo[cost_card]-$MoneyInfo[cost])*$OrderInfo[count];
			$sql="insert into account_book (OrderID,title,product,cost,cost_paint,price,count,gross,type,time,creater,cost_express)";
			$sql.=" values('{$ID}','服装','{$OrderInfo[modal]}','{$MoneyInfo[cost]}','0','{$Price}','{$OrderInfo[count]}','{$gross}','Loan','{$time}','0','0')";
			//echo $sql;echo "<br>";return;
			
			mysql_query($sql);
			$_SESSION[OrderParam]="";
			Header("Location:page_order_detail.php?OrderID={$ID}");
			return;
			//Header("Location:page_alipay.php?OrderID={$ID}");
		}
		else
		{
			$BackUrl="index.php?owner={$_SESSION[WebOwner]}";
			$Icon="../icon/icon_order.png";
			?>
			<div style="margin-top:-5px;z-index:200;width:100%;position:fixed; top:0;background-color:#FFFFFF">
			<?
				include_once("chunks/chunk_back_bar.php");
			?>
            <table width="100%" border="0" cellpadding="1" cellspacing="0">
            <tr>
            <td width="50%" align="center" height="40px;">
            <a class="weui-form-preview__btn weui-form-preview__btn_default" href="<?echo "{$FileName}?operate=SwitchView&view=LoanView";?>">待支付</a></td>
            <td width="50%" align="center">
            <a class="weui-form-preview__btn weui-form-preview__btn_default" href="<?echo "{$FileName}?operate=SwitchView&view=PaymentView";?>">已支付</a></td>
            </tr>
            </table>
            
            </div>

            <?
			switch($_SESSION[OrderViewModal])
			{
			case "PaymentView":
				$sql=$PaymentSql;
				break;
			default:
				$sql=$LoanSql;
				break;
			}
			$sql="select a.* from ({$sql}) a order by a.time desc";
            include_once("chunks/chunk_orders_user.php");
			//echo "88888";
			//print_r($_GET);echo "<br>";
		
		}
		
	}
	else
	{
		Header("Location:page_user_login.php?err=请登录");
		return;
	}
	//print_r($_GET);echo "<br>";
	//Header("Location:page_orders_user.php");

	break;
case "DelOrder":
	print_r($_GET);
	if(strlen($_GET[OrderID])>0)
	{
		$sql="delete from items where OrderID='{$_GET[OrderID]}'";
		mysql_query($sql);
		$sql="delete from orders where ID='{$_GET[OrderID]}'";
		mysql_query($sql);
		$sql="delete from account_book where OrderID='{$_GET[OrderID]}'";
		mysql_query($sql);
	}
	Header("Location:{$FileName}");
	return;
case "CreateOrder":
	include_once("chunks/chunk_fix_order.php");
	break;
}

//include_once("chunks/chunk_foot.php");
?>
</body>
</html>
<script>
var err="<?echo $_GET[err];?>";
if(err.length>0) alert(err);
</script>