<?php

session_start();

require_once ('rdio.php');
require_once ('rdio-consumer-credentials.php');
/*
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
*/
#########################################################
#-------------------------------------------------------#
#                     --- Main ---                      #
#-------------------------------------------------------#
#########################################################
# create an instance of the Rdio object with our consumer credentials
$rdio = new Rdio(array(RDIO_CONSUMER_KEY, RDIO_CONSUMER_SECRET));

# work out what our current URL is
$current_url = "http" . ((!empty($_SERVER['HTTPS'])) ? "s" : "") .
  "://" . $_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'];

if ($_GET['logout']) {
  # to log out, just throw away the session data
  session_destroy();
  # and start again
  header('Location: '.$current_url);
}

if ($_SESSION['oauth_token'] && $_SESSION['oauth_token_secret']) {
  # we have a token in our session, let's use it
  $rdio->token = array($_SESSION['oauth_token'],
    $_SESSION['oauth_token_secret']);
  if ($_GET['oauth_verifier']) {
    # we've been passed a verifier, that means that we're in the middle of
    # authentication.
    $rdio->complete_authentication($_GET['oauth_verifier']);
    # save the new token in our session
    $_SESSION['oauth_token'] = $rdio->token[0];
    $_SESSION['oauth_token_secret'] = $rdio->token[1];
  }
 
  # make sure that we can in fact make an authenticated call
  $currentUser = $rdio->call('currentUser');
  if ($currentUser) {
    echo "<h1><$currentUser->result->firstName . 's Playlists</h1>";
  } 
} else {
  # we have no authentication tokens.
  # ask the user to approve this app
  $authorize_url = $rdio->begin_authentication($current_url);
  # save the new token in our session
  $_SESSION['oauth_token'] = $rdio->token[0];
  $_SESSION['oauth_token_secret'] = $rdio->token[1];

  header('Location: '. $authorize_url);
  echo "<meta HTTP-EQUIV='REFRESH' content=\"0; URL=$current_url\">";
}

echo "<html>";
echo "<head>";
echo "<title>Mike's DeDupe for Rdio Playlists</title>";
echo "</head>";
echo "<body>";
echo "<h2 align=\"center\">Welcome to the DeDupe Utility for Rdio</h2>";
echo "<h4 align=\"center\">Powered by Rdio, made independently</h4>"; 

/*
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
*/
echo "</body>
";
echo "</html>";
?>
