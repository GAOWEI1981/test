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

?>
<form action="<?echo $FileName;?>?operate=ChangeRebate&id=<?echo $_GET[id];?>" method="post" name="RebateEdit" class="Industry" id="RebateEdit">
  <table width="100%" border="1" cellspacing="0">
    <tr>
      <td colspan="2" align="left">&nbsp;</td>
    </tr>
    <tr>
      <td width="44%">一级代理返利</td>
      <td width="56%" align="left">
        <input name="0" type="text" id="0" value="<?echo $OneClassRebate;?>" />      </td>
    </tr>
    <tr>
      <td>二级代理返利</td>
      <td align="left"><label>
        <input name="1" type="text" id="1" value="<?echo $TwoClassRebate;?>" />
      </label></td>
    </tr>
    <tr>
      <td colspan="2" align="center">
          <input type="submit" name="Submit" value="提交" />      </td>
    </tr>
  </table>
</form>
<script>

</script>