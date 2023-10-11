<?php
include "simple_html_dom.php";
error_reporting(0);
header('Content-Type:application/json');
$url = 'https://www.youdooball.com/?r=live_schedule';
$ID=$_GET['url'];
$NAME =[];
$LINK=[];
$mArray = [];
$html = file_get_html($url);
if($html=="{     \"result\": \"ERROR\",     \"message\": \"[Database] (default), Connection error, Connection refused\" }"){
    $url = 'https://www.youdooball.com/';
    $html = file_get_html($url);
}
    foreach($html->find('div[class=w-100 container]')as $kt_player){
        foreach($kt_player->find('div[data-schedule-data-id='.$ID.']')as $json){
            $Json= $json->{'data-schedule-channel'};
            $decode = json_decode($Json);
            $ary = $decode->hls_url;
            $data =  explode(",",str_replace("}","",str_replace("{","",str_replace("]","",str_replace("[",'',$ary)))));
            
                for($i=0;$i<count($data);$i++){
                    if($i%2==0){
                        $name= str_replace("\"","",explode(":",$data[$i])[1]);
                        array_push($NAME,$name);
                    }else{
                        $link = str_replace("\"","",str_replace("\"url\":","",$data[$i]));
                
                        array_push($LINK,$link);
                    }
                }

                
            
        }
    }
    for($i=0;$i<count($NAME);$i++){
        $json=[
            'extra_title'=>$NAME[$i],
            'stream_link'=>$LINK[$i],
            'referer'=>"https://www.youdooball.com/"
        ];
        array_push($mArray,$json);
    }
    
echo json_encode($mArray,JSON_PRETTY_PRINT);
?>