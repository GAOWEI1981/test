<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<link href="../function/InterfaceStyle.css" rel="stylesheet" type="text/css" />
<?
$FileName=basename($_SERVER["PHP_SELF"]);

$PageSize=6;
$keyword=$_GET['keyword'];

//款式条目总数
$result=mysql_query($sql);
$res =  mysql_query("select found_rows()");
$ItemCount = mysql_result($res,0);
//页面跳转按钮们
$PageCount=ceil($ItemCount/$PageSize);
$PageIndex=$PageOffset/$PageSize;
$page=$PageOffset+$PageSize;

$TransValues="ViewModal={$_GET[ViewModal]}&GroupID={$_GET[GroupID]}&keyword={$keyword}";//需要传送的参数

if($page<$ItemCount)
	$NextPage="<a href='index.php?{$TransValues}&PageOffset={$page}'>下一页</a>";
else $NextPage="<a href='index.php?{$TransValues}&PageOffset={$PageOffset}'>下一页</a>";

$page=$PageOffset-$PageSize;
if($page>=0)
	$LastPage="<a href='index.php?{$TransValues}&PageOffset={$page}'>上一页</a>";
else $LastPage="<a href='index.php?{$TransValues}&PageOffset={$PageOffset}'>上一页</a>";
//***************
//页码串
$PageIndex+=1;
$Pages="";
$PageFrom=$PageIndex-2;
if($PageFrom<1) $PageFrom=1;
for($i=$PageFrom;$i<=$PageIndex+2 && $i<=$PageCount;$i++)
{

	$pos=($i-1)*$PageSize;
	$index=substr($i+100,1,2);
	$Pages.="<a href='index.php?PageOffset={$pos}&keyword={$keyword}'>-{$index}-</a> ";
}
//******************
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
	$modal=$row['modal'];
	//echo $modal;
	//echo "<br>";
	include("chunk_present_modal_preview.php");
}
//session_unset();
//session_destroy();
//{$Pages}<br>


if(strlen($PageWidth)==0) $ChunkModalsWidth="100%";
else $ChunkModalsWidth=$PageWidth;
?>
<div align="center" width="100%">
<table width="<?echo $ChunkModalsWidth;?>" border="0" cellspacing="0" class="ProductTable">
  <tr>
    <th width="33%" class="LinkTD"><?echo "{$LastPage}";?></th>
    <th width="33%"><?echo "{$PageIndex}/{$PageCount}";?></th>
    <th width="33%" class="LinkTD"><?echo "{$NextPage}";?></th>
  </tr>
</table>
</div>
<script>

</script>