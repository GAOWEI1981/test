<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/> 
<link href="../function/InterfaceStyle.css" rel="stylesheet" type="text/css" />
<body class="BodyNoSpace">
<?php
error_reporting(E_ALL & ~E_NOTICE);
if(!isset($_SESSION)) session_start();
header("Content-Type:text/html;charset=utf-8");
include_once("../function/config.php");
include_once("../function/Script.php");
date_default_timezone_set("ETC/GMT-8"); 
//echo PageHead("OrderOperate.php","球服参数");

if($Database->Connect()==false)
{
	echo "db connect false!";
	return;
}

mysql_select_db($DatabaseName);
$product=$_GET["product"];
$operate=$_GET['operate'];
switch($operate)
{
case "ListAllPics":
	//print_r($_GET);
	$PicSize=$_GET['PicSize'];
	$product=$_GET['product'];
	//$item=GetItemA("products","modal",$product);
	//print_r($item);
	$sql="select * from product_items where modal='{$product}'";
	//echo $sql;
	$rh=mysql_query($sql);
	$ItemCount=mysql_num_rows($rh);
	if($ItemCount==0) return;
	else break;
}
?>
<table id="album" width="100%" border="1" cellspacing="0" class="ProductTable">
  <tr>
  	<?
		while($item=mysql_fetch_array($rh))
		{
			//print_r($item);
			//echo "<br>";
			$path="../function/PicSize.php?ImgPath=../pic/data/{$item['path']}&width={$PicSize}&height={$PicSize}";
			$pic="<a target='_parent' href='pic_view.php?id={$item['id']}'><img src='{$path}'/></a>";
			echo "<td align='center'>{$pic}</td>";
		}
	?>
  </tr>
</table>
</body>
<script>

var BodyHeight=$("album").style.height;
//alert(BordyHeight);
/*var pro="<?echo $product;?>";
parent.SetPicAreaHeight(pro,BodyHeight);//让主窗口控制尺寸*/
//alert(pro);
//window.offsetHeight="150px";
</script>


