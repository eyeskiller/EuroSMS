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
   
   //EuroSMS instance
   $sms = new EuroSMS();
   
   //set phone number
   $sms->setPhoneNumber('0902XXXXXX);
   
   //set sender - who is sending sms
   $sms->setSender('Sender Foo);
   
   //set message
   $sms->setMessage('First SMS from EuroSMS gateway');  
   
    //send sms
   $sms->send();
   
   //status of sending
   echo $sms->getState();
   
   //ID of message, is recommended to store this for check later (see $sms->info() )
   echo $sms->getMessageID();
```

If you want to enable debug you can set it

```
  $sms->debug(true);
```

If you want disable HTTPS

```
  $sms->isSecure(false);
```

If you want to check info about SMS that you send (is sent, sending failed, etc...)

```
  $sms = new EuroSMS();
  $sms->info($messageID);
```




