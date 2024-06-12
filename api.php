<?php
//Name:Lasata Maharjan University Id:2408473
function fetch_currentWeather_data($city){
    try{
        //use the open weathermap api to fetch data of $city
        $APIURL="https://api.openweathermap.org/data/2.5/weather?units=metric&q=".$city."&appid=7e5a8c646fc24d483fe02d2982677ed2";
        //@ is used to suppress the warnings
        $weatherD=@file_get_contents($APIURL);
        //json_decode converts the JSON objecto to associative array
        $data=json_decode($weatherD,true);
        return $data;
    }catch(Exception $th){
        return null;
    }
}

?>