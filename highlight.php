<?php
error_reporting(0);
date_default_timezone_set('Asia/Yangon');
header('Content-Type:application/json');
include('simple_html_dom.php');
$url = "https://bingsport.tv/high-light";
$html = file_get_html($url);
        $IMAGE = [];
        $TITLE = [];
        $HOME_TEAM = [];
        $AWAY_TEAM = [];
        $HOME_FLAG = [];
        $AWAY_FLAG = [];
        $LEAGUE = [];
        $TIME = [];
        $STATUS = [];
        $Href =[];
        $SCORE =[];
        $mArray = [];
foreach($html->find('div[class=right-container]')as $container){
    foreach($container->find('a')as $a){
        $href = $a->href;
        array_push($Href,$href);
        foreach($a->find('span[class=txt_time]')as $time){
            $time = $time->plaintext;
            $dt = new DateTime($time,new DateTimeZone('GMT+0'));
            $dt->setTimezone(new DateTimezone('Asia/Yangon'));
            $time = $dt->format('h:i A');
            array_push($TIME,$time);

        }
        foreach($a->find('div[class=txt-vs]')as $vs){
            $status = $vs->plaintext;
            array_push($STATUS,$status);
        }
        foreach($a->find('div[class=league-name]')as $name){
            $league = $name->plaintext;
            array_push($LEAGUE,$league);
        }
        foreach($a->find('img[class=lazy]')as $img){
            $image = $img->{'data-src'};
            $title = $img->title;
            array_push($IMAGE,$image);
            array_push($TITLE,$title);
        }
        
    }

}

foreach ($IMAGE as $k => $v) {
    if ($k % 2 == 0) {
        array_push($HOME_FLAG,$v);
        // $even[] = $v;
    }
    else {
        array_push($AWAY_FLAG,$v);
        // $odd[] = $v;
    }
}
foreach ($TITLE as $k => $v) {
    if ($k % 2 == 0) {
        array_push($HOME_TEAM,$v);
        // $even[] = $v;
    }
    else {
        array_push($AWAY_TEAM,$v);
        // $odd[] = $v;
    }
}
for($i = 0 ;$i<count($HOME_TEAM);$i++){
    $json = [
        'league'=>$LEAGUE[$i],
        'score'=>$STATUS[$i],
        'time'=>$TIME[$i],
        "home_name"=>$HOME_TEAM[$i],
        "home_logo"=>$HOME_FLAG[$i],
        "away_name"=>$AWAY_TEAM[$i],
        "away_logo"=>$AWAY_FLAG[$i],
        "url"=>$Href[$i]
    ];
    array_push($mArray,$json);
}
echo json_encode($mArray,JSON_PRETTY_PRINT);
?>