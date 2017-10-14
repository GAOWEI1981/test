<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width"/>

<title>用户管理</title>
<link href="../function/industry.css" rel="stylesheet" type="text/css" />

</head>
<body>
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
$operate=$_GET[operate];
switch($operate)
{
case "":
	break;
case "update":
	print_r($_GET);echo "<br>";
	//$sql="";
	if(strlen($_GET[id])>0)
	{
		$value=intval($_GET[value], 10);
		if(strlen($value)==0) $value=0;
		$sql="update location set remark='{$value}' where id='{$_GET[id]}'";			
		 
		mysql_query($sql);
		echo "<script>alert('修改成功！');location='page_empty.php';</script>";
	}
	
	//Header("Location:page_empty.php");
	return;
}
/*
$time=time();
foreach($ProvinceList as $p)
{
	$sql="insert into location (name,time,remark) values('{$p}','{$time}','0')";
	mysql_query($sql);
	echo $sql."<br>";
}
*/
?>
<table width="100%" border="1" cellpadding="1" cellspacing="0" class="Industry">
<tr>
  <td colspan="3" align="center"><table width="100%" border="0" cellpadding="1" cellspacing="0">
<tr>
  <td align="center"><input type="text" name="keyword" style="width:80%" id="keyword" /></td>
  <td align="center"><input type="button" onclick="Search();" name="button" id="button" value="查找" /></td>
</tr>
<tr>
  <td align="center">&nbsp;</td>
  <td align="center">&nbsp;</td>
</tr>
<tr>
          <td width="61%" align="center"><input type="text" name="PlaceName" style="width:80%" id="PlaceName" /></td>
          <td width="39%" align="center"><input type="button" onclick="CreateNew();" name="button2" id="button2" value="添加" /></td>
        </tr>
      
  </table></td>
  </tr>
<tr>
  <td width="19%" align="center">省份</td>
    <td width="14%" align="center">额外收费</td>
    <td width="16%" align="center">&nbsp;</td>
  </tr>
  <?
  $sql="select * from location where name like '%{$_GET[keyword]}%' order by time desc";
  $result=mysql_query($sql);
  while($row=mysql_fetch_array($result))
  {
	
  ?>
  <tr>
    <td><?echo $row[name];?></td>
    <td align="center"><input id="value_<?echo $row[id];?>" style="width:80%" value="<?echo $row[remark];?>" /></td>
    <td align="center"><input type="button" id="<?echo $row[id];?>" onclick="Update(this);" value="提交" /></td>
  </tr>
  <?
  }
  ?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<iframe src="" name="exe" id="exe" width="100%" height="0px">
</iframe>
</body>
</html>
<script>
function Search()
{
	var keyword=document.getElementById("keyword").value;
	var url="<?echo $FileName;?>?keyword="+keyword;
	location=url;
}
function Update(obj)
{
	var id=obj.id;
	var url="<?echo $FileName;?>?operate=update&id="+id;
	var va;
	
	va=document.getElementById("value_"+id).value;
	url+="&value="+va;
	
	/*va=document.getElementById("first_"+id).value;
	url+="&cost_first="+va;
	
	va=document.getElementById("second_"+id).value;
	url+="&cost_second="+va;
	
	va=document.getElementById("card_"+id).value;
	url+="&cost_card="+va;
	
	//alert(url);*/
	var target=document.getElementById("exe");
	//alert(url);
	target.src=url;
}
function CreateNew()
{
	var name=document.getElementById("PlaceName").value;
	var url="<?echo $FileName;?>?operate=CreateNew&name="+name;
	alert(url);
	//location=url;
}
</script>


