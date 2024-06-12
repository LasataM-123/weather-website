<?php
//Name:Lasata Maharjan University Id:2408473
//this is used to set a header as any origin is allowed to access the resource
header("Access-Control-Allow-Origin: *");
//this converts the page to json type of page
header("Content-Type: application/json");
//include() function includes the file
include("database.php");
include("api.php");
//isset() function is used to check that the entered value is set and not null
//$_GET gets the value of the $city from the URL
if (isset($_GET['city'])){
    $city = $_GET['city'];
    $data = fetch_currentWeather_data($city);
    if (isset($_GET["history"])) {
        $conn=connect("127.0.0.1","root","","weather");
        if($conn){
           $allData= get_weather($conn,$city);
           echo json_encode($allData);
           exit;
        }else{
            echo"Database connection failed!";
            exit;
        }
    }
    if($data){
        $conn=connect("127.0.0.1","root","","weather");
        if($conn){
            update_or_insert_weather_data($conn,$data);
        }else{
            echo"Database connection failed!";
        }
        //converting to an associative array form to be displayed
        $response = array(
            'coord' => $data['coord'],
            'weather' => array(
                'main' => $data['weather'][0]['main'],
                'icon'=>$data['weather'][0]['icon'],
                'description' => $data['weather'][0]['description']
            ),
            'main' => array(
                'temp' => $data['main']['temp'],
                'pressure' => $data['main']['pressure'],
                'humidity' => $data['main']['humidity']
            ),
            'wind' => array(
                'speed' => $data['wind']['speed']
            ),
            'name' => $data['name'],
            'date' => $data['dt']
        );
        //converting the associative array to JSON string form
        $jsonData=json_encode($response);
        echo $jsonData;
    }else{
        echo json_encode(['error'=>'No data found in API']);
        exit;
    }
} else {
    echo json_encode(['error' => 'City parameter is missing']);
    exit;
}
?>
