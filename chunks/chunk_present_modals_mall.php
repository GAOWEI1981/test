<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<link rel="stylesheet" href="../function/industry.css"/>
<link rel="stylesheet" href="../function/weui/dist/style/weui.css"/>
<link rel="stylesheet" href="../function/weui/dist/style/weui.min.css"/>
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
	//$MoneyItem=GetItem("products","modal",$modal);
	include("chunk_present_modal_mall.php");
}
?>
<table width="100%" border="0" cellpadding="1">
  <tbody>
    <tr>
      <td height="40">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>
