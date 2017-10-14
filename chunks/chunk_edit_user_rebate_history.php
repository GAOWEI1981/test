<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<link href="../function/industry.css" rel="stylesheet" type="text/css" />
<?

/*$FileName=basename($_SERVER["PHP_SELF"]);
$UserInfo=GetItemA("signup_users","openid",$_GET[id]);
//print_r($UserInfo);echo "<br>";
$name=$UserInfo['name'];*/
$rs=$LocalRebate->GetRebate($_GET[id]);
$OneClassRebate=$rs[0];
$TwoClassRebate=$rs[1];
//echo $Twokey;echo "<br>";
$item=$LocalRebate->GetLastRebate($_GET[id]);
//print_r($item);echo "<br>";
$time=$item[time];//获取最近一次分红时间
//echo date("Y-m-d H:i:s",$time);//echo " {$time}<br>";
$RebateInfo=$LocalRebate->GetRebateInfo($_GET[id],$time);
print_r($RebateInfo);echo "<br>";

?>
<form action="<?echo $FileName;?>?operate=RebateExe&id=<?echo $_GET[id];?>" method="post" name="RebateEdit" class="Industry" id="RebateEdit">
  <table width="100%" border="1" cellspacing="0">
    <tr>
      <td colspan="2" align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="44%">当前金额</td>
      <td width="56%" align="left">
        <?echo $RebateInfo[OneRebate]+$RebateInfo[TwoRebate]?>      </td>
    </tr>
    <tr>
      <td colspan="2" align="center">
          <input type="submit" name="Submit" value="提交" />      </td>
    </tr>
  </table>
</form>
<table width="100%" border="1" cellpadding="0">
  <?
  $sql="select * from account_book where OrderID='{$_GET[id]}' order by time desc";
  //echo $sql;echo "<br>";
  $result=mysql_query($sql);
  while($row=mysql_fetch_array($result))
  {
	  $time=date("Y-m-d H:i",$row[time]);
	  $DelLink="<a href='{$FileName}?operate=DelRebate&UserID={$_GET[id]}&id={$row[id]}'>删除</a>";
	  
	  ?>
    <tr>
      <td width="35%"><?echo $time;?></td>
      <td width="37%"><?echo $row[gross];?></td>
      <td width="28%"><?echo $DelLink;?>&nbsp;</td>
    </tr>
  <?}?>
</table>

<script>

</script>