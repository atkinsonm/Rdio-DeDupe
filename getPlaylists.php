<html>
  <head>
     <title>PHP Test</title>
  </head>
  <body>
     <?php
       # remove duplicates function
       # takes playlist sorted by trackKey as an argument with original indeces assoc. with playlist
       function removeDupes($playlist) 
       {
         $dupes = array();
         for ($i = 2; $i < count($playlist); $i++) {
           if ($playlist[$i] == $playlist[$i-1]) {
             $dupes[]['track'] = $playlist[$i];
             $dupes[count($dupes)-1]['index'] = $indeces[$i]; 
           }
         }
         
         $i = 0;
         
         foreach ($dupes as $track) {
           $rdio->call('removeFromPlaylist', 
             playlist = $playlist[0],
             index = $track['index']
             count = 1,
             tracks = $track['track']
           )
           $i++;
         }
       }
     
       #########################################################
       #-------------------------------------------------------#
       #                     --- Main ---                      #
       #-------------------------------------------------------#
       #########################################################
       session_start();

       require_once "rdio.php";
       require_once "rdio-consumer-credentials.php";
       
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
       $i;
       # all tracks from all playlists
       $tracks = array();
       # tracks in the playlist currently being examined 
       $current = array();
       #original index of each track in the playlist
       $indeces = array();
       
       foreach ($myPlaylists as $playlist) {
         $current = array();
         $indeces = array();
         $current['pKey'] = $playlist['key'];
         $indeces['pKey'] = $playlist['key'];
         for ($i = 0; $i < count($playlist.trackKeys); $i++) {
            $current[$i+1] = $playlist.trackKeys[$i];
            $indeces[$+1] = $i;
         }
         array_multisort($current, $indeces);
         $tracks[] = $current;
         $tracks[count($tracks)-1]['indArr'] = $indeces; 
       }
       
       
     
     ?>
  </body>
</html>