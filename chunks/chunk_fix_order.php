<link href="../../function/industry.css" rel="stylesheet" type="text/css">
<?
$UserInfo=GetItemA("signup_users","openid",$_SESSION[adminID]);
$OrderInfo=unserialize($_SESSION[OrderParam]);
$CurrentTime=time();
$TeamName="网站_{$UserInfo[phone]}_{$CurrentTime}";
$ID=CreateID(10)."_{$CurrentTime}";

/*if(mysql_query($sql))//插入成功
{
	//$TeamName=$_GET['TeamName'];
	//$modal=$_GET['Modal'];
	//header("Location:ClientOperate.php?OrderID={$ID}&TeamName={$TeamName}&modal={$modal}");//跳转到订单页
	return $ID;
}*/

//$_SESSION[OrderParam]="";
//$operate="CreateOrder";

//$_SESSION[OrderParam]="";
//Header("Location:{$FileName}?operate=CreatOrder");

//图片链接
$sql="select * from product_items where modal='{$OrderInfo[modal]}' and color='{$OrderInfo[color]}'";
$r=mysql_query($sql);
//echo $sql;echo "<br>";
$item=mysql_fetch_array($r);
$path="../function/PicSize.php?ImgPath=../pic/data/{$item['path']}&width=100&height=100";
//echo $path;echo "<br>";
$ImgLink="<a href='page_modal.php?{$TransValues}&modal={$item[modal]}&ItemID={$item['id']}'>
		<img src='{$path}' width='100px' height='100px'></a>";
		
?>
<form action="<?echo $FileName;?>?operate=FixOrder" id="FixForm" name="FixForm" method="post">
  <table width="100%" border="1" cellpadding="1" cellspacing="0" class="Industry">
    <tr>
      <td width="24%" rowspan="4" align="center"><?echo $ImgLink;?>&nbsp;</td>
      <th width="24%" align="left">货品</th>
      <td width="62%"><?echo "{$OrderInfo[modal]} {$OrderInfo[color]}";?></td>
    </tr>
    <tr>
      <th align="left">数量</th>
      <td><?echo "{$OrderInfo[count]}";?></td>
    </tr>
    <tr>
      <th align="left">收件人</th>
      <td align="center"><input type="text" style="width:80%;" name="receiver" value="<?echo $UserInfo[name];?>" id="receiver"></td>
    </tr>
    <tr>
      <th align="left">联系电话</th>
      <td align="center"><input type="text" style="width:80%;" name="phone" id="phone" value="<?echo $UserInfo[phone];?>"></td>
    </tr>
    <tr>
      <th colspan="3" align="center">收货地址</th>
    </tr>
    <tr>
      <td colspan="3" align="center"><textarea type="text" style="width:90%;height:50px;" name="address" id="address"></textarea></td>
    </tr>
    <tr>
      <td colspan="3" align="center"><table width="100%" border="0" cellpadding="1" cellspacing="0" class="Industry">
<tr>
            <td width="50%" align="center"><input type="submit" onclick="return BrowserJudge();" name="submit" id="submit" value="提交"></td>
            <td width="50%" align="center"><input type="button" name="cancel" id="cancel" value="作废" onClick="CancelOrder();"></td>
          </tr>
          
      </table></td>
    </tr>
  </table>
</form>
<script>
function CancelOrder()
{
	if(confirm("确定要作废吗？"))
	{
		var url="<?echo $FileName.'?operate=CancelOrder';?>";
		//alert(url);
		location=url;
	}
}

function BrowserJudge()
{
	var string=navigator.userAgent;
	string=string.toLowerCase();
	//alert(string);
	try
	{
		//alert("");
		var address=document.getElementById("address").value;
		var receiver=document.getElementById("receiver").value;
		var phone=document.getElementById("phone").value;
		if(address=="" || receiver=="" || phone=="")
		{
			alert("收货地址、收件人、联系电话都不能为空");
			return false;
		}
		if(string.indexOf("mqqbrowser")>=0)//微信
		{
			var obj=document.getElementById("FixForm");
			obj.action=obj.action+"&browser=weixin";
			
			//var obj=document.getElementById("PayMessage");
			//obj.style.display="";
		}
	}catch(e)
	{
		alert(e);
		return false;
	}
	//return false;
}


</script>