
<div class="weui_cells_title" style="height:80px;">&nbsp;</div>
<?
$result=mysql_query($sql);$GrossTag=0;
while($row=mysql_fetch_array($result))
{
    $time=date("Y-m-d H:i:s",$row[time]);
    
    //图片链接
	$sql="select * from product_items where modal='{$row[product]}' order by time";
	//echo $sql;echo "<br>";
    $r=mysql_query($sql);
    $item=mysql_fetch_array($r);

	$path="../function/PicSize.php?ImgPath=../pic/data/{$item['path']}&width=80&height=80";
    $ImgLink="<img src='{$path}'>";
    //print_r($row);echo "<br>";
    $ProductLink="page_modal_mall.php?modal={$row[product]}";
    
    $OrderLink="<a href='page_order_detail.php?OrderID={$row[ID]}'>订单详情</a>";
    
    $GrossTag+=$row[MoneyTotal];
    //金额
    ?>
    <div class="weui_cells weui_cells_access" style="margin-top:5px">
    <a class="weui_cell" href="<?echo $ProductLink;?>">
        <div class="weui_cell_bd weui_cell_primary">
            <p><?echo $row[product];?></p>
        </div>
        <div class="weui_cell_ft">
        </div>
    </a>
    <a class="weui_cell" href="page_order_detail.php?OrderID=<?echo $row[ID];?>">
        <div class="weui_cell_bd weui_cell_primary">
            <table width="100%" border="0" cellpadding="1" cellspacing="0">
            <tbody>
            <tr>
            <td width="24%" rowspan="4" align="center"><?echo $ImgLink;?></td>
            <td width="76%"><?echo $row[ID]?></td>
            </tr>
            <tr>
              <td align="left"><?echo $time;?></td>
            </tr>
            <tr>
            <td align="right">￥<?echo $row[MoneyTotal];?></td>
            </tr>
            </tbody>
            </table>

    </div>
        <div class="weui_cell_ft">
        </div>
    </a>
    <?
    //支付状态
    $sql="select * from account_book where OrderID='{$row[ID]}' and (type='Loan' or type='loan')";
    $r=mysql_query($sql);
    $LoanState=mysql_num_rows($r);
    if($LoanState>0)
    {
        $DelLink="{$FileName}?operate=DelOrder&OrderID={$row[ID]}";
		?>
        <a class="weui_cell" href="<?echo $DelLink;?>" onClick="return del();">
            <div class="weui_cell_bd weui_cell_primary">
            <img src="../icon/icon_dustbin.png" alt="" style="width:20px;margin-right:5px;display:block">
            </div>
            <div class="weui_cell_ft">
            </div>
        </a>
        <?
    }
	else
	{
		$item=GetItemA("orders","ID",$row[ID]);
		//print($item);
		$ComID="";
		foreach($PostWay as $inf)
		{
			if($item[PostWay]==$inf[0])
			{
				$ComID=$inf[1];
				
			}
		
			//echo "555<br>";
		}
		//echo $ComID;echo "4444<br>";
		$ExpressLink="https://www.kuaidi100.com/chaxun?com={$ComID}&nu={$item[PostNum]}";
		?>
      <a class="weui_cell" href="<?echo $ExpressLink;?>">
            <div class="weui_cell_bd weui_cell_primary">
            <table width="100%" border="0" cellpadding="2">
              <tbody>
                <tr>
                  <td width="24%"><img src="../icon/icon_express.png" alt="" style="width:30px;margin-right:5px;display:block"></td>
                  <td width="46%"><?echo $item[PostWay];?>&nbsp;</td>
                  <td width="30%"><?echo $item[PostNum];?></td>
                </tr>
              </tbody>
            </table>
          </div>

      <div class="weui_cell_ft">
            </div>
        </a>
        <?
	}
	?>
    
    </div>
    
  <?
}


?>
 <div class="weui_cells" style="margin-top:5px">
        <div class="weui_cell_bd weui_cell_primary">
        </div>
        <div class="weui_cell_ft">
           ￥<?echo $GrossTag;?>
        </div>
 </div>
