<?php
error_reporting(0);
date_default_timezone_set('Asia/Yangon');
header('Content-Type:application/json');

include('simple_html_dom.php');



$url = $_GET['url'];
if(!empty(getScripMp4($url))){
    echo getScripMp4($url);
}else{
    echo getM3u8($url);
}
function getScripMp4($url){
    $mArray = [];
    $html = file_get_html($url);
        foreach($html->find('body')as $body){
            foreach($body->find("script")as $script){
                $acy = explode(";",explode("link_highlight",$script->innertext)[1]);
                if(!empty($acy[0])){
                    $link = str_replace(" ","",str_replace("=","",str_replace("'","",$acy[0])));
                    
                    $json = [
                        'name'=>"HD",
                        'link'=>$link,
                        'referer'=>"https://bingsport.tv/"
                    ];
                    array_push($mArray,$json);
                }
            }
        }
        return json_encode($mArray,JSON_PRETTY_PRINT);
}

function getM3u8($url){
    $ary = [];
    $TITLE = [];
    $mArray = [];
    $html = file_get_html($url);
        foreach($html->find('body')as $body){
            foreach($body->find('div[class=list-server]')as $script){
                foreach($script->find('div')as $div){
                    $link = $div->{'data-link'};
                    array_push($ary,$link);
                    array_push($TITLE,$div->plaintext);
                }
            }
        }
    for($i=0;$i<count($ary);$i++){
        $json = [
            'name'=>$TITLE[$i],
            'link'=>$ary[$i],
            'referer'=>"https://bingsport.tv/"
        ];
        array_push($mArray,$json);
    }
    return json_encode($mArray,JSON_PRETTY_PRINT);
}

?>