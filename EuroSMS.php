<?php
/**
 * EuroSMS class.
 *
 * LICENSE: The MIT License (MIT)
 *
 * Copyright (c) 2014 Detroit Studio s.r.o.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * @author     Ľuboš Beran
 * @copyright  (c) 2012-forever Ľuboš Beran
 * @version    1.0
 */

include('EuroSMS_Config.php');

class EuroSMS {

  private $phoneNumber = false;

  private $message = false;

  private $sender = false;

  private $debug = false;

  private $secure = false;

  private $state;

  private $id;

  public function setPhoneNumber($value){
    $this->phoneNumber = $value;
  }

  public function setMessage($value){
    $this->message = $value;
  }

  public function setSender($value){
    $this->sender = $value;
  }

  public function debug($value){
    $this->debug = $value;
  }

  public function isSecure($value){
    $this->secure = $value;
  }

  private function generateHash(){
    if($this->phoneNumber != false){
      $ikey = EuroSMS_Config::$integrationKey;
      $hash = substr(md5($ikey.$this->phoneNumber),10,11);
      return $hash;
    }else{
      throw new \Exception('Phone number must be set!');
    }
  }

  public function info($messageID = null){
    $id = $messageID == null ? $this->id : $messageID;
    $url = "http://as.eurosms.com/sms/Sender?action=status1SMSHTTP&id=".$id;
    $ch = curl_init();

    // set url
    curl_setopt($ch, CURLOPT_URL, $url);

    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // $output contains the output string
    $output = curl_exec($ch);

    // close curl resource to free up system resources
    curl_close($ch);

    return $output;
  }

  private function constructUrl(){

    $hash = $this->generateHash();
    $intID = EuroSMS_Config::$smsIntegrationID;
    $provider = EuroSMS_Config::$smsGateway;
    if($this->debug == true){
      $action = 'validate1SMSHTTP';
    }else{
      $action = 'send1SMSHTTP';
    }

    if($this->secure == true){
      $protocol = 'https';
    }else{
      $protocol = 'http';
    }

    $finalURL = $protocol.'://'.$provider.'?action='.$action.'&i='.$intID.'&s='.$hash.'&d=1&sender='.$this->sender.'&number='.$this->phoneNumber.'&msg='.urlencode($this->message);

    return $finalURL;
  }

  public function send(){

    $url = $this->constructUrl();
    // create curl resource
    $ch = curl_init();

    // set url
    curl_setopt($ch, CURLOPT_URL, $url);

    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // $output contains the output string
    $output = curl_exec($ch);

    // close curl resource to free up system resources
    curl_close($ch);

    $return = explode('|',$output);

    $this->state = $return[0];
    $this->id = $return[1];

    return $return[0] == 'ok' ? true : false;
  }

  public function getState(){
    return $this->state;
  }

  public function getMessageID(){
    return $this->id;
  }
}