<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<?
$pics="";
$result=mysql_query($PicsSql);$index=0;
while($row=mysql_fetch_array($result))
{
	$path="../pic/data/{$row[path]}";
	$source_info   = getimagesize($path);
	$source_width  = $source_info[0];
	$source_height = $source_info[1];
	//print_r($source_info);echo "<br>";
	?>
    <div class="weui_cells" style="margin-top:5px">
        <div class="weui_cell_bd weui_cell_primary">
        <img style="max-width:100%;max-height:100%" src="<?echo $path;?>"></a>
        </div>
    </div>
	<?
	$index++;
}
?>

<script src="../function/swiper/js/swiper.min.js"></script>
<script>
</script>
