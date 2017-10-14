<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width"/>

<title>用户管理</title>
<link href="../function/industry.css" rel="stylesheet" type="text/css" />

</head>
<body class="BodyNoSpace">
<?
ob_start();
error_reporting(E_ALL & ~E_NOTICE);
if(!isset($_SESSION)) session_start();

include_once("../function/config.php");
include_once("../function/functions_mall.php");
include_once("../function/JSSDK.php");
include_once("../function/Script.php");
include_once("LocalConfig.php");


if($Database->Connect()==false)
{
	echo "db connect false!";
	return;
}

mysql_select_db($DatabaseName);


$FileName=basename($_SERVER['PHP_SELF']);
//echo $FileName;
print_r($_SESSION);echo "<br>";
$operate=$_GET['operate'];
//$MainPage=basename($_SERVER["PHP_SELF"]);
$LastPage=$_GET['LastPage'];
switch($operate)
{
case "SetAsTopMember":
	if(strlen($_GET[UserID])>0)
	{
		$sql="update signup_users set owner='' where openid='{$_GET[UserID]}'";
		mysql_query($sql);
		//echo $sql;
	}
	Header("Location:{$FileName}");
	return;
case "DeleteAccount":
	if(strlen($_GET[UserID])>0)
	{
		$sql="delete from signup_users where openid='{$_GET[UserID]}'";
		mysql_query($sql);
		//echo $sql;
	}
	Header("Location:{$FileName}");
	break;
case "ClearAccount":
	if(strlen($_GET[UserID])>0)
	{
		$sql="select a.id,b.creater from account_book a left join orders b on a.OrderID=b.ID";
		$sql="select a.id from ({$sql}) a where a.creater='{$_GET[UserID]}'";
		//$sql="delete from ({$sql}) a where a.creater='{$_GET[UserID]}'";
		$sql="delete from account_book where id in({$sql})";
		mysql_query($sql);
		$sql="delete from orders where creater='{$_GET[UserID]}'";
		mysql_query($sql);
		echo $sql;
	}
	Header("Location:{$FileName}");
	break;
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
case "ChangeUserInfo":
	//print_r($_POST);
	echo "<br>";
	$id=$_GET['id'];
	if(strlen($id)>0)
	{
		$password=$_POST['ps'];
		$name=$_POST['user'];
		$HomePage=$_POST['page'];
		$UserType=$_POST['UserType'];
		$brand=$_POST['brand'];
		
		if(strlen($password))
		{
			$sql="update users set name='{$name}',password='{$password}',brand='{$brand}',page='{$HomePage}',UserType='{$UserType}' where ID='{$id}'";
		}
		else 
			$sql="update users set name='{$name}',page='{$HomePage}',brand='{$brand}',UserType='{$UserType}' where ID='{$id}'";
		//echo $sql;
		mysql_query($sql);

	}	
	Header("Location:UsersView.php?operate=UserTable");
	return;
case "AddPopedom":
	$SysName=$_POST['SysName'];
	$TimeLimit=strtotime($_POST['TimeLimit']);
	$time=time();
	$id=$_GET['id'];
	$sql="insert into sys_popedom (sys_name,user_id,time,time_limit) values('{$SysName}','{$id}','{$time}','{$TimeLimit}')";
	echo $sql;
	echo "<br>";
	mysql_query($sql);
	Header("Location:{$FileName}?operate=UserView&id={$id}");
	return;
case "DelPopedom":
	$time=time();
	$id=$_GET['PopdomID'];
	$UserID=$_GET['id'];
	$sql="delete from sys_popedom where id={$id}";
	
	mysql_query($sql);
	Header("Location:{$FileName}?operate=UserView&id={$UserID}");
	return;
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
}
include_once("chunks/chunk_console_title.php");
?>
<table width="100%" border="1" cellpadding="0" cellspacing="0" class="Industry">
  <tr>
    <td width="15" colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center"><table width="100%" border="0" cellpadding="1">
      <tr>
        <td width="63%" align="center"><input style="width:80%;" type="text" name="keyword" id="keyword" /></td>
        <td width="37%" align="center"><input type="button" style="width:90%;" onclick="return Search();" value="查找" /></td>
      </tr>
    </table></td>
  </tr>
</table>

