async function fetchData(){
    const options = {
        method: 'GET',
        headers: {
            'X-RapidAPI-Key': 'ee7279b9bbmsh7172f99cef0519fp17ca96jsn6ac28430c532',
            'X-RapidAPI-Host': 'weatherapi-com.p.rapidapi.com'
        }
    };
    
    const res = await fetch('https://weatherapi-com.p.rapidapi.com/current.json?q=Nantes', options)
    const record = await res.json()  

    console.log(record)

    document.getElementById("weather").innerHTML += record.current.temp_c + " °C";
    document.getElementById("weatherIcon").innerHTML += `<img src=${record.current.condition.icon} alt='weather icon'/>`;
}

fetchData()