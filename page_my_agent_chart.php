<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<title>我的代理</title>
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
if($UserInfo==null)
{
	Header("Location:page_user_login.php");
}
$BackUrl="page_my_agents.php";
$Icon="../icon/icon_us.png";$IconWidth="25px";
include_once("chunks/chunk_back_bar.php");
switch($operate)
{
case "":
	break;
}

//include_once("chunks/chunk_foot.php");
?>
<div class="weui_cells">
<div class="weui_cell">
        <div class="weui_cell_bd weui_cell_primary">
        <table width="100%" border="0" cellpadding="1">
        <tr>
        <td width="34%" align="left">产品</td>
        <td width="25%" align="center">单价</td>
        <td width="20%" align="center">数量</td>
        <td width="21%" align="center">合计</td>
        </tr>
        </table>
      </div>
    </div>
<?

$orders="select * from orders where creater='{$_GET[UserID]}'";
//echo $orders;echo "<br>";
$account="select id,OrderID,product,price,count,cast((gross) as DECIMAL(8,2)) as MoneyTotal from account_book where type<>'loan' and type<>'Loan'";

$sql="select a.title,b.* from ({$orders}) a left join ({$account}) b on a.ID=b.OrderID";
$sql="select a.* from ({$sql}) a where a.price<>''";
//echo $sql;echo "<br>";
$result=mysql_query($sql);$gross=0;
while($row=mysql_fetch_array($result))
{
	//$ItemCount=1000+$row[ItemCount];
	$gross+=$row[MoneyTotal];
	
	?>
	<div class="weui_cell">
		<div class="weui_cell_bd weui_cell_primary">
		<table width="100%" border="0" cellpadding="1">
		<tr>
		<td width="34%" align="left"><?echo $row[product];?></td>
		<td width="25%" align="center"><?echo $row[price];?>&nbsp;</td>
		<td width="20%" align="center"><?echo $row[count];?>&nbsp;</td>
		<td width="21%" align="center"><?echo $row[MoneyTotal];?>&nbsp;</td>
		</tr>
		</table>
	  </div>
	</div>
	<?
}

?>
<div class="weui_cell">
		<div class="weui_cell_bd weui_cell_primary">
		<table width="100%" border="0" cellpadding="1">
		<tr>
		<td width="19%" align="left">&nbsp;</td>
		<td width="26%" align="center">&nbsp;</td>
		<td width="26%" align="center">&nbsp;</td>
		<td width="29%" align="center"><?echo $gross;?>&nbsp;</td>
		</tr>
		</table>
	  </div>
	</div>
</div>
</body>
</html>
<script>
</script>