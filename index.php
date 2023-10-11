<?php
include "simple_html_dom.php";
error_reporting(0);
date_default_timezone_set('Asia/Yangon');
header('Content-Type:application/json');
$url = 'https://www.youdooball.com/?r=live_schedule';
$HOME_TEAM = [];
        $AWAY_TEAM = [];
        $HOME_FLAG = [];
        $AWAY_FLAG = [];
        $LEAGUE = [];
        $TIME = [];
        $STATUS = [];
        $Href =[];
        $SCORE =[];
        $Date = [];
        $mArray = [];
        $IMAGE = [];
$html = file_get_html($url);
if($html=="{     \"result\": \"ERROR\",     \"message\": \"[Database] (default), Connection error, Connection refused\" }"){
    $url = 'https://www.youdooball.com/';
    $html = file_get_html($url);
}
    foreach($html->find('div[class=w-100 container]')as $kt_player){
        foreach($kt_player->find('div[class=schedule-card_title]') as $font){

            $league = str_replace("          ",'',$font->plaintext);
            array_push($LEAGUE,str_replace("      ","",str_replace('&nbsp;','',$league)));
        }
        foreach($kt_player->find('div[class=schedule-card_image_container] img')as $image){

            $img =  $image->src;
            array_push($IMAGE,$img);
        }

        foreach($kt_player->find('div[class=schedule-card_time]')as $time){
            $dateatime= $time->plaintext;
            $ary = explode(' ',$dateatime);
            $dt = new DateTime($ary[36],new DateTimeZone('GMT+7'));
            $dt->setTimezone(new DateTimezone('Asia/Yangon'));
            $time = $dt->format('h:i A');
            array_push($TIME,$time);
            if(str_contains($ary[18],"\r\n")){
                $arys = explode("\r\n",$ary[18]);
            array_push($Date,$arys[0]);
            }else{
            array_push($Date,$ary[18]);
            }
            
        }
        foreach($kt_player->find('div[class=border-top align-content-center overflow-hidden schedule-card_desc]')as $name){
            $Name = str_replace('              ','',$name->plaintext);
            $NAMEs= translate($Name, "th", "en");
            if(str_contains($NAMEs,'-')){
                $ary = explode('-',$NAMEs);
                array_push($HOME_TEAM,$ary[0]);
                array_push($AWAY_TEAM,$ary[1]);
                
            }elseif(str_contains($NAMEs,'vs')){
                $ary = explode('vs',$NAMEs);
                array_push($HOME_TEAM,$ary[0]);
                array_push($AWAY_TEAM,$ary[1]);
            }
        }
        foreach($kt_player->find('div[class=schedule-card_countdown]')as $id){
            $ID= $id->{'data-id-countdown'};
            
            array_push($Href,$ID);
        }    
        for($i=0;$i<count($IMAGE);$i++){
            if($i%2==0){
                array_push($HOME_FLAG,$IMAGE[$i]);
            }else{
                array_push($AWAY_FLAG,$IMAGE[$i]);
            }
        }

    }
    for($i=0;$i<count($TIME);$i++){
        $start_times = strtotime($Date[$i].' '.$TIME[$i]);
        $date_time = strtotime(date('d/m/Y h:i A'));
        if($start_times<=$date_time){
            array_push($STATUS,'Live');
        }else{
            array_push($STATUS,'Uncoming');
        }
    }
    function translate($q, $sl, $tl){
            $res="";
        
            $qqq=explode(".", $q);
        
            if(count($qqq)<2){
        
                @unlink($_SERVER['DOCUMENT_ROOT']."/transes.html");
                copy("http://translate.googleapis.com/translate_a/single?client=gtx&ie=UTF-8&oe=UTF-8&dt=bd&dt=ex&dt=ld&dt=md&dt=qca&dt=rw&dt=rm&dt=ss&dt=t&dt=at&sl=".$sl."&tl=".$tl."&hl=hl&q=".urlencode($q), $_SERVER['DOCUMENT_ROOT']."/transes.html");
                if(file_exists($_SERVER['DOCUMENT_ROOT']."/transes.html")){
                    $dara=file_get_contents($_SERVER['DOCUMENT_ROOT']."/transes.html");
                    $f=explode("\"", $dara);
        
                    $res.= $f[1];
                }
            }
            else{
        
        
            for($i=0;$i<(count($qqq)-1);$i++){
        
                if($qqq[$i]==' ' || $qqq[$i]==''){
                }
                else{
                    copy("http://translate.googleapis.com/translate_a/single?client=gtx&ie=UTF-8&oe=UTF-8&dt=bd&dt=ex&dt=ld&dt=md&dt=qca&dt=rw&dt=rm&dt=ss&dt=t&dt=at&sl=".$sl."&tl=".$tl."&hl=hl&q=".urlencode($qqq[$i]), $_SERVER['DOCUMENT_ROOT']."/transes.html");
        
                    $dara=file_get_contents($_SERVER['DOCUMENT_ROOT']."/transes.html");
                    @unlink($_SERVER['DOCUMENT_ROOT']."/transes.html");
                    $f=explode("\"", $dara);
        
                    $res.= $f[1].". ";
                    }
                }
            }
            return $res;
        }
        
        for($i=0;$i<count($TIME);$i++){
            if(!empty($HOME_TEAM[$i])){
                
                $json = [
                    "league"=>$LEAGUE[$i],
                    "date"=>$Date[$i],
                    "time"=>$Date[$i].'   '. $TIME[$i],
                    "status"=>$STATUS[$i],
                    "score"=>"vs",
                    "home_name"=>$HOME_TEAM[$i],
                    "home_logo"=>$HOME_FLAG[$i],
                    "away_name"=>$AWAY_TEAM[$i],
                    "away_logo"=>$AWAY_FLAG[$i],
                    "url"=>$Href[$i]
                ];
                array_push($mArray,$json);
            }
            
            
          
          }
          echo json_encode($mArray,JSON_PRETTY_PRINT);
        
?>