<!doctype html>
<html>
  <head>
    <title>jQuery Network Camera Example</title>
  </head>
  <body>

    <div id="stream"></div>

    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="./js/jquery.network-camera.js"></script>
    <script>
      $('#stream').networkCamera({
        url: 'http://192.168.100.88/media_video.html',
        // url: 'https://www.google.com/logos/doodles/2015/fifa-women-world-cup-winner-tbd-country-1-5173664725073920.3-hp.jpg',
        streaming: true
      });
    </script>
  </body>
</html>