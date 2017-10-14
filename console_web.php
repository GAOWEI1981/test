<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<title>配置</title>
<link href="../function/industry.css" rel="stylesheet" type="text/css" />
</head>
<body class="BodyNoSpace">
<?
ob_start();
error_reporting(E_ALL & ~E_NOTICE);
if(!isset($_SESSION)) session_start();

include_once("../function/config.php");
include_once("../function/JSSDK.php");
include_once("../function/Script.php");
include_once("LocalConfig.php");
echo "sdfsdfsdf<br>";
/*
$data->Brand = "三恒";
$data->AppName = "products_sanheng";
$fp = fopen("ConfigInfo.php", "w");
fwrite($fp, json_encode($data));
fclose($fp);*/
if($Database->Connect()==false)
{
	echo "db connect false!";
	return;
}

mysql_select_db($DatabaseName);
//$_SESSION[SysName]="";
//可以访问的系统名称，防止访问不该访问的系统

$config=file_get_contents("ConfigInfo.inf");
$brand=GetXMLParam($config,"brand");//获取本地设置
$SysName=GetXMLParam($config,"AppName");//本系统名称

//print_r($_SESSION);
$FileName=basename($_SERVER["PHP_SELF"]);

//必须要管理员才能登陆
if(strlen($_SESSION['adminID'])==0)
{
	//Header("Location:Login.php");
	//$operate="login";
	print_r($_SESSION);echo "未登录";
	return;
	Header("Location:login.php?err=请重新登录");
}
else//判断使用权限
{
	
	$UserInfo=GetItemA("signup_users","openid",$_SESSION['adminID']);
	
	$r=HavePopedom($SysName,$UserInfo[user_type]);
	
	if($r[result]==false)
	{
		print_r($_SESSION);echo "<br>";
		print_r($r);echo "<br>";return;
		//Header("Location:login.php?err={$r[msg]}");
		return;
	}
	//print_r($UserInfo);echo "<br>";
	
}
$PageSize=10;
if(strlen($_GET['PageOffset'])==0) $PageOffset=0;
else $PageOffset=$_GET['PageOffset'];

//$PicSize=80;

$operate=$_GET['operate'];
if(!isset($PicSize))
	$PicSize=80;
switch($operate)
{
case "SetTop":
	$modal=$_GET["modal"];
	if(strlen($modal)>0)
	{
		$time=time();
		$sql="update products set time='{$time}' where modal='{$modal}'";
		echo $sql;
		mysql_query($sql);
	}
	Header("Location:{$FileName}");
	return;
case "DelModal":
	$modal=$_GET["modal"];
	//echo $modal;
	//if(strlen($modal)>0)
	{
		$sql="delete from products where modal='{$modal}'";
		mysql_query($sql);
		$sql="delete from product_items where modal='{$modal}'";
		mysql_query($sql);
		$sql="delete from product_group_items where modal='{$modal}'";
		mysql_query($sql);
		
		$sql="delete from orders where ID in (select OrderID from account_book where product='{$modal}' GROUP by OrderID)";
		mysql_query($sql);
		
		$sql="delete from account_book where product='{$modal}'";
		mysql_query($sql);
		
	}
	Header("Location:{$FileName}");
	
	return;
case "":
	break;
case "CreateNewModal":
	//$config=file_get_contents("ConfigInfo.inf");
	//$brand=GetXMLParam($config,"brand");//获取本地设置
	
	$name=$_POST["name"];
	$time=time();
	$sql="insert into products (modal,time,brand) values('{$name}','{$time}','{$brand}')";
	mysql_query($sql);
	Header("Location:{$FileName}");
	return;
default:
	return;
}
include_once("chunks/chunk_console_title.php");
?>
<form action="<?echo $FilaName?>?operate=CreateNewModal" method="post" name="NewModalForm" class="ProductTable" id="NewModalForm">
  <table width="100%" border="1" cellspacing="0" class="Industry">
    <tr>
      <td height="13" align="center"><input style="width:80%;" name="keyword" type="text" id="keyword" /></td>
      <td height="13" align="center"><input name="Submit2" type="submit" onclick="return Search();" value="查找" /></td>
    </tr>
    <tr>
      <td height="14" colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td height="27" align="center">
      <input style="width:80%;" name="name" type="text" id="name"/>      </td>
      <td width="34%" align="center"><input name="Submit" type="submit" onclick="return CreateModal();" value="添加新型号" /></td>
    </tr>
  </table>
