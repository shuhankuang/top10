<?php


  function init(){
  
    $header[]= "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7"; 
$header[]= 'Content-Type: application/json';  
    $header[]= "X-AVOSCloud-Application-Id: 5yz37ugjv8fr9194oqlxkqq6zfuv55lqinhk50vtco3w23p9"; 
	    $header[]= "X-AVOSCloud-Application-Key: v5x4nm3lcey5n4iut0iomtwns80jnmxvdbaxhzacfiy9v5ma"; 

   return $header;
  }

  function getInfo($codeid){
  
    $array=array();
    $header=init();

$ch = curl_init ('https://cn.avoscloud.com/1/classes/Wedding?where={"sweetcode":"'.$codeid.'"}&include=video,couple,location');
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回  
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
curl_setopt($ch, CURLOPT_HEADER, 0);  
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$result = curl_exec($ch); 


if(curl_errno($ch)){
    echo 'Curl error: ' . curl_error($ch);
}

$ass=json_decode($result);

$array['ass']=$ass;
$companylist=$ass->results[0]->companies;
$companycount=count($companylist);

for($c=0;$c<$companycount;$c++){

$companys[$c]='"'.$companylist[$c].'"';
}

$idstr=implode(",",$companys);
$ch = curl_init ('https://cn.avoscloud.com/1/classes/Company?where={"objectId":{"$in":['.$idstr.']}}');

curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回  
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
curl_setopt($ch, CURLOPT_HEADER, 0); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$result = curl_exec($ch); 
$companyarray=json_decode($result);
for($g=0;$g<count($companyarray->results);$g++){
$company[$g]['name']=$companyarray->results[$g]->name;
$company[$g]['id']=$companyarray->results[$g]->objectId;
}
   
 $array['company']=$company;  
 
   return $array;
  
  }

  
   function getCategory($id){
   
    $header=init();
$chs = curl_init ('https://cn.avoscloud.com/1/classes/Category?order=index&where={"weddingId":"'.$id.'"}');

curl_setopt($chs, CURLOPT_HTTPHEADER, $header);
curl_setopt($chs, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回  
curl_setopt($chs, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
curl_setopt($chs, CURLOPT_HEADER, 0); 
curl_setopt($chs, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($chs, CURLOPT_SSL_VERIFYHOST, false);
$result = curl_exec($chs);
$category=json_decode($result);
   
   
  return $category;
   }

?>



