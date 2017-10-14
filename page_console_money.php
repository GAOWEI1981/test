<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width"/>

<title>用户管理</title>
<link href="../function/industry.css" rel="stylesheet" type="text/css" />

</head>
<body>
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
include_once("chunks/chunk_console_title.php");
$operate=$_GET[operate];
switch($operate)
{
case "":
	break;
case "update":
	print_r($_GET);echo "<br>";
	//$sql="";
	$keys=array_keys($_GET);
	foreach($keys as $key)
	{
		if($key!="modal" && $key!="operate")
		{
			$value=$_GET[$key];
			if(strlen($value)>0)
			{
				$sql="update products set {$key}='{$value}' where modal='{$_GET[modal]}'";			
				//echo $sql;echo "<br>";
				mysql_query($sql);
			}
		}
	}
	echo "<script>alert('修改成功！');location='page_empty.php';</script>";
	//Header("Location:page_empty.php");
	return;
}
?>
<table width="100%" border="1" cellpadding="1" cellspacing="0" class="Industry">
<tr>
  <td colspan="6" align="center"><table width="100%" border="0" cellpadding="1" cellspacing="0">
<tr>
          <td width="61%" align="center">
          <input type="text" name="keyword" style="width:80%" id="keyword" /></td>
          <td width="39%" align="center"><input type="button" onclick="Search();" name="button" id="button" value="查找" /></td>
        </tr>
      
  </table></td>
  </tr>
<tr>
  <td width="19%" align="center">&nbsp;</td>
    <td width="19%" align="center">产品</td>
    <td width="14%" align="center">成本</td>
    <td width="16%" align="center">一级</td>
    <td width="15%" align="center">二次购价格</td>
    <td width="16%" align="center">标价</td>
  </tr>
  <?
  $sql="select * from products where modal like '%{$_GET[keyword]}%' order by time desc";
  $result=mysql_query($sql);
  while($row=mysql_fetch_array($result))
  {
	$sql="select * from product_items where modal='{$row[modal]}' and color<>''";
	$r=mysql_query($sql);
	//echo $sql;echo "<br>";
	$item=mysql_fetch_array($r);
	if(strlen($item[path])>0)
	{
		$path="../function/PicSize.php?ImgPath=../pic/data/{$item['path']}&width=100&height=100";
		//echo $path;echo "<br>";
		$ImgLink="<a href='page_modal.php?{$TransValues}&modal={$item[modal]}&ItemID={$item['id']}'>
				<img src='{$path}' width='60px' height='60px'></a>";
	}
	else $ImgLink="";
  ?>
  <tr>
    <td rowspan="2" align="center"><?echo $ImgLink;?></td>
    <td><?echo $row[modal];?></td>
    <td align="center"><input id="cost_<?echo $row[modal];?>" style="width:30px" value="<?echo $row[cost];?>" /></td>
    <td align="center"><input id="first_<?echo $row[modal];?>" style="width:30px" value="<?echo $row[cost_first];?>" /></td>
    <td align="center"><input id="second_<?echo $row[modal];?>" style="width:30px" value="<?echo $row[cost_second];?>" /></td>
    <td align="center"><input id="card_<?echo $row[modal];?>" style="width:30px" value="<?echo $row[cost_card];?>" /></td>
  </tr>
  <tr>
    <td colspan="5" align="right"><input type="button" id="<?echo $row[modal];?>" onclick="Update(this);" value="提交" /></td>
  </tr>
  <?
  }
  ?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
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
	var url="<?echo $FileName;?>?operate=update&modal="+id;
	var va;
	
	va=document.getElementById("cost_"+id).value;
	url+="&cost="+va;
	
	va=document.getElementById("first_"+id).value;
	url+="&cost_first="+va;
	
	va=document.getElementById("second_"+id).value;
	url+="&cost_second="+va;
	
	va=document.getElementById("card_"+id).value;
	url+="&cost_card="+va;
	
	//alert(url);
	var target=document.getElementById("exe");
	target.src=url;
}
</script>


