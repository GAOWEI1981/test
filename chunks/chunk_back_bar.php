<link rel="stylesheet" href="../function/industry.css"/>
<link rel="stylesheet" href="../function/weui/dist/style/weui.css"/>
<link rel="stylesheet" href="../function/weui/dist/style/weui.min.css"/>
<?
echo "sdfsdfsdf";
if(strlen($IconWidth)==0) $IconWidth="20px";
?>
<div class="weui_cells" style="margin-top:5px">
    <a class="weui_cell" href="<?echo $BackUrl;?>">
        <div class="weui_cell_bd weui_cell_primary">
            <img src="../icon/icon_back.png" alt="" style="width:25px;margin-right:5px;display:block">
        </div>
        <div class="weui_cell_ft">
           <img src="<?echo $Icon?>" alt="" style="width:<?echo $IconWidth;?>;">
        </div>
    </a>
 </div>