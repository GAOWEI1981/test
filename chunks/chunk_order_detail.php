<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<link rel="stylesheet" href="../function/industry.css"/>
<link rel="stylesheet" href="../function/weui/dist/style/weui.css"/>
<link rel="stylesheet" href="../function/weui/dist/style/weui.min.css"/>
<?
$sql="select * from items where OrderID='{$_GET[OrderID]}'";
$result=mysql_query($sql);
$item=mysql_fetch_array($result);
$ar=explode("_",$item[Model]);
//print_r($item);
//$item=GetItemA("products","modal",$ar[0]);	

$r=mysql_query("select * from product_items where modal='{$ar[0]}' and color='{$ar[1]}'");
//echo "select * from product_items where modal='{$ar[0]}' and color='{$ar[1]}'";echo "<br>";
$item=mysql_fetch_array($r);
$path="../function/PicSize.php?ImgPath=../pic/data/{$item['path']}&width=50&height=50";
//echo $path;echo "<br>";
$ImgLink="<a href='page_modal_mall.php?{$TransValues}&modal={$item[modal]}&ItemID={$item['id']}'>
		<img src='{$path}' width='width:50px;height:50px;'></a>";
		
$MoneyInfo=GetItemA("account_book","OrderID",$_GET[OrderID]);
	//print_r($MoneyInfo);echo "<br>";
$OrderInfo=GetItemA("orders","ID",$_GET[OrderID]);

$UserInfo=GetItemA("signup_users","openid",$_SESSION[adminID]);
//print_r($UserInfo);echo "dfg<br>";

$sql="select *,count(id) as ItemCount from items where OrderID='{$_GET[OrderID]}' group by Size";
$result=mysql_query($sql);$count=0;
while($row=mysql_fetch_array($result))
{
	$count+=$row[ItemCount];
	$PruductModal=$row[Model];
}
	
$MoneyTotal=$count*$MoneyInfo[price];
?>
<div class="weui_cells" style="margin-top:5px">
<div class="weui_cell">
    <div class="weui_cell_hd" style="width:20%;"><label class="weui_label">发货人</label></div>
    <div class="weui_cell_bd weui_cell_primary">
        <input class="weui_input" name="shipper" id="shipper" type="text" placeholder="请输入您的姓名" value="<?echo $UserInfo[name];?>"/>
    </div>
</div>
</div>
<div class="weui_cells weui_cells_access" style="margin-top:5px">

    <a class="weui_cell" href="page_express.php?OrderID=<?echo $_GET[OrderID];?>">
        <div class="weui_cell_bd weui_cell_primary">
            <table width="100%" border="0" cellpadding="1">
            <tbody>
            <tr>
            <td width="6%" rowspan="2" align="center"><img src="../icon/icon_location.png" alt="" style="width:20px;"></td>
            <td width="6%" align="left">&nbsp;</td>
            <td width="39%" align="left"><?if(strlen($OrderInfo[receiver])>0) echo $OrderInfo[receiver];else echo "请填写收件人";?></td>
            <td width="38%" align="left"><?if(strlen($OrderInfo[phone])>0) echo $OrderInfo[phone];else echo "请填电话";?></td>
            <td width="11%" rowspan="2" align="center">&nbsp;</td>
            </tr>
            <tr>
            <td align="left">&nbsp;</td>
            <td colspan="2" align="left"><?if(strlen($OrderInfo[address])>0) echo $OrderInfo[address];else echo "请填写收货地址";?></td>
            </tr>
            </tbody>
            </table>
        </div>
        <div class="weui_cell_ft">
        </div>
    </a>
   <a class="weui_cell" href="page_orders_user.php">
        <div class="weui_cell_bd weui_cell_primary">
            <table width="100%" border="0" cellpadding="1">
            <tbody>
            <tr>
              <td width="8%" rowspan="3" align="center"><?echo $ImgLink;?></td>
              <td align="left"><?echo $PruductModal;?></td>
              <td width="11%" rowspan="3" align="center">&nbsp;</td>
            </tr>
            <tr>
            <td width="40%" align="left">单价：<?echo $MoneyInfo[price];?></td>
            </tr>
            </tbody>
            </table>
        </div>
        <div class="weui_cell_ft">
        </div>
  </a>
</div>

<div class="weui_cells" style="margin-top:5px">
<div class="weui_cell">
    <div class="weui_cell_bd weui_cell_primary">
        商品数量
    </div>
    <div class="weui_cell_ft">
        <?echo $count;?>
    </div>
</div>
<div class="weui_cell">
    <div class="weui_cell_bd weui_cell_primary">
        商品金额
    </div>
    <div class="weui_cell_ft">
        ￥<?echo $MoneyTotal;?>
    </div>
</div>
<div class="weui_cell">
    <div class="weui_cell_bd weui_cell_primary">
        快递附加费
    </div>
    <div class="weui_cell_ft">
        ￥
		<?
		$index=strpos($OrderInfo[address],"-");
		$province=substr($OrderInfo[address],0,$index);
		$sql="select * from location where name='{$province}'";
		//echo $sql;echo "<br>";
		$result=mysql_query($sql);
		$item=mysql_fetch_array($result);
		if(strlen($item[remark])==0 || $count>1)
			$ExpressMoney=0;
		else
	        $ExpressMoney=$item[remark];
		$sql="update account_book set cost_express='{$ExpressMoney}' where OrderID='{$_GET[OrderID]}'";
		mysql_query($sql);
		//echo $sql;echo "<br>";
		echo $ExpressMoney;
		?>
    </div>
</div>
<div class="weui_cell">
    <div class="weui_cell_bd weui_cell_primary">
        合计
    </div>
    <div class="weui_cell_ft">
        ￥
		<?
			
			$total=$MoneyTotal+$ExpressMoney;
			$sql="update account_book set gross='{$total}' where OrderID='{$_GET[OrderID]}'";
			mysql_query($sql);
			echo $total;
		?>
    </div>
</div>
</div>
<div class="weui_cells" style="margin-top:5px">
          <div class="weui_cell">
            <div class="weui_cell_bd weui_cell_primary">
            <textarea class="weui_input" style="height:60px;" name="remark" id="remark" type="text" placeholder="请输入留言"><?echo $OrderInfo[remark];?></textarea></div>
            <div class="weui_cell_ft">
            </div>
          </div>
         
</div>
<?

if(strlen($_GET[OutState])>0)
{
	
}
else
{
	$sql="select * from account_book where OrderID='{$_GET[OrderID]}' and (type='Loan' or type='loan')";
	$r=mysql_query($sql);
	$LoanState=mysql_num_rows($r);
	if($LoanState>0)//如果没有支付就显示支付入口
	{
		?>
			<div class="weui_btn_area">
			<a class="weui_btn weui_btn_primary" onclick="return CheckData(this);" href="<?echo "{$FileName}?operate=PayExe&OrderID={$_GET[OrderID]}";?>">支付</a>
			</div>
		<?
	}
}
?>

<script>

function CheckData(obj)
{
	
	var receiver="<?echo $OrderInfo[receiver];?>";
	var phone="<?echo $OrderInfo[phone];?>";
	var address="<?echo $OrderInfo[address];?>";
	var shipper=document.getElementById("shipper").value;
	if(shipper.length==0)
	{
		alert("发货人不能为空！");
		return false;
	}
	if(receiver.length==0 || phone.length==0 || address.length==0)
	{
		alert("收货地址信息不完整！");
		return false;
	}
	obj.href+="&shipper="+shipper+"&remark="+document.getElementById("remark").value;
	//alert(obj.href);
	return true;
}

</script>