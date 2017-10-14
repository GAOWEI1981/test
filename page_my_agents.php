<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<title>我的会员</title>
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
include_once("../function/functions_mall.php");
include_once("LocalConfig.php");
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
$BackUrl="page_user_table.php";
$Icon="../icon/icon_us.png";$IconWidth="25px";
include_once("chunks/chunk_back_bar.php");
switch($operate)
{
case "":
	break;
}

//include_once("chunks/chunk_foot.php");
?>
<div class="weui_cells_title">一级</div>
<div class="weui_cells">
<div class="weui_cell">
        <div class="weui_cell_bd weui_cell_primary">
        <table width="100%" border="0" cellpadding="1">
        <tr>
        <td width="32%" align="left">用户信息</td>
        <td width="39%" align="center">订单数</td>
        <td width="29%" align="center">消费额</td>
        </tr>
        </table>
      </div>
    </div>
<?
$info=GetClientsPayments_OneLeve($_SESSION[adminID]);
//echo $info[0];echo "<br>";
$result=mysql_query($info[0]);
while($row=mysql_fetch_array($result))
{
	$ItemCount=1000+$row[ItemCount];
	$ItemCount=substr($ItemCount,1,strlen($ItemCount)-1);
	
	$chart="";
	if(strlen($row[MoneyTotal])>0)
		$chart="<a class='weui_btn weui_btn_primary weui_btn_mini' style='width:100%' href='page_my_agent_chart.php?UserID={$row[openid]}'>{$row[MoneyTotal]}</a>";
	//print_r($row);echo "<br>";
?>
    <div class="weui_cell">
        <div class="weui_cell_bd weui_cell_primary">
        <table width="100%" border="0" cellpadding="1">
        <tr>
        <td width="32%" align="left">
        <?
		//print_r($row);echo "<br>";
        if(strlen($row[name])>0)
			echo $row[name];
		else
		{
			if(strlen($row[phone])>0)
				echo $row[phone];
			else echo "未注册";
		}
        ?></td>
        <td width="39%" align="center"><?echo $ItemCount;?>&nbsp;</td>
        <td width="29%" align="center"><?echo $chart;?></td>
        </tr>
        </table>
      </div>
    </div>
<?
}

?>
</div>


<div class="weui_cells_title">二级</div>
<div class="weui_cells">
<div class="weui_cell">
        <div class="weui_cell_bd weui_cell_primary">
        <table width="100%" border="0" cellpadding="1">
        <tr>
        <td width="32%" align="left">用户信息</td>
        <td width="39%" align="center">订单数</td>
        <td width="29%" align="center">消费额</td>
        </tr>
        </table>
      </div>
    </div>
<?
$info=GetClientsPayments_TwoLeve($_SESSION[adminID]);
$result=mysql_query($info[0]);
while($row=mysql_fetch_array($result))
{
	$ItemCount=1000+$row[ItemCount];
	$ItemCount=substr($ItemCount,1,strlen($ItemCount)-1);
	
	$chart="";
	if(strlen($row[MoneyTotal])>0)
		$chart="<a class='weui_btn weui_btn_primary weui_btn_mini' style='width:100%' href='page_my_agent_chart.php?UserID={$row[openid]}'>{$row[MoneyTotal]}</a>";
?>
    <div class="weui_cell">
        <div class="weui_cell_bd weui_cell_primary">
        <table width="100%" border="0" cellpadding="1">
        <tr>
        <td width="32%" align="left">
        <?
		//print_r($row);echo "<br>";
        if(strlen($row[name])>0)
			echo $row[name];
		else
		{
			if(strlen($row[phone])>0)
				echo $row[phone];
			else echo "未注册";
		}
        ?>
        </td>
        <td width="39%" align="center"><?echo $ItemCount;?>&nbsp;</td>
        <td width="29%" align="center"><?echo $chart;?></td>
        </tr>
        </table>
      </div>
    </div>
<?
}

?>
</div>
</body>
</html>
<script>
</script>