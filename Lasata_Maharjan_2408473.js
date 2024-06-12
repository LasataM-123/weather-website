//using DOM elements to retrieve data from the html file
const searchButton=document.querySelector(".search");
const n=document.getElementById("cityn");
const cityname=document.getElementById("name");
const dateofweather=document.getElementById("date");
const temp=document.getElementById("temp");
const pressure=document.getElementById("pressure");
const wind=document.getElementById("wind");
const humidity=document.getElementById("humidity");
const img=document.getElementById("image");
const description=document.getElementById("description");
const weatherdata=()=>{
  //fetching data using php API developed
  const URL=`http://localhost/weather/main.php?city=${n.value.trim()}`;
  fetch(URL).then(res=>res.json())
  .then((data)=>{
    if(data.error){
      //if the API is showing error then this part 
      temp.innerHTML="Temperature:____";
      pressure.innerHTML="Pressure:____";
      wind.innerHTML="Wind Speed:____";
      humidity.innerHTML="Humidity:____";
      img.src='';
      description.innerHTML='';
      alert(`No data found for ${n.value}`);
    }  
    //converting the date provided in the api in the form like Wed,Jan 31,2024, 6:38:00 PM
    const date = new Date(data.date*1000).toLocaleDateString('en-US', {
      weekday: 'short',
      day: 'numeric',
      month: 'short',
      year:'numeric',
      hour:"numeric",
      minute:"numeric",
      second: 'numeric'
    });
    //display the data in the console       
    console.log(n.value,data);
    //changing the data inside using innerHTML
    cityname.innerHTML=data.name;
    dateofweather.innerHTML=date;
    temp.innerHTML=`Temperature: ${data.main.temp} &deg;C`;
    pressure.innerHTML=`Pressure: ${data.main.pressure} Pa`;
    wind.innerHTML=`Wind Speed: ${data.wind.speed} km/hr`;
    humidity.innerHTML=`Humidity: ${data.main.humidity}%`;
    source=`https://openweathermap.org/img/wn/${data.weather.icon}@4x.png`;
    img.src=source;
    description.innerHTML=data.weather.description;   
  }).catch=()=>{
    alert("Data not found");
  }
}


//for the past 7 days weather data of Sasaram
async function get_data(){
  weatherUl=document.getElementById("weather-cards");
  let response= await fetch(`http://localhost/weather/main.php?city=${n.value}&history=true`);
  let data = await response.json();
  console.log(`All data of ${n.value}:`,data);
  weatherUl.innerHTML = "";
  data.map((items) => {
    //Date object represents date and time. new Date() constructor is creating a new Date object with the help of existing dates
    const itemsDate= new Date(items.Weather_date);
    // Math.floor rounds to the nearest whole number and enxures the value is an integer. The Date object represents the date and time and uses the getTime() function to fetch the time in milliseconds
    const itemsTimestamp= Math.floor(itemsDate.getTime());
    // Checking if the items date is older than 7 days if older then return null
    // Multiplied by 1000 to convert time of 7 days into milliseconds
    //setHours changes the current data and time represented by Date() to midnight 
    if (itemsTimestamp < Date.now() - (8 * 24 * 60 * 60 * 1000)|| itemsTimestamp >= new Date().setHours(0, 0, 0, 0)) {
      return;
    } 
    let date = new Date(itemsTimestamp).toLocaleDateString("en-US",
    {
      weekday: "short",
      day: "numeric",
      month: "short"
    });
    weatherUl.innerHTML += `<li class="card">
    <h3>${date}</h3>
    <img class="weather_icon"src='https://openweathermap.org/img/wn/${items.icon}@2x.png'>
    <p><font size=3>Temperature:${items.Temperature}&deg;C</font></p>
    <p><font size=3>Pressure:${items.Pressure} Pa</font></p>
    <p><font size=3>Wind:${items.Wind_speed} km/hr</font></p>
    <p><font size=3>Humidity:${items.Humidity} %</p></font></li>
    `;
  });
}
//checking if the entered value is Sasaram or not
if(n!="Sasaram"){
  searchButton.addEventListener('click',()=>{
  weatherdata();
  get_data();
  });
}
//if the entered value is Sasaram then simply call the functions
weatherdata();     
get_data();
