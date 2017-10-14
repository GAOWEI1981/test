<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>

<?
error_reporting(E_ALL & ~E_NOTICE);
if(!isset($_SESSION)) session_start();

include_once("../function/config.php");
include_once("../function/JSSDK.php");
include_once("../function/Script.php");
include_once("LocalConfig.php");
/*
$data->Brand = "三恒";
$data->AppName = "products_sanheng";
$fp = fopen("ConfigInfo.inf", "w");
fwrite($fp, json_encode($data));
fclose($fp);
*/

if($Database->Connect()==false)
{
	echo "db connect false!";
	return;
}
mysql_select_db($DatabaseName);
//LogInFile("visit","Visit.txt");//统计访问量

$operate=$_GET['operate'];
switch($operate)
{
case "PayExe":
	$param[size]=$_GET[size];
	$param[count]=$_GET[count];
	$param[color]=$_GET[color];
	$param[modal]=$_GET[modal];
	$_SESSION[OrderParam]=serialize($param);
	if(!isset($_SESSION[adminID]))
	{
		
		//print_r($_SESSION);
		Header("Location:page_user_login.php?err=请登录");
	}
	else
	{
		//$PayPath="../alipay/normal/wappay/pay.php";
		Header("Location:page_orders_user.php");
	}
	//$_SESSION[EntranceUrl]="{$PayPath}?";
	return;
}
$CurModalInf=GetItemA("products","modal",$_GET[modal]);
$Price=GetPrice($_SESSION[adminID],$_GET[modal]);
$detail=$CurModalInf['detail'];//型号信息
$detail=str_replace("\r\n","<br>",$detail);

//print_r($CurModalInf);
$FileName=basename($_SERVER["PHP_SELF"]);
$config=file_get_contents("ConfigInfo.inf");
$brand=GetXMLParam($config,"brand");//获取本地设置
$SysName=GetXMLParam($config,"AppName");
//print_r($_GET);echo "<br>";

//session_unset();
//session_destroy();
$PageWidth="100%";
if($_SESSION[PageModal]=="computer")
{
	$PageWidth="600px";
}

?>
<title><?echo $_GET[modal];?>
</title>
<link rel="stylesheet" href="../function/industry.css"/>
<link rel="stylesheet" href="../function/weui/dist/style/weui.css"/>
<link rel="stylesheet" href="../function/weui/dist/style/weui.min.css"/>
</head>
<body ontouchstart>
<?
include_once("chunks/chunk_title.php");
include_once("chunks/chunk_modal_item_option_mall.php");
?>
<div class="weui_cells"  style="margin-top:0px;">
        <div class="weui_cell">
        <div class="weui_cell_bd weui_cell_primary">
        <table width="100%" border="0" cellpadding="1" cellspacing="0">
            <tr>
              <td width="41%" align="center"><table width="100%" border="0" cellpadding="1" cellspacing="0">
<tr>
                <td align="center"><a onclick="return subtraction();" href="#" class="weui_btn weui_btn_primary weui_btn_mini">-</a></td>
                <td align="center"><input type="text" style="width:40px; text-align:center;" name="count" id="count" value="1"/></td>
                <td align="center">
                  <a onclick="return additive();" href="#" width="100%" class="weui_btn weui_btn_primary weui_btn_mini">+</a>
                </td>
                </tr>
                
              </table></td>
              <td width="59%" align="center">&nbsp;</td>
            </tr>
          </table>
        </div>
        </div>
</div>


<div class="weui_btn_area">
   <a onclick="return PayExe(this);" class="weui_btn weui_btn_primary" href="<?echo "{$FileName}?operate=PayExe";?>">支付<label id="money"><?echo "￥".$Price;?></label></a>
</div>

</body>
</html>
<script src="../function/swiper/js/swiper.min.js"></script>
<script>
function subtraction()
{
	var obj=document.getElementById("count");
	var count=parseInt(obj.value)-1;
	//alert(count);
	if(count<1) count=1;
	obj.value=count;	
	
	
	var price=parseFloat("<?echo $Price;?>");
	var gross=count*price;
	gross=gross.toFixed(2);
	obj=document.getElementById("money");
	obj.innerText="￥"+gross;
	return false;
}
function additive()
{
	var obj=document.getElementById("count");
	var count=parseInt(obj.value)+1;
	obj.value=count;
	
	var price=parseFloat("<?echo $Price;?>");
	var gross=count*price;
	gross=gross.toFixed(2);
	obj=document.getElementById("money");
	obj.innerText="￥"+gross;
	return false;
}
function PayExe(obj)
{
	//alert("");
	try
	{
		var ops = document.getElementsByName("color");
		var color="";
		for(var i=0;i<ops.length;i++)
		{
			if(ops[i].checked)
			{
				color=ops[i].value;
			}
		}
		if(color.length==0)
		{
			alert("未选择规格！");
			return false;
		}
		
		
		var ops = document.getElementsByName("size");
		var size="";
		for(var i=0;i<ops.length;i++)
		{
			if(ops[i].checked)
			{
				size=ops[i].id;
				break;
			}
		}
		if(size.length==0 && ops.length!=0)
		{
			alert("未选择尺码！");
			return false;
		}
		
		var count = document.getElementById("count");
		count=count.value;
		
		var url=obj.href+"&size="+size+"&color="+color+"&count="+count+"&modal="+"<?echo $_GET[modal];?>";
		//alert(url);
		location.href=url;
		
	}catch(e)
	{
		alert(e);
	}
	return false;
}
</script>