# EuroSMS
================

Helper class for EuroSMS gateway API

Installation
============

Simply download and add to your namespace or library folder.
Open ``EuroSMS_Config.php`` and set config variables with data received from EuroSMS company

Usage
=====

If you want to send message 

```
   include('EuroSMS.php);
   ...
   ...
   $sms = new EuroSMS();
   $sms->setPhoneNumber('0902XXXXXX); //set phone number
   $sms->setSender('Sender Foo);  //set sender - who is sending sms
   $sms->setMessage('First SMS from EuroSMS gateway'); //sms message
   $sms->send(); //send sms
   echo $sms->state; //here is status of sending
   echo $sms->id; //here is ID of message, is recommended to store this for check later (see $sms->info() )
```

If you want to enable debug you can set it

```
  $sms->debug(true);
```

If you want to use HTTPS instead HTTP use this

```
  $sms->isSecure(true);
```

If you want to check info about SMS that you send (is sent, sending failed, etc...)

```
  $sms = new EuroSMS();
  $sms->info($messageID);
```




