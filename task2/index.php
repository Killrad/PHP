<?php
// путь до XML файла
$xml_file = "data.xml";
// настройки подключения к базе данных
$settings = array(
    'db_host' => 'localhost',
    'db_user' => 'test',
    'db_password' => 'test',
    'db_db_name' => 'test_db'
);
// Парсим xml файл в переменную $xml
$xml = simplexml_load_file($xml_file);
// Подключаемся к базе данных
if ((@$link_sql = mysqli_connect($settings['db_host'],
        $settings['db_user'],
        $settings['db_password'],
        $settings['db_db_name']))
    && (@mysqli_query($link_sql, "SET NAMES utf8;")))
{
    print "Связь с базой установлена\n";
    //Очищаем таблицу
    mysqli_query($link_sql, "DELETE FROM plant where zone < 50;");
    // проходим по всем PLANT из XML
    foreach ($xml->children() as $row){
        //Считываем параметры из xml в соответствующие переменные
        $common = strval($row->COMMON);
        $botanical = strval($row->BOTANICAL);
        $zone = intval($row->ZONE);
        $light = strval($row->LIGHT);
        $price = strval($row->PRICE);
        $avail = strval($row->AVAILABILITY);
        //Создаём SQL запрос на вставку данных
        $insert_sql = "INSERT INTO plant values ('$common', '$botanical', '$zone', '$light', '$price', '$avail');";
        // Выполняем SQL запрос
        mysqli_query($link_sql, $insert_sql);
    }
    print "Данные загружены в БД\n";
    // Считываем таблицу из бд
    $dat = mysqli_query($link_sql, "select * from plant;");
    //переводим результат запроса в php массив
    $dat2 = array();
    while($row = mysqli_fetch_assoc($dat)){
        $dat2[]=$row;
    }
    //Создаём json файл
    $json = (json_encode($dat2, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK));
    $out = fopen("data.json", 'w');
    fprintf($out, $json);
    print "json файл создан\n";
}


