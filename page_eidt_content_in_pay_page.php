<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="../function/industry.css" rel="stylesheet" type="text/css" />
<head>
<title><?echo $_GET[modal];?>
</title>
</head>
<body class="Industry">
<?
ob_start();
error_reporting(E_ALL & ~E_NOTICE);
if(!isset($_SESSION)) session_start();

include_once("../function/config.php");
include_once("../function/JSSDK.php");
include_once("../function/Script.php");

/*
$data->Brand = "三恒";
$data->AppName = "products_sanheng";
$fp = fopen("ConfigInfo.php", "w");
fwrite($fp, json_encode($data));
fclose($fp);*/

$FileName=basename($_SERVER["PHP_SELF"]);

//必须要管理员才能登陆
if(!isset($_SESSION['adminID']))
{
	//Header("Location:Login.php");
	//$operate="login";
	Header("Location:Login.php");
}
if($Database->Connect()==false)
{
	echo "db connect false!";
	return;
}

mysql_select_db($DatabaseName);

$modal=$_GET['modal'];
$operate=$_GET['operate'];


switch($operate)
{
case "":
	break;
case "ChangeParam":
	print_r($_GET);echo "<br>";
	print_r($_POST);echo "<br>";
	
	$content=$_POST[params];
	$content=explode(" ",$content);
	
	foreach($content as $word)
	{
		if(strlen($word)>0)
			$ar[]=$word;
	}
	//$data=json_encode($ar);
	$data=serialize($ar);
	$sql="update product_items set mall_param='{$data}' where id='{$_GET[id]}'";
	mysql_query($sql);
	//echo $sql;echo "<br>";
	echo "<script>alert('修改成功');</script>";
	return;
}
$LastPage="console_web.php";
include_once("chunks/chunk_console_title.php");
?>
<table width="100%" border="1" cellpadding="1" class="Industry">
<?
	$size=100;
    $sql="select * from product_items where modal='{$modal}' order by time desc";
	//echo $sql;echo "<br>";
	$result=mysql_query($sql);
	while($row=mysql_fetch_array($result))
	{
		$path="../function/PicSize.php?ImgPath=../pic/data/{$row['path']}&width={$size}&height={$size}";
		$param=unserialize($row[mall_param]);
		$content="";
		foreach($param as $p)
		{
			if(strlen($p)>0)
			{
				$content.="{$p} ";
			}
		}
		?>
	  <tr>
	    <td width="3%" rowspan="2"><table width="100%" border="1" cellpadding="1">
          <tbody>
	          <tr>
	            <td><img src="<?echo $path;?>"/></td>
              </tr>
	          <tr>
	            <td align="center"><?echo $row[color];?></td>
              </tr>
          </tbody>
        </table></td>
	    <td width="77%"><form action="<?echo "{$FileName}?operate=ChangeParam&id={$row[id]}";?>" id="<?echo "form_{$row[id]}";?>" method="post" target="ExeWnd">
	      <textarea id="param" name="params" style="width:100%;height:60px;"><?echo $content;?></textarea>
        </form></td>
      </tr>
	  <tr>
	    <td align="right"><input onClick="ChangeParam(this);" type="button" name="<?echo $row[id];?>" value="提交"></td>
  </tr>
	  <?
	}
	?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<iframe src="" id="ExeWnd" name="ExeWnd" style="height:0px;width:100%"></iframe>
</body>
</html>
<script src="../function/swiper/js/swiper.min.js"></script>
<script>
function ChangeParam(obj)
{
	var id="form_"+obj.name;
	//alert(id);
	var form=$("#"+id);
	//alert(form.attr("action"));
	form.submit();
}
</script>