</form>
<table width="100%" border="1" align="center" cellspacing="0" class="Industry">
<?
//$AllPics="select *,count(id) as ItemCount from product_items group by modal";
$sql="select * from products where brand='{$brand}'";
//$sql="select a.*,b.ItemCount from ({$AllMods}) a left join ({$AllPics}) b on a.modal=b.modal";
$sql="select sql_calc_found_rows a.* from ({$sql}) a where a.modal like '%{$_GET[keyword]}%' order by time desc limit {$PageOffset},{$PageSize}";
//echo $sql;

$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
	//echo "sfsdf<br>";
	$modal=$row['modal'];
	$count=$row["ItemCount"];
?>
<tr>
    <td height="24" align="center" valign="middle"><table width="100%" border="1" cellpadding="1" cellspacing="0" class="Industry">
<tr>
          <td width="51%" align="left"><?echo $modal;?>&nbsp;</td>
          <td width="49%" rowspan="3" align="left">
          <?
			$sql="select * from product_items where modal='{$row[modal]}'";
			$rh=mysql_query($sql);$index=0;
			while($item=mysql_fetch_array($rh))
			{
				//print_r($item);echo "<br>";
				$path="../function/PicSize.php?ImgPath=../pic/data/{$item['path']}&width=80&height=80";
				$pic="<a target='_parent' href='pic_view.php?id={$item['id']}'><img src='{$path}'/></a>";
				echo "<span>{$pic}</span>";
				$index++;
				if($index>3) break;
			}
		  ?>&nbsp;</td>
        </tr>
        <tr align="left">
          <td>&nbsp;</td>
        </tr>
        <tr align="left">
          <td><select name="select" onchange="Operate(this);">
            <option selected>选项</option>
            <option value="console_web_edit_modal.php?modal=<?echo $modal;?>">基础设置</option>
            <option value="page_eidt_content_in_pay_page.php?modal=<?echo $modal;?>">商城设置</option>
            <option value="<?echo $FileName?>?operate=SetTop&modal=<?echo $modal;?>">置顶</option>
            <option value="<?echo $FileName;?>?operate=DelModal&modal=<?echo $modal;?>">删除</option>
          </select></td>
        </tr>
    </table></td>
  </tr>
	<tr>
	<td height="24" align="center" valign="middle">&nbsp;</td>
</tr>
<?
}
//页面跳转按钮们
$PageCount=ceil($ItemCount/$PageSize);
$PageIndex=$PageOffset/$PageSize;
$page=$PageOffset+$PageSize;
if($page<$ItemCount)
	$NextPage="<a href='{$FileName}?PageOffset={$page}&keyword={$_GET[keyword]}'>下一页</a>";
else $NextPage="<a href='{$FileName}?PageOffset={$PageOffset}&keyword={$_GET[keyword]}'>下一页</a>";

$page=$PageOffset-$PageSize;
if($page>=0)
	$LastPage="<a href='{$FileName}?PageOffset={$page}&keyword={$_GET[keyword]}'>上一页</a>";
else $LastPage="<a href='{$FileName}?PageOffset=0&keyword={$_GET[keyword]}'>上一页</a>";
//***************
//页码串
$PageIndex+=1;
$Pages="";
$PageFrom=$PageIndex-2;
if($PageFrom<1) $PageFrom=1;
for($i=$PageFrom;$i<=$PageIndex+2 && $i<=$PageCount;$i++)
{

	$pos=($i-1)*$PageSize;
	$index=substr($i+100,1,2);
	$Pages.="<a href='{$FileName}?PageOffset={$pos}'>-{$index}-</a> ";
}
?>
</table>
<table width="100%" border="1" cellspacing="0" class="ProductTable">
  <tr>
    <th><table width="100%" border="0" cellpadding="1">
<tr>
          <td align="center"><?echo "{$LastPage}";?>&nbsp;</td>
          <td align="center"><?echo "<{$PageIndex}/{$PageCount}>";?>&nbsp;</td>
          <td align="center"><?echo "{$NextPage}";?>&nbsp;&nbsp;</td>
        </tr>
      
    </table></th>
  </tr>
  <tr>
    <th>&nbsp;</th>
  </tr>
</table>
</body>
</html>
<script>
function CreateModal()
{
	var obj=document.getElementById("name");
	if(obj.value.length==0)
	{
		alert("名称不能为空");
		return false;
	}
	return true;
}
function Search()
{
	var obj=document.getElementById("keyword");
	var keyword=obj.value;
	url="<?echo $FileName.'?keyword=';?>"+keyword;
	//alert(url);
	location=url;
		
	
	//else alert("不能为空！");
	return false;
}
function Operate(obj)
{
	var index=obj.selectedIndex;
	var url=obj.options[index].value;
	var text=obj.options[index].text;
	if(text=="删除" && confirm("确认删除吗？")==false)
	{
		return;
	}
	location=url;
	
}
</script>