<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<title>订单详情</title>
<link rel="stylesheet" href="../function/industry.css"/>
<link rel="stylesheet" href="../function/weui/dist/style/weui.css"/>
<link rel="stylesheet" href="../function/weui/dist/style/weui.min.css"/>
</head>
<body ontouchstart>
<?
ob_start();
error_reporting(E_ALL & ~E_NOTICE);
if(!isset($_SESSION)) session_start();
include_once("../function/config.php");
include_once("LocalConfig.php");

$operate=$_GET['operate'];
//**************/
$_SESSION[LastPage]="";
$FileName=basename($_SERVER["PHP_SELF"]);
if($Database->Connect()==false)
{
	echo "db connect false!";
	return;
}

mysql_select_db($DatabaseName);
$OrderInfo=GetItemA("orders","ID",$_GET[OrderID]);
switch($operate)
{
case "FllAddress":
	if(strlen($_GET[OrderID])>0)
	{
		if(strlen($_GET[receiver])>0 && strlen($_GET[address])>0 && strlen($_GET[phone])>0)
		{
			$sql="update orders set receiver='{$_GET[receiver]}',address='{$_GET[address]}',phone='{$_GET[phone]}' where ID='{$_GET[OrderID]}'";
			echo $sql;echo "<br>";
			mysql_query($sql);
		}
		
		
		Header("Location:page_order_detail.php?OrderID={$_GET[OrderID]}");
	}
	return;
case "FixAddress":
	//print_r($_POST);echo "<br>";
	//print_r($_GET);echo "<br>";
	if(strlen($_GET[OrderID])>0)
	{

		if(strlen($_POST[receiver])>0 && strlen($_POST[address])>0 && strlen($_POST[phone])>0)
		{
			$sql="update orders set receiver='{$_POST[receiver]}',address='{$_POST[province]}-{$_POST[address]}',phone='{$_POST[phone]}' where ID='{$_GET[OrderID]}'";
			//echo $sql;echo "<br>";
			mysql_query($sql);
		}
		else
		{
		}
		Header("Location:page_order_detail.php?OrderID={$_GET[OrderID]}");
	}
	return;
}
$BackUrl="page_order_detail.php?OrderID={$_GET[OrderID]}";
$Icon="../icon/icon_location.png";
include_once("chunks/chunk_back_bar.php");
?>
 

