<?php
$name = $_POST["title"];
$content= $_POST["content"];
$img = $_POST['image'];
// error_reporting(0);
header('Content-Type:application/json');
$code = sendMessage($name,$content,$img);
echo $code;

function sendMessage($title,$text,$img){
    $content = array(
        "en" => $text
        );
    $headings = array(
            "en" => $title
    );

    $fields = array(
        'app_id' => "05c725bf-48db-40c2-a87e-defd0fb2f2e2",
        "headings" => $headings,
        'included_segments' => array('All'),
        'data' => array("foo" => "bar"),
        'large_icon' =>"https://i.imgur.com/hVZtTER.jpg",
        'contents' => $content,
        "big_picture" => $img,
    );

    $fields = json_encode($fields);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                                               'Authorization: Basic ZGUwNDU4M2YtZjlkNy00ODEzLWIzYzctMWJkOWJmMDA1NThk'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}
?>