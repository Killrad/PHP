<?php
$settings = array(
    'db_host' => 'localhost',
    'db_user' => 'test',
    'db_password' => 'test',
    'db_db_name' => 'users'
);
$host='localhost';
$db = 'lab5';
$username = 'test';
$password = 'test';
$dsn = "pgsql:host=$host;port=5432;dbname=$db;user=$username;password=$password";
try{
$conn = new PDO($dsn);
if($conn)
{
    $lg = $_POST['login'];
    $pw = $_POST['password'];
    $query= "SELECT pass FROM users WHERE (pass='$pw' and name = '$lg') ;";
    $dat = $conn->query($query);
    $dat2 = array();
    $pass = array();
    while($row = $dat->fetch(PDO::FETCH_ASSOC)){
        $pass[]=$row;
    }
    if (count($pass) == 0)
    {
        $dat2['status'] = "bad";
        $dat2['result'] = "Неверный логин или пароль.";
    }
    else
    {
        $dat2['status']= "ok";
        $dat2['result'] = $pass;
    }
    $json = json_encode($dat2);
    echo $json;

}
else
{
    $dat2 = array();
    $dat2['status'] = "bad";
    $dat2['result'] = "Связь с бд не установлена";
    echo json_encode($dat2);
}
}catch (PDOException $e){
    echo $e->getMessage();
    }