<form action="<?echo $FileName;?>?operate=FixAddress&OrderID=<?echo $_GET[OrderID];?>" method="post" name="FixAddress" id="FixAddress">
  <div class="weui_cells_title"><?echo $_GET[err];?></div>
    <div class="weui_cells weui_cells_form">
        <div class="weui_cell">
            <div class="weui_cell_hd" style="width:20%;"><label>收件人</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" name="receiver" id="receiver" type="text" value="<?echo $OrderInfo[receiver];?>" placeholder="请输入收件人姓名"/>
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_hd" style="width:20%;"><label>电话</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" name="phone" id="phone" type="text" value="<?echo $OrderInfo[phone];?>" placeholder="请输入联系电话"/>
            </div>
        </div>
        <div class="weui_cell">
          <div class="weui_cell_hd" style="width:20%;">地址</div>
            <div class="weui_cell_bd weui_cell_primary">
              <div class="weui_cell weui_cell_select">
                    <div class="weui_cell_bd" style="width:100%;">
                    <?
                    	$index=strpos($OrderInfo[address],"-");
						$address=substr($OrderInfo[address],$index+1,strlen($OrderInfo[address])-$index);
						$CurProvince=substr($OrderInfo[address],0,$index);
						//echo $CurProvince;echo "<br>";
					?>
                    <select name="province" id="province" class="weui_select" style="width:90%;">
                    <option>省份列表</option>
                    <?
						$sql="select * from location";
						$result=mysql_query($sql);
						
						//foreach($ProvinceList as $province)
						while($row=mysql_fetch_array($result))
						{
							$province=$row[name];
							if($CurProvince==$province)
							{
								echo "<option selected='selected'>{$province}</option>";
							}
							else
								echo "<option>{$province}</option>";
						}
                    
                    ?>
                    </select>
                    </div>
                </div>
                <div class="weui_cell weui_cell_bd">
                    <input class="weui_input" name="address" id="address" type="text" value="<?echo $address;?>" placeholder="请输入地址"/>
                </div>
                
          </div>
        </div>
    </div>
    <div class="weui_btn_area">
        <a class="weui_btn weui_btn_primary" onclick="return FixRecevierInfo();" href="javascript:">确定</a>
    </div>
    
    <div class="weui_cells_title">我的收货地址</div>
    <div class="weui_cells weui_cells_access" style="margin-top:5px">
    <?
    $sql="select ID,receiver,phone,address,time from orders where creater='{$_SESSION[adminID]}' and address<>'' and ID<>'{$_GET[OrderID]}'";
	//echo $sql;echo "<br>";
	$sql="select a.ID,a.receiver,a.phone,a.address,max(a.time) as time from ({$sql}) a group by a.address";
	$sql="select a.* from ({$sql}) a order by time desc";
	
	//$sql="select a.* from ({$sql}) a  order by a.time desc";
	//$sql="select a.* from orders a group by address";
	$result=mysql_query($sql);
	while($row=mysql_fetch_array($result))
	{
		
		$time=date("Y-m-d H:i:s",$row[time]);
		?>
        
      <a class="weui_cell" href="#" id="<?echo $row[ID];?>" onclick="return CopyAddress(this);">
                <div class="weui_cell_bd weui_cell_primary">
                    <table width="100%" border="0" cellpadding="1" cellspacing="0">
                    <tr>
                    <td><lable id="<?echo $row[ID]."_receiver";?>"><?echo $row[receiver];?></label>&nbsp;</td>
                    </tr>
                    <tr>
                    <td><label id="<?echo $row[ID]."_phone";?>"><?echo $row[phone];?></label>&nbsp;</td>
                    </tr>
                    <tr>
                      <td><label id="<?echo $row[ID]."_address";?>"><?echo $row[address];?></label>
                      &nbsp;</td>
                    </tr>
                    <tr>
                    <td><?echo $time;?>&nbsp;</td>
                    </tr>
                    </table>
                
                </div>
                <div class="weui_cell_ft">
                </div>
            </a>
        
		<?
	}
	?>
    </div></div>
</form>
</body>
</html>
<script>
function FixRecevierInfo()
{
	try
	{
		var form=document.getElementById("FixAddress");
		var data=document.getElementById("receiver").value;
		if(data.length==0){alert("收件人不能为空");return false}
		
		data=document.getElementById("phone").value;
		if(data.length==0){alert("电话不能为空");return false}
		
		data=document.getElementById("address").value;
		if(data.length==0){alert("地址不能为空");return false}
		
		data=document.getElementById("province").selectedIndex;
		if(data==0){alert("省份不能为空");return false}
		
		
		form.submit();
	}catch(e)
	{
		alert(e);
	}
	return true;
}
function CopyAddress(obj)
{
	
	var receiver=document.getElementById(obj.id+"_receiver").innerText;
	//alert(receiver);
	var phone=document.getElementById(obj.id+"_phone").innerText;
	//alert(receiver);
	var address=document.getElementById(obj.id+"_address").innerText;
	//alert(receiver);
	if(confirm("收货地址：\n'"+receiver+" "+phone+"\n"+address+"'\n填入当前订单吗？"))
	{
		var url="<?echo $FileName;?>?operate=FllAddress&address="+address+"&phone="+phone+"&receiver="+receiver;
		url+="&OrderID=<?echo $_GET[OrderID];?>";
		//alert(url);
		location=url;
	}
	//alert(url);
	
	return false;
}
</script>