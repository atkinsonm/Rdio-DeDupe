<html>
  <head>
     <title>PHP Test</title>
  </head>
  <body>
     <?php
       session_start();

       require_once '../rdio.php';
       require_once 'rdio-consumer-credentials.php';
       
       # create an instance of the Rdio object with our consumer credentials
       $rdio = new Rdio(array(RDIO_CONSUMER_KEY, RDIO_CONSUMER_SECRET));
     
       # authenticate against the Rdio service
       $url = $rdio->begin_authentication('oob');
       print "Go to: $url\n";
       print "Then enter the code: ";
       $verifier = trim(fgets(STDIN));
       $rdio->complete_authentication($verifier);

       # find out what playlists you created
       $myPlaylists = $rdio->call('getPlaylists')->result->owned;
     
     ?>
  </body>
</html>