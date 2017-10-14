<?
$sql="select * from product_items where modal='{$modal}'";

$result_unit=mysql_query($sql);
$item=GetItemA("products","modal",$modal);
//print_r($item);echo "<br>";


$PicHeightInPreview=300;
$mark=array();
$pics=array();
$i=0;
//LogInFile("{$PostXScale}<2>{$PostXScale}","size.txt");
while($r=mysql_fetch_array($result_unit))
{
	$mark[$i]=$r['color'];
	if($i==0) $w=$h=$PicHeightInPreview;
	else $w=$h=$PicHeightInPreview*0.6;
	$w=$h*1.2;
	
	$path="../function/PicSize.php?ImgPath=../pic/data/{$r['path']}&width={$w}&height={$h}&PostXScale={$PosXScale}&PostYScale=0";
	//echo"PostXScale={$PosXScale} PostYScale={$PosYScale}";
	
	//http://www.90worldengine.com/sys_cloth_orders/sys_products/ModalPage.php?id=138&Modal=513AB
	$pics[$i]="<a href='page_modal.php?{$TransValues}&modal={$modal}&ItemID={$r['id']}&PageOffset={$PageOffset}'>
				<img src='{$path}' width='100%'></img></a>";
	$i++;
}

?>
<link href="../../function/industry.css" rel="stylesheet" type="text/css">

<table width="100%" border="0" align="center" cellspacing="0" class="Industry">
  <tr>
    <td height="22" colspan="3" align="left"><table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
          <th width="46%" align="left"><?echo "{$item[remark]} {$modal}";?>&nbsp;</th>
          <th width="54%" align="left"><?echo $item['title'];?>&nbsp;</th>
        </tr>
        
    </table></td>
  </tr>
  <tr>
    <td width="55%" rowspan="4" align="center" valign="middle">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
          <td align="center" valign="middle"><?echo $pics[0];?><span></span></td>
        </tr>
    </table></td>
    <td height="40" align="center"><?echo $pics[1];?></td>
    <td height="40" align="center"><?echo $pics[2];?></td>
  </tr>
  <tr class="TimeText" >
    <td align="left"><?echo $mark[1];?>&nbsp;</td>
    <td align="left"><?echo $mark[2];?>&nbsp;</td>
  </tr>
  <tr>
    <td height="40" align="center"><?echo $pics[3];?></td>
    <td height="40" align="center"><?echo $pics[4];?></td>
  </tr>
  <tr class="TimeText">
    <td width="23%" align="left"><?echo $mark[3];?>&nbsp;</td>
    <td width="22%" align="left"><?echo $mark[4];?>&nbsp;</td>
  </tr>
</table>