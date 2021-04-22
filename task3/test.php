<?php


/*$settings = array(
    'db_host' => 'remotemysql.com',
    'db_user' => 'wN81O6w6cD',
    'db_password' => 'btuvTGizpA',
    'db_db_name' => 'wN81O6w6cD'
);*/
$settings = array(
    'db_host' => 'localhost',
    'db_user' => 'tester',
    'db_password' => 'PUFQ1csFGBiWuwvV',
    'db_db_name' => 'phpmailer'
);
$time = date('Y-m-d H:i:s');
// Подключаемся к базе данных
if ((@$link_sql = mysqli_connect($settings['db_host'],
        $settings['db_user'],
        $settings['db_password'],
        $settings['db_db_name'], 3306))
    && (@mysqli_query($link_sql, "SET NAMES utf8;"))) {

    $dat = mysqli_query($link_sql, "select mtime,  TIMEDIFF('01:30:00',TIMEDIFF('$time', mtime)) as wait from reports
                                                    where email like '$_POST[email]' and mtime >= DATE_SUB('$time', INTERVAL 90 MINUTE) 
                                                    order by mtime desc;");
    $dat2 = array();
    while($row = mysqli_fetch_assoc($dat)){
        $dat2[]=$row;
    }
    if (sizeof($dat2) == 0){
        $insert_sql = "INSERT INTO reports values ('$time', '$_POST[name]', '$_POST[email]', '$_POST[phone]', '$_POST[message]');";
        mysqli_query($link_sql, $insert_sql);
        $result = "success";
        $status = "ok";
    }
    else{
        $wait = $dat2[0]['wait'];
        $result = "wait";
        $status = $wait;
    }

}
else{
    $result = "error";
    $status = 'no DB connection';
}
$response["result"] = $result;
$response["status"] = $status;
// Отображение результата
echo json_encode($response);
?>