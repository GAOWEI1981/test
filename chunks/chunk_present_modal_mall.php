<?
$sql="select * from product_items where modal='{$modal}' order by time";

$result_unit=mysql_query($sql);
$item=GetItemA("products","modal",$modal);
//print_r($item);echo "<br>";


$PicHeightInPreview=300;
$mark=array();
$pics=array();
$links=array();
$i=0;
//LogInFile("{$PostXScale}<2>{$PostXScale}","size.txt");
while($r=mysql_fetch_array($result_unit))
{
	$mark[$i]=$r['color'];
	if($i==0) $w=$h=$PicHeightInPreview;
	else $w=$h=$PicHeightInPreview*0.6;
	$w=$h*1.2;
	
	//$path="../function/PicSize.php?ImgPath=../pic/data/{$r['path']}&width={$w}&height={$h}&PostXScale={$PosXScale}&PostYScale=0";
	$path="../pic/data/{$r['path']}";

	$links[$i]="page_modal_mall.php?{$TransValues}&modal={$modal}&ItemID={$r['id']}&PageOffset={$PageOffset}";
	$pics[$i]="<a href='{$links[$i]}'>
				<img src='{$path}' style='max-width:100%;max-height:100%'></img></a>";
	$i++;
}

?>

<div class="weui_cells" style="margin-top:5px">
    <div class="weui_cell_bd weui_cell_primary">
    <table width="100%" border="0" cellpadding="1" cellspacing="0">
    <tr>
    <td width="100%"><?echo $pics[0];?></td>
    </tr>
    </table>
  </div>
</div>
