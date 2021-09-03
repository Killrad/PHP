<?php
$apikey = '4018fcaa-5f88-4692-a2fc-ba97a0113d1e';
$params = [
    'apikey' => $apikey,
    'geocode' => $_POST['address'],
    'format' => 'json',
];

$response = file_get_contents('https://geocode-maps.yandex.ru/1.x/?'. http_build_query($params));
    $obj = json_decode($response, true);

    $cord = str_replace(" ", ",", $obj['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos']);
    $parameters = array(
      'apikey' => $apikey,
      'geocode' => $cord,
      'kind' => 'metro',
      'format' => 'json',
      'results' => '1'
    );
    
    $response = file_get_contents('https://geocode-maps.yandex.ru/1.x/?'. http_build_query($parameters));
    //echo $response;
    $obj = json_decode($response, true);

    $metro = $obj['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['Address']['formatted'];
    echo json_encode($metro, JSON_UNESCAPED_UNICODE);
