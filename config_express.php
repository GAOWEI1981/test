<?php
//参数设置
//来自快递100
function ExpressQuery($ComNum,$ExpressNum)
{
	$post_data = array();
	$post_data["customer"] = 'E525A3D4322FF8CD9AFBC20A9BD66BB8';
	$key= 'gbOwUwkS2312' ;
	//$post_data["param"] = '{"com":"shunfeng","num":"616549560293"}';
	$post_data["param"] = '{"com":"'.$ComNum.'","num":"'.$ExpressNum.'"}';
	/*print_r($post_data);echo "<br>";
	echo "<br>";
	echo "<br>";
	echo "<br>";*/
	
	$url='http://poll.kuaidi100.com/poll/query.do';
	$post_data["sign"] = md5($post_data["param"].$key.$post_data["customer"]);
	$post_data["sign"] = strtoupper($post_data["sign"]);
	$o=""; 
	foreach ($post_data as $k=>$v)
	{
		$o.= "$k=".urlencode($v)."&";		//默认UTF-8编码格式
	}
	$post_data=substr($o,0,-1);
	$result =https_post($url,$post_data);
	$data = str_replace("\&quot;",'"',$result );
	$data = json_decode($data,true);
	return $data;
}

//echo "dfdf<br>";
/*
$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	$result = curl_exec($ch);
	$data = str_replace("\&quot;",'"',$result );
	$data = json_decode($data,true);
*/
?>