<table width="100%" border="1" cellpadding="1" cellspacing="0" class="Industry" style="table-layout:fixed;">
  <tr>
    <th width="60%" align="center">用户信息</th>
    <th width="40%" align="center">&nbsp;</th>
  </tr>
  <?
  	//print_r($LocalSysConfig);echo "<br>";
	$LocalRebate=new RebateConfig("ConfigInfo.inf");
	/*$config=file_get_contents("ConfigInfo.inf");
	$brand=GetXMLParam($config,"brand");//获取本地设置
	$UserInfoUrl=GetXMLParam($config,"UserInfoUrl");//微信用户信息获取网址
	$SysName=GetXMLParam($config,"AppName");*/
	
	$account="select id,OrderID,cast((gross) as DECIMAL(8,2)) as MoneyTotal from account_book where type<>'loan' and type<>'Loan'";
	//$account="select id,OrderID,cast((price*count) as DECIMAL(8,2)) as MoneyTotal from account_book where type<>'loan' and type<>'Loan'";
	
	//echo $account;echo "<br>";
	$account="select a.OrderID,sum(a.MoneyTotal) as MoneyTotal from ({$account}) a group by OrderID";
	//echo $account;echo "<br>";
	//$account="select a.creater,b.ItemCount,b.MoneyTotal,b.OrderID from orders a left join ({$account}) b on a.ID=b.OrderID";
	$account="select a.MoneyTotal,a.OrderID,b.creater from ({$account}) a left join orders b on a.OrderID=b.ID";
	//echo $account;echo "<br><br>";
	$account="select a.creater,sum(a.MoneyTotal) as MoneyTotal,count(a.OrderID) as ItemCount from ({$account}) a group by a.creater";
	//echo $account;echo "<br><br>";
	//$users="select * from signup_users where owner='{$_SESSION[adminID]}'";
	$sql="select a.openid,a.user_type,a.nickname,a.name,a.phone,a.login_time,a.time,b.MoneyTotal,b.ItemCount from signup_users a left join ({$account}) b on a.openid=b.creater";
	//echo $sql;echo "<br><br>";
	$sql="select a.*,b.name as UserTypeName from ({$sql}) a left join signup_user_type b on a.user_type=b.id";
	//echo $sql;echo "<br><br>";
	if(strlen($_GET[keyword])>0)
		$sql="select a.* from ({$sql}) a where a.name like '%{$_GET[keyword]}%' or a.phone like '%{$_GET[keyword]}%' order by a.login_time desc,a.time desc";
	else
		$sql="select a.* from ({$sql}) a order by a.login_time desc,a.time desc";
	//echo $sql;echo "<br>";
	$result=mysql_query($sql);
	while($row=mysql_fetch_array($result))
	{
		//返利设置
		$rs=$LocalRebate->GetRebate($row[openid]);
		$OneRebate=$rs[0];
		$TwoRebate=$rs[1];
		//**********
		$time=date("y-m-d H:i",$row[time]);
		$LoginTime="";
		if(strlen($row[login_time])>0) $LoginTime=date("y-m-d H:i",$row[login_time]);
		$ItemCount=10000+$row[ItemCount];
		$ItemCount=substr($ItemCount,1,strlen($ItemCount)-1);
		//if(strlen($row[name])<20) continue;
		$title="{$row[name]}{$row[phone]}";
		if(strlen($title)==0) $title="{$row[nickname]}_NULL";
		$title= "<a href='page_edit_user_info.php?id={$row[openid]}'>{$title}</a>";
		?>
  <tr>
    <td rowspan="2" style="word-wrap:break-word;">
      <table width="100%" border="0" cellpadding="2">
        <tbody>
          <tr>
            <td colspan="2"><?echo $title;?>&nbsp;</td>
          </tr>
          <tr>
            <td width="37%">消费</td>
            <td width="63%"><?echo $row[MoneyTotal];?>&nbsp;</td>
          </tr>
          <tr>
            <td>角色</td>
            <td><span><?echo $row[UserTypeName];?></span></td>
          </tr>
          <tr>
            <td>上次登陆</td>
            <td><?echo $LoginTime;?>&nbsp;</td>
          </tr>
        </tbody>
    </table></td>
    <td align="center"><table width="100%" border="0" cellpadding="2">
      <tbody>
        <tr>
          <td width="67%">订单数</td>
          <td width="33%"><a href="page_payments.php?UserID=<?echo $row[openid];?>"><?echo $ItemCount;?></a>&nbsp;</td>
        </tr>
        <tr>
          <td>一级返利</td>
          <td><?echo $OneRebate;?>&nbsp;</td>
        </tr>
        <tr>
          <td>二级返利</td>
          <td><?echo $TwoRebate;?>&nbsp;</td>
        </tr>
      </tbody>
    </table></td>
  </tr>
  <tr>
    <td align="right"><select name="select2" id="<?echo $row[openid];?>" onchange="Operate(this);">
      <option selected="selected">操作</option>
      <option value='<?echo "{$FileName}?operate=SetAsTopMember&UserID={$row[openid]}";?>'>设为一级会员</option>
      <option value='<?echo "{$FileName}?operate=DeleteAccount&UserID={$row[openid]}";?>'>删除账户</option>
      <option value='<?echo "{$FileName}?operate=ClearAccount&UserID={$row[openid]}";?>'>清空账单</option>
    </select></td>
  </tr>
  <?
    }
	?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

</body>
</html>
<script>
function Search()
{
	var obj=document.getElementById("keyword");
	location="<?echo $FileName;?>?keyword="+obj.value;
}
function Operate(obj)
{
	try
	{
		var index=obj.selectedIndex;
		var url=obj.options[index].value;
		//alert(url);
		if(confirm("确定要"+obj.options[index].text+"?"))
		{
			location=url;			
		}
	}catch(e)
	{
		alert(e);
	}
}
function JumpPage(obj)
{
	var index=obj.selectedIndex;
	//alert(obj.options[index].value);
	location=obj.options[index].value
}
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


