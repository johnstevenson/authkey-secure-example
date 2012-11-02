<?php

  # this will fail if you have not installed the required packages via Composer
  require('vendor/autoload.php');

  # init demo and get the url of the server script
  $url = demoInit();


  # set up our account details
  $account = array(
    'id' => 'client-demo',
    'key' => 'U7ZPJyFAX8Gr3Hm2DFrSQy3x1I3nLdNT2U1c+ToE5Vk=',
  );


  # create a client instance
  $client = new AuthKey\Secure\Client($account);


  # set the proxy for the demo
  $client->setCurlOption(CURLOPT_PROXY, $proxy);
  $client->setHeader('Content-Type', 'application/json');

  # write some data to send (must be a string) ...
  $data = array('msg' => $message ? $message : 'Hello World');
  $data = json_encode($data);


  # ...and send it
  $result = $client->send('POST', $url, $data);


  demoDisplay();


function demoInit()
{

  ini_set('default_charset', 'UTF-8');
  ini_set('display_errors', '1');

  # get passed in message - only for demo
  $GLOBALS['message'] = !empty($_POST['message']) ? $_POST['message'] : '';

  # get passed in proxy - only for demo (fiddler - 127.0.0.1:8888)
  $GLOBALS['proxy'] = !empty($_POST['proxy']) ? $_POST['proxy'] : '';

  $path = dirname($_SERVER['PHP_SELF']) . '/server.php';
  $scheme = (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') ? 'https' : 'http';
  return $scheme . '://' . $_SERVER['HTTP_HOST'] . $path;

}


function demoDisplay()
{

  global $message, $proxy, $result, $client;

  echo '<form method="POST">';
  echo '<p>';
  echo '<input type="submit" value="Run Example"> &nbsp;&nbsp;Last run: ' . date(DATE_RFC822);
  echo '</p><p>';
  echo 'Message:&nbsp;&nbsp;';
  echo "<input type='text' name='message' value='$message' />";
  echo '&nbsp;&nbsp;Proxy:&nbsp;&nbsp;';
  echo "<input type='text' name='proxy' value='$proxy' />";
  echo '</p>';
  echo '</form>';
  echo '<pre>';

  echo '<b>return:</b> ';
  echo $result ? 'true' : 'false';
  echo '<br /><br />';

  echo '<b>error:</b> ' . $client->error;
  echo '<br /><br />';

  echo '<b>output:</b> ' . $client->output;
  echo '<br /><br />';

  echo '<hr />';
  echo '<br /><br />';
  echo '<b>Client object public properties:</b> ';
  echo '<br /><br />';

  echo getPublicProperties($client);

}


function getPublicProperties($class)
{

  $public = get_object_vars($class);
  ksort($public);
  return str_ireplace('stdClass', get_class($class), print_r((object) $public, 1));

}
