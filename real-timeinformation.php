<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <script src='https://api.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.js'></script>
  <link href='https://api.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.css' rel='stylesheet' />
  <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.1.0/mapbox-gl-directions.js"></script>
  <link
    rel="stylesheet"
    href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.1.0/mapbox-gl-directions.css"
    type="text/css"
  />
  <title>Safe city</title>
  <style>
    body {
      margin: 0;
    }

    #map {
      height: 100vh;
      width: 100vw; 
    }

    #info {
      position: absolute;
      top: 10px;
      left: 10px;
      background: white;
      padding: 10px;
      z-index: 1;
      border-radius: 5px;
    }
  </style>
</head>
<body>
<?php
    session_start(); // Resume existing session or start a new one

    // Example of checking if user is logged in
    if (!isset($_SESSION['user_id'])) {
        // Redirect to login page
        header('Location: index.html');
        exit();
    }
    ?>

  <div id="info">Journey Time: <span id="journey-time">N/A</span></div>
  <div id='map'></div>
  <script>
    mapboxgl.accessToken = "pk.eyJ1Ijoic295aW5rYTRnYWJyaWVsIiwiYSI6ImNseXhjZnd3YjFsbzgya3NobWY0N2dmb2YifQ.HxYsEPFWiI5iq4boE1G2Dw";

    navigator.geolocation.getCurrentPosition(successLocation, errorLocation, {
      enableHighAccuracy: true
    });

    function successLocation(position) {
      setupMap([position.coords.longitude, position.coords.latitude]);
    }

    function errorLocation() {
      setupMap([7.0134, 4.77742]);
    }

    function setupMap(center) {
      const map = new mapboxgl.Map({
        container: "map",
        style: "mapbox://styles/mapbox/streets-v11",
        center: center,
        zoom: 15
      });

      const nav = new mapboxgl.NavigationControl();
      map.addControl(nav);

      var directions = new MapboxDirections({
        accessToken: mapboxgl.accessToken,
        unit: 'metric',
        profile: 'mapbox/driving',
        controls: {
          inputs: true, 
          instructions: true
        }
      });

      map.addControl(directions, "top-left");

      directions.setOrigin(center);

      setTimeout(function() {
        const originInput = document.querySelector('.mapbox-directions-origin-input input');
        if (originInput) {
          originInput.value = 'Current Location';
        }
      }, 1000);

      directions.on('route', function(e) {
        const route = e.route[0];
        const duration = route.duration;
        const durationMinutes = Math.floor(duration / 60);
        document.getElementById('journey-time').textContent = durationMinutes + " minutes";
      });
    }
  </script>
</body>
</html>