<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width"/>

<title>用户管理</title>
<link href="../function/industry.css" rel="stylesheet" type="text/css" />

</head>
<body class="BodyNoSpace">
<table width="100%" border="0" cellpadding="1" cellspacing="0" class="Industry">
<tr>
    <th align="left"><a href="page_edit_users.php?operate=UserTable">返回</a></th>
    <th>&nbsp;</th>
  </tr>
</table>

<?
ob_start();
error_reporting(E_ALL & ~E_NOTICE);
if(!isset($_SESSION)) session_start();

include_once("../function/config.php");
include_once("../function/functions_mall.php");
include_once("../function/Script.php");
include_once("LocalConfig.php");


if($Database->Connect()==false)
{
	echo "db connect false!";
	return;
}

mysql_select_db($DatabaseName);


$FileName=basename($_SERVER['PHP_SELF']);

$LocalRebate=new RebateConfig("ConfigInfo.inf");

$operate=$_GET['operate'];
//$MainPage=basename($_SERVER["PHP_SELF"]);
//print_r($_SESSION);echo "<br>";


//$utool=new UsersDatabase();
//$utool->IsOwner($_SESSION[adminID],$_GET[id]);
//$utool->ChangeUserOwner($_SESSION[adminID],$_GET[id]);


$LastPage=$_GET['LastPage'];
switch($operate)
{
case "DelRebate":
	$time=time();
	//$LocalRebate=new RebateConfig("ConfigInfo.inf");
	print_r($_GET);
	if(strlen($_GET[id])>0)
	{
		$sql="delete from account_book where id='{$_GET[id]}'";
		//echo $sql;echo "<br>";
		mysql_query($sql);
	}
	//$LocalRebate->RebateExe($_GET[id],$time);
	Header("Location:{$FileName}?id={$_GET[UserID]}");
	
	return;
case "RebateExe":
	$item=$LocalRebate->GetLastRebate($_GET[id]);
	$time=$item[time];//获取最近一次分红时间

	//$LocalRebate=new RebateConfig("ConfigInfo.inf");
	print_r($_GET);
	$LocalRebate->RebateExe($_GET[id],$time);
	Header("Location:{$FileName}?id={$_GET[id]}");
	return;
case "ChangeRebate":
	if(strlen($_GET[id])>0)
	{
		print_r($_POST);
		$LocalRebate->SetRebate($_GET[id],$_POST[0],$_POST[1]);
	}
	Header("Location:{$FileName}?id={$_GET[id]}");
	return;
case "CreateNewUser":
	/*print_r($_POST);
	$time=time();
	$sql="insert into users (name,password,page,brand,lt) values('{$_POST[name]}','{$_POST[password]}','{$_POST[HomePage]}','三恒','{$time}')";
	echo $sql;
	mysql_query($sql);
	Header("Location:{$FileName}?operate=UserTable");*/
	return;
case "UserChange":
	$id=$_GET['id'];
	$_SESSION['adminID']=$id;
	$item=GetItem("users",$id);
	$name=$item['name'];
	$_SESSION['adminName']=$name;
	$_SESSION['modal']="admin";//这个工作模式可以处理子账户的财务
	//header("Location:OrderView.php?MainPage=OrderView.php");
	header("Location:{$item[page]}?MainPage=OrderView.php");
	break;
case "ChangeUserInfo":
	print_r($_POST);
	echo "<br>";
	$id=$_GET['id'];
	if(strlen($id)>0)
	{
		//,user_type='{$_POST[ggggg]}' 
		$sql="update signup_users set name='{$_POST[user]}',user_type='{$_POST[RoleID]}' where openid='{$id}'";
		echo $sql;echo "<br>";
		mysql_query($sql);
		if(strlen($_POST[ps])>0)
		{
			$sql="update signup_users set password='{$_POST[ps]}' where openid='{$id}'";
			mysql_query($sql);
		}
	}	
	Header("Location:{$FileName}?id={$id}");
	return;
default:
	include_once("chunks/chunk_edit_user.php");
	include_once("chunks/chunk_edit_user_rebate.php");
	include_once("chunks/chunk_edit_user_rebate_history.php");
	return;
}
?>
</body>
</html>
<script>
function CreateNewUser()
{
	try
	{
		var ps=document.getElementById("ps").value;
		//alert(ps);
		var va=hash(ps);
		document.getElementById("password").value=va;
		document.getElementById("ps").value="";
		return true;
	}catch(e)
	{
		alert(e);
	}
	return false;
	
}
</script>


