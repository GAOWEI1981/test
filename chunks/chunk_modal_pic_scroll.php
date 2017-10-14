<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport"content="width=device-width"/>
<link rel="stylesheet" href="../function/swiper/css/swiper.min.css">
<link href="../function/industry.css" rel="stylesheet" type="text/css" />
 <style>
    html, body {
        position: relative;
        height: 100%;
    }
    body {
        background: #eee;
        font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
        font-size: 14px;
        color:#000;
        margin: 0;
        padding: 0;
    }
    .swiper-container
	{
        width: 100%;
        margin-left: auto;
        margin-right: auto;
    }
    #swiper_pics .swiper-slide {
        text-align: center;
        font-size: 18px;
        background: #fff;

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
    </style>
<!-- Swiper -->
<div style="height:250px" class="swiper-container" id="swiper_pics">
    <div class="swiper-wrapper">
        <?
		$PicID=$_GET['ItemID'];
		if(strlen($PicID)==0)//假若没有指定从哪张图片开始
		{
			$result=mysql_query("select * from product_items where modal='{$_GET[modal]}'");
			$item=mysql_fetch_array($result);
			$PicID=$item[id];
		}
		
		
		$pitem=GetItemA("product_items","id",$PicID);
		$CurModal=$pitem['modal'];
		$CurPicPath=$pitem['path'];//点击的图片路径
		$PicWidth=300;
		$PicHeight=220;
		
		//print_r($CurModalInf);
		
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
	  		<div class="swiper-slide"><img style="max-width:100%;max-height:100%" src="<?echo $path;?>"></a></div>
            <?
			$index++;
		}
		?>
    </div>
    <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
        <!-- Add Arrows -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
</div>
<script src="../function/swiper/js/swiper.min.js"></script>
<script>
var swiper_pics = new Swiper('#swiper_pics',
{
	pagination: '.swiper-pagination',
	slidesPerView: 1,
	paginationClickable: true,
	spaceBetween: 0,
	keyboardControl: true,
	nextButton: '.swiper-button-next',
	prevButton: '.swiper-button-prev',
});

</script>
