<link href="../function/industry.css" rel="stylesheet" type="text/css" />
<?
//echo $FileName;echo "<br>";
//echo $_SESSION[HomePage];echo "<br>";
?>
<table width="100%" border="1" cellspacing="0" cellpadding="0" class="Industry">
  <tr>
    <th align="left"><a href="
    <?
	if(strlen($LastPage)>0)
	{
		echo $LastPage;
	}
	else
	{
		if($FileName!=$_SESSION[HomePage])
		{
			if(strlen($_SESSION[HomePage])>0)
				echo $_SESSION[HomePage];
			else echo "page_user_login_admin.php?operate=logout";
		}
		else
			echo "page_user_login_admin.php?operate=logout";
	}
	
	?>
    ">返回</a></th>
    <th align="right"><a href="page_user_login_admin.php?operate=logout">退出</a></th>
  </tr>
</table>
<script src="../../function/jquery.js"></script>
<script>
</script>