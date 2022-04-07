window.onload = function () {
    console.log("ready!");

    var current_location = document.getElementById("get_location");
    var current_url = window.location;

    /** AL hacer click en el boton llamamos a la funcion de getLocation */
    //current_location.onclick = getLocation;


    current_location.onclick = function(){
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            current_location.innerHTML = "La Geolocalizacion no es soportado por tu navegador actual.";
        }
    };

    /*
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            current_location.innerHTML = "La Geolocalizacion no es soportado por tu navegador actual.";
        }
    }*/

    function showPosition(position) {
        var current_latitude = position.coords.latitude;
        var current_longitude = position.coords.longitude;

        //Put lat and long
        document.getElementById('latitud').value = current_latitude;
        document.getElementById('longitud').value = current_longitude;

        //Make a JSON to save the latitude and longitude in Web Storage
        var coordinates = {
            'latitude': current_latitude,
            'longitude': current_longitude
        }

        // Check browser support 
        if (typeof (Storage) !== "undefined") {
            // Store the JSON of Latitud and Longitude in Web Storage
            localStorage.setItem("Location", JSON.stringify(coordinates));
            // Retrieve
            document.getElementById("get_location").innerHTML = "Sus datos de Geolocalizacion ha sido almacenados en Local Web Storage...";

            // Send the latitude and longitude
            //location.href = current_url + "?latitude=" + position.coords.latitude + "&longitude" + position.coords.longitude;
            // Retrive the JSON
            var location_json = JSON.parse(localStorage.getItem('Location'));
            console.log(location_json);
        } else {
            document.getElementById("get_location").innerHTML = "Lo sentimos, tu navegador actual no soporta Local Web Storage...";
        }
    }
};