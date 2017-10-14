<link rel="stylesheet" href="../function/industry.css"/>
<link rel="stylesheet" href="../function/WeiXin_UI/weui.css"/>
<link rel="stylesheet" href="../function/WeiXin_UI/example.css"/>
<?
$operate=$_GET[operate];
switch($operate)
{
case "GetSizeList":
	echo "<param>";
	$item=GetItemA("product_items","id",$_GET[id]);
	$data=unserialize($item[mall_param]);
	echo json_encode($data);
	
	echo "</param>";
	return;
}
$pics="";
//$PicHeightInPreview=300;
$w=$h=80;
$FirstPic="";
$sql="select * from product_items where modal='{$_GET[modal]}'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
	$wl=$w*2;
	$hl=$h*2;
	$path="../function/PicSize.php?ImgPath=../pic/data/{$row['path']}&width={$wl}&height={$hl}&PostXScale={$PosXScale}&PostYScale=0";
	if($pics=="")
	{
		
		$FirstPic="<img style='width:{$w}px;height:{$h}px;' src='{$path}'/>";
		$FirstID=$row[id];//用于显示第一个尺码列表
	}
	$pics.="<path>{$path}</path>"."<id>".$row['id']."</id>"."<br>";
	$PicIds[]=$row['id'];
	if($row[id]==$PicID)
	{
		$PicIndex=$index;
	}
	$index++;
}
?>
    <div class="weui_cells_title"><?echo "{$_GET[modal]}";?></div>
    <div class="weui_cells">
        <div class="weui_cell">
        <div class="weui_cell_bd weui_cell_primary">
        <table width="100%" border="0" cellpadding="1" cellspacing="0">
<tr>
  <td width="35%" align="center" id="PicWnd"><?echo $FirstPic;?></td>
  <td width="65%">
    <?
				
			$sql="select * from product_items where modal='{$_GET[modal]}' and color is not null and color<>''";
			//echo $sql;echo "<br>";
			$result=mysql_query($sql);
			if(mysql_num_rows($result)>0)
			{
			
				$i=0;
				while($row=mysql_fetch_array($result))
				{
					//print_r($row);echo "<br>";
					$ColorInfo="<input onclick='SelColor(this);' name='color' style='width:30px;' id='{$row[id]}' value='{$row[color]}' type='radio'/>{$row[color]}";
					?>
    <label><?echo $ColorInfo;?></label>
    <?
					$i++;
				}
				
			}
			
		?>        
    </td>
</tr>
        <tr>
          <td colspan="2" id="SizeWnd">&nbsp;
          </td>
        </tr>
        </table>
        </div>
        </div>
    </div>
<script src="../function/swiper/js/swiper.min.js"></script>
<script>
var pics=null;
var testaddup=0;
try
{
	pics="<?php echo $pics?>";
	var ar=pics.split("<br>");
	//alert(pics);
	//去除掉空的元素
	pics=new Array();addup=0;
	for(var i=0;i<ar.length;i++)
	{
		if(ar[i].length>0)
		{
			//pics[addup]=GetXMLParam(ar[i],"path");
			var buf=new Array();
			buf['path']=GetXMLParam(ar[i],"path");
			buf['id']=GetXMLParam(ar[i],"id");
			pics[addup]=buf;
			//alert(ar[i]);
			addup++;
		}
	}
}
catch(e)
{
	alert(e);
}

function SelColor(obj)
{
	
	var PicWnd=document.getElementById("PicWnd");
	var SizeWnd=document.getElementById("SizeWnd");
	for(var i=0;i<pics.length;i++)
	{
		if(obj.id==pics[i]['id'])
		{
			PicWnd.innerHTML="<img style='width:<?echo $w;?>px;height:<?echo $h;?>px;' src='"+pics[i]['path']+"'/>";
			//alert("etete");
			
		}
	}
	var ajax=InitAjax();
	var url="<?echo "{$FileName}?operate=GetSizeList&id=";?>"+obj.id;
	ajax.open("GET",url,false);
	ajax.send();
	var response=ajax.responseText;

	var data=GetXMLParam(response,"param");
	var sizes=eval("{"+data+"}");
	var list="";
	for(var i=0;i<sizes.length;i++)
	{
		//alert(sizes[i]);
		list+="<label><input type='radio' style='width:20px' id='"+sizes[i]+"' name='size' >"+sizes[i]+"</label>";
	}
	if(list.length==0) list="&nbsp";
	SizeWnd.innerHTML=list;
	
}
</script>