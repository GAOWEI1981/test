<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
<!-- Link Swiper's CSS -->
<link rel="stylesheet" href="../function/swiper/css/swiper.min.css">
<link href="../../function/industry.css" rel="stylesheet" type="text/css">

<style>
	.swiper-container
	{
        width: 100%;
        margin-left: auto;
        margin-right: auto;
    }
	#swiper-title .swiper-slide
	{
		text-align: center;
		font-size: 18px;
		background: #2A2222;
		
		/* Center slide text vertically */
		display: -webkit-box;
		display: -ms-flexbox;
		display: -webkit-flex;
		display: flex;
		-webkit-box-pack: center;
		-ms-flex-pack: center;
		-webkit-justify-content: center;
		justify-content: center;
		-webkit-box-align: center;
		-ms-flex-align: center;
		-webkit-align-items: center;
		align-items: center;
	}
	.swiper-slide a
	{
		color:#FF0000;
	}
</style>
<table width="100%" border="0" align="center" cellspacing="0" class="Industry">
<tr>
<td height="22" colspan="3" align="left"><table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
  <td align="right" bgcolor="#FF0004" style="font-size: xx-large; font-weight: 900; font-style: normal; color: #FFFFFF;">古海角产品目录</td>
  </tr>
<tr>
  <td align="right" bgcolor="#FF0004" style="color: #FFFFFF">GUHAIJIAO</td>
</tr>
<tr>
  <td align="right">

  </td>
</tr>
</table></td>
</tr>
</table>
  <!-- Swiper -->
<div class="swiper-container" style="height: 40px;" id="swiper-title">
    <div class="swiper-wrapper">
        <div class="swiper-slide"><a href="<?echo "index.php?ViewModal=";?>">首页</a></div>
        <?
		$MenuSql="select * from product_groups";
		$result=mysql_query($MenuSql);
		while($row=mysql_fetch_array($result))
		{
			?>
	  		<div class="swiper-slide"><a href="<?echo "index.php?ViewModal=ViewProductGroup&GroupID={$row[id]}";?>"><?echo $row[name];?></a></div>
            <?
		}
		?>
        <div class="swiper-slide"><a href="login.php">后台</a></div>
    </div>
</div>
<!-- Swiper JS -->
<script src="../function/swiper/js/swiper.min.js"></script>
<script>
/*
<table width="100%" border="0" cellpadding="0" class="Industry">
<tr>
      <td width="67%" align="center" valign="middle"><input type="text" name="keyword" id="keyword" style="width:80%"></td>
      <td width="33%" align="center" valign="middle"><input  onClick="Search();" type="button" name="button" id="button" value="查找"></td>
    </tr>
</table>
*/
var swiper_title = new Swiper('#swiper-title',
{
	pagination: '.swiper-pagination',
	slidesPerView: 3,
	freeModeMomentumVelocityRatio: 2,
	paginationClickable: true,
	spaceBetween: 2,
	freeMode: true
});
function Search()
{
	var obj=document.getElementById("keyword");
	var modal=obj.value;
	var url="index.php?keyword="+modal+"&ViewModal=<?echo $_GET[ViewModal];?>&GroupID=<?echo $_GET[GroupID];?>";
	//alert(url);
	document.location=url;
	return false;
}
</script>
