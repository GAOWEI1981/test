<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<link href="../function/industry.css" rel="stylesheet" type="text/css" />
<title>权限设置</title>
</head>
<body class="BodyNoSpace">

<?
error_reporting(E_ALL & ~E_NOTICE);
if(!isset($_SESSION)) session_start();
include_once "../function/excel/Classes/PHPExcel/IOFactory.php";
include_once("../function/config.php");
include_once("LocalConfig.php");
//include_once("../function/Script.php");

$FileName=basename($_SERVER["PHP_SELF"]);
$config=file_get_contents("ConfigInfo.inf");
$brand=GetXMLParam($config,"brand");//获取本地设置
$SysName=GetXMLParam($config,"AppName");
//https://m.kuaidi100.com/

if($Database->Connect()==false)
{
	echo "db connect false!";
	return;
}
mysql_select_db($DatabaseName);


$operate=$_GET["operate"];
switch($operate)
{
case "CreateNewUser":
	print_r($_POST);
	$time=time();
	$sql="insert into users (name,password,page,brand,lt) values('{$_POST[name]}','{$_POST[password]}','{$_POST[HomePage]}','三恒','{$time}')";
	echo $sql;
	mysql_query($sql);
	Header("Location:{$FileName}?operate=UserTable");
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

case "AddPopedom":
	$SysName=$_POST['SysName'];
	$TimeLimit=strtotime($_POST['TimeLimit']);
	$time=time();
	$id=$_GET['id'];
	$sql="insert into sys_popedom (sys_name,user_id,time,time_limit) values('{$SysName}','{$id}','{$time}','{$TimeLimit}')";
	echo $sql;
	echo "<br>";
	mysql_query($sql);
	Header("Location:{$FileName}?operate=EditPopedomView&id={$id}");
	return;
case "DelPopedom":
	
	if(strlen($_GET[id])>0)
	{
		$sql="delete from sys_popedom where id={$_GET[id]}";
		
		mysql_query($sql);
	}
	Header("Location:{$FileName}?operate=EditPopedomView&id={$_GET[RoleID]}");
	return;
case "UserTable":
	//include("Chunk_Edit_Users.php");
	break;
case "UserView":
	$UserID=$_GET["id"];
	//print_r($_GET);
	$LastPage="UserView.php?operate=UserTable";
	//echo $LastPage;
	//echo "fefef";
	include("../chunks/Chunk_Edit_User.php");
	return;
case "Defer":
	$UserID=$_GET['UserID'];
	$PopedomID=$_GET['PopedomID'];
	$time=strtotime($_GET['time']);
	//$time="哥哥哥";
	echo "<response>";
	$sql="update sys_popedom set time_limit='{$time}' where id='{$PopedomID}'";
	//echo $sql;
	if($_GET['time']>0 && strlen($time)>0)
	{
		mysql_query($sql);
		echo "修改成功！";
	}
	else echo "修改失败！";
	
	echo "</response>";
	$sql="";
	return;
case "UpdateRoleInfo":
	if(strlen($_GET[id])>0)
	{
		$sql="update signup_user_type set jump_page='{$_GET[page]}',name='{$_GET[name]}' where id={$_GET[id]}";
		echo $sql;echo "<br>";
		mysql_query($sql);
	}
	Header("Location:{$FileName}");
	return;
case "EditPopedomView":
	?>
    <table width="100%" border="0" cellpadding="1" cellspacing="0" class="Industry">
    <tr>
    <th align="left"><a href="page_edit_popedom.php">返回</a></th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
    </tr>
    </table>
	<?
	include_once("chunks/chunk_edit_popedom.php");
	return;
case "CreateNewRole":
	if(strlen($_GET[name])>0)
	{
		$sql="insert into signup_user_type (name) values('{$_GET[name]}')";
		mysql_query($sql);
	}
	Header("Location:{$FileName}");
	return;
default:
	//include_once("chunks/chunk_edit_popedom.php");
	?>
    <table width="100%" border="0" cellpadding="1" cellspacing="0" class="Industry">
    <tr>
    <th align="left"><a href="page_edit_users.php">返回</a></th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
    </tr>
    </table>
	<?
	include_once("chunks/chunk_edit_roles.php");
	return;
}

?>
</body>
</html>
<script>

</script>