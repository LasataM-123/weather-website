<?php
//Name:Lasata Maharjan University Id:2408473
//this function checks if the database is connected or not
function connect($server,$username,$password,$database){
    $conn=null;
    try{
        $conn=new mysqli($server, $username, $password,$database);
        if($conn->connect_errno){
           die("Database connection failed".$conn->connect_errno);
        }
        return $conn;
    }catch(Exception $th){
        return null;
    }
}
//This functon selects the data of the specified city by date in ascending order
function get_weather($conn,$cityname){
    {
        try {
            $result = $conn->query('SELECT * FROM city_all WHERE cityname="' . $cityname.'" ORDER BY Weather_date ASC;');
            if ($result) {
                $data = $result->fetch_all(MYSQLI_ASSOC);
                return $data;
            } else {
                return null;
            }
        } catch (Exception $th) {
            return null;
        }
    }
}
//this function is used to select the data from the database with name of city and date
function get_weather_data($conn,$cityname,$date){
    {
        try {
            $result = $conn->query('SELECT * FROM city_all WHERE cityname="' . $cityname . '" AND Weather_date="' . $date . '" ORDER BY Weather_date ASC;');
            if ($result) {
                $data = $result->fetch_all(MYSQLI_ASSOC);
                return $data;
            } else {
                return null;
            }
        } catch (Exception $th) {
            return null;
        }
    }
}
//this function is used to convert the timestamp to year month and day
function convertDate($timestamp) {
    return date('Y-m-d', $timestamp);
}

//this function is used to insert the data in the database
function insert_weather_data($conn, $data)
{
    try{
        $cityname=$data["name"];
        $date=convertDate($data["dt"]);
        $temp=$data["main"]["temp"];
        $pressure=$data["main"]["pressure"];
        $wind=$data["wind"]["speed"];
        $humidity=$data["main"]["humidity"];
        $main=$data["weather"][0]["main"];
        $icon=$data["weather"][0]["icon"];
        $description=$data["weather"][0]["description"];
            if (!get_weather_data($conn, $cityname, $date)) {
                $result =$conn->query( 'INSERT INTO city_all(cityname, Weather_date, Temperature, Pressure, Wind_speed,Humidity, main, icon, weather_description) VALUES(
                    "'. $cityname .'",
                    "' . $date . '",
                    ' . $temp . ',
                    ' . $pressure . ',
                    ' . $wind . ',
                    ' . $humidity . ',
                    "' . $main . '",
                    "' . $icon . '",
                    "' . $description . '"
                )');
    
                if ($result) {
                    return true;
                } else {
                    return false;
                }
            }
    
            return true;
        } catch (Exception $th) {
            echo $th;
            return false;
        }
    }
 
//this function is used to update if the date already exists otherwise it inserts the data
function update_or_insert_weather_data($conn, $data)
{
    try {
        $cityname = $data["name"];
        $date = convertDate($data["dt"]);
        $temp = $data["main"]["temp"];
        $pressure = $data["main"]["pressure"];
        $wind = $data["wind"]["speed"];
        $humidity = $data["main"]["humidity"];
        $main = $data["weather"][0]["main"];
        $icon = $data["weather"][0]["icon"];
        $description = $data["weather"][0]["description"];

        $existingData = get_weather_data($conn, $cityname, $date);
        //checks if the data already exists
        if ($existingData) {
            // Data already exists for the city and date, consider updating it
            $result = $conn->query('UPDATE city_all  SET 
                Temperature = ' . $temp . ',
                Pressure = ' . $pressure . ',
                Wind_speed = ' . $wind . ',
                Humidity = ' . $humidity . ',
                main = "' . $main . '",
                icon = "' . $icon . '",
                weather_description = "' . $description . '"
                WHERE cityname = "' . $cityname . '" AND Weather_date = "' . $date . '"');
                if ($result) {
                    return true;
                } else {
                    return false;
                }
        } else {
            //if the data i.e. same city name and date doesn't exist, insert the data
           insert_weather_data($conn,$data);
        }
    } catch (Exception $th) {
        echo json_encode(['error' => $th->getMessage()]);
    }
}

?>
