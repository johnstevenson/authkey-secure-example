<?php

  ini_set('default_charset', 'UTF-8');

  # we don't want any PHP errors being output
  ini_set('display_errors', '0');

  # so we will log them. Exceptions will be logged as well
  ini_set('log_errors', '1');
  ini_set('error_log', 'server-errors.log');


  # this will fail if you have not installed the required packages via Composer
  require('vendor/autoload.php');


  $handlers = array(
    'authorize' => 'authorize',
    'process' => 'process',
  );

  $Server = new AuthKey\Secure\Server($handlers);

  try
  {
    $Server->receive();
  }
  catch (Exception $e)
  {
    error_log($e);
  }


function authorize(AuthKey\Secure\Server $Server)
{

  /*
    The client's accountId is in $Server->accountId.

    On success set $Server->accountKey to the client's accountKey and return true.

    On error return either an array containing the error message:

      $res = array(
        'errorResponse' => 400,
        'errorMsg' => 'resource not found',
        'errorCode' => 'InvalidRequest'
        ... plus any addition info
      );

    or null/false, which will create a default error message:

      $res = array(
        'errorResponse' => 403,
        'errorMsg' => 'The AccountId you provided does not exist in our records',
        'errorCode' => 'InvalidAccountId',
      );

  */

  $res = false;

  if ($Server->accountId === 'client-demo')
  {
    $Server->accountKey = 'U7ZPJyFAX8Gr3Hm2DFrSQy3x1I3nLdNT2U1c+ToE5Vk=';
    $res = true;
  }

  return $res;

}


function process(AuthKey\Secure\Server $Server)
{

  $rfcDate = date(DATE_RFC850);

  if ($data = @json_decode($Server->input))
  {

    if (isset($data->msg))
    {
      $data->msg = 'Received message: ' . $data->msg;
      $data->time = $rfcDate;
      $Server->reply(json_encode($data));
      return;
    }

  }

  //$Server->setXHeaderOut('content-type', 'text/html');
  $Server->reply('Reply from server [' . $rfcDate . ']');

}
