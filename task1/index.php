<?php
// Функция, вычисляющая ответ на задачу
function ResultTask($inputFile)
{
    //Открываем на чтение файл с входными данными
    $input = fopen($inputFile, 'r');
    $BetsNumber = fgets($input);// Считываем число сделаных ставок
    $Bets = array();
    $Income = 0; // Переменная отвечающая за баланс игрока
    for($it = 0; $it < $BetsNumber; $it++){ // Построчно считываем сделанные ставки и помещаем их в массив Bets
        list($GameIndex, $BetValue, $Command) = explode(" ", fgets($input));
        $Command = trim($Command, "\n\r");
        $Bets[$GameIndex][$Command]=$BetValue;
        $Income -=$BetValue; // Уменьшаем баланс т.к. делаем ставку
    }
    $GamesNumber = fgets($input); // Считываем число проведённых игр
    for($it = 0; $it < $GamesNumber; $it++){ // Построчно читаем игры и проверяем: делали ли мы на них ставку, и выйграла ли она
        list($GameIndex, $Lcoeff, $Rcoeff, $Dcoeff, $Command) = explode(" ", fgets($input));
        $Command = trim($Command, "\n\r");
        //Проверяем есть ли в массиве Bets ставка с индексом GameIndex на команду Command
        if (array_key_exists($GameIndex, $Bets) && array_key_exists($Command, $Bets[$GameIndex])){
            //Изменяем баланс т.к. ставка прошла. С помошью Command определяем нужный коэффициент.
            $Income += $Bets[$GameIndex][$Command] * ($Command == 'L' ? $Lcoeff : ($Command == 'R' ? $Rcoeff : $Dcoeff));
        }
    }
    return $Income; // Возвращаем полученный доход
}

// Получаем файлы с тестами и ответами на них
$inputData = glob('A/*.dat');
$inputAns = glob('A/*.ans');


print "Tests results:\n";
//Проходим по всем файлам и проверяем работу алгоритма
for ($index = 0; $index < sizeof($inputData); $index++){
    $output = fopen($inputAns[$index], 'r');//Открываем файл с ответами на чтение
    $result = ResultTask($inputData[$index]); //Получаем результат работы нашего алгоритма
    $answer = fgets($output);//Считываем результат из файла
    print ($index + 1).") ".($answer == $result ? 'OK' : 'WA')."\n"; // Сравниваем и выводим ответ по данному тесту
}