<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
// use Webklex\IMAP\Facades\Client;
use Webklex\IMAP\Client;
use Webklex\IMAP\Facades\Message;
use App\Accounts;

class MailReceiverController extends Controller
{
  public function getEmailData(Request $request)
  {
    //Connect to the IMAP Server
    // $oClient = Client::account('default');
    $emailAccount = Accounts::where('id', $request->account_select)->first();
    if ($emailAccount->validate_cert == 1) {
      $emailAccountValifate = true;
    } else {
      $emailAccountValifate = false;
    }
    $oClient = new Client([
      'host'          => $emailAccount->host,
      'port'          => $emailAccount->port,
      'encryption'    => $emailAccount->encryption,
      'validate_cert' => $emailAccountValifate,
      'username'      => $emailAccount->username,
      'password'      => decrypt($emailAccount->password),
    ]);

    $oClient->connect();

    $aFolder = $oClient->getFolders();

    foreach($aFolder as $oFolder){
      $aMessage = $oFolder->searchMessages([['SINCE', Carbon::parse($request->cat_date)->format('d M y')], ['FROM', $request->from_email]], 'UTF-8');
      foreach($aMessage as $oMessage){
        $data[] = array(
          'subject' => $oMessage->subject,
          'date' => $oMessage->date,
          'bodyText' => $oMessage->getTextBody(true),
          'bodyHtml' => $oMessage->getHTMLBody(true)
        );
      }
    }
    return view('admin.mailbox.list', compact('data'));
  }


  public function str_to_address($context)
  {

    $context_parts = array_reverse(explode(" ", $context));
    $zipKey = "";
    foreach($context_parts as $key=>$str) {
        if(strlen($str)===5 && is_numeric($str)) {
            $zipKey = $key;
            break;
        }
    }
    if ($zipKey != "") {

    $context_parts_cleaned = array_slice($context_parts, $zipKey);
    $context_parts_normalized = array_reverse($context_parts_cleaned);
    $houseNumberKey = "";
    foreach($context_parts_normalized as $key=>$str) {
        if(strlen($str)>1 && strlen($str)<6 && is_numeric($str)) {
            $houseNumberKey = $key;
            break;
        }
    }

    $address_parts = array_slice($context_parts_normalized, $houseNumberKey);
    $string = implode(' ', $address_parts);
    return $string;
  } else {
    return 'no address';

  }
}

  public function sortData($data)
  {
    $mail_str = strip_tags($data);
    $mail_str = strtolower($mail_str);
    //$mail_str = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $mail_str);

    // remove signed
    $mail_str = substr($mail_str, 0, strpos($mail_str, "--"));

    // patterns
    $date_pattern = "/\d{1,2}\/\d{1,2}\/\d{2,4}/";
    $phone_pattern = '/[0-9]{3}[\-][0-9]{7}|[0-9]{3}[\s][0-9]{7}|[0-9]{3}[\s][0-9]{3}[\s][0-9]{4}|[0-9]{10}|[0-9]{3}[\-][0-9]{3}[\-][0-9]{4}/';
    $name_pattern = "/^([a-zA-Z\s ]*)/";
    $time_pattern = "/((0?[1-9]|1[012])([:.][0-5][0-9])?(\s?[ap]m)|([01]?[0-9]|2[0-3])([:.][0-5][0-9]))/";
    $zipcode_pattern = "/\s[0-9]{5}\s/";
    $address_pattern = "/(?<=>)[a-z\s]*(?=<\/a>)/";
    //$age_pattern = "/(?<=roof age:|age of roof:|age of roof|roof age|age)(.*?)(?=yrs|year|type)/";
    $age_pattern = "/(?<=age|age:|age :|roof|roof:|roof :)((\s)?\d+(\s)?(\+)?(\s)?((month|months|yrs|years|year))?)|(\d+)?(\s)?(years|year|yrs|months|month)/";
    //$matches = array();

    // returns all results in array $matches
    //preg_match_all($phone_pattern, $text, $matches);
    //$matches = $matches[0];

    //var_dump($matches);

    //preg_match($date_pattern, $mail_str, $matches);
    // $expHtml_ar = explode("/<div><br></div>/", $htmlData);
    //$expHtml_ar = preg_split('<div><br></div>', $htmlData);
    //$exp_ar = explode(PHP_EOL, $mail_str);
    $exp_ar = preg_split('#\n\s*\n#Uis', $mail_str);
    //$i = 1;
    $pieces = array();
    $p=0;
    $previous_p = 0;

/*
    foreach ($expHtml_ar as $value) {
      echo $value . "<br />-----<br />";
    }
    */
    foreach ($exp_ar as $value) {
      $value = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $value);
    //  echo $value . '<br />-----<br />';
      /*
      if (strlen($value) < 10 || (strlen($value) < 100 && $current_strlen > 250 && $current_strlen < 290) || (strlen($value) < 100 && $current_strlen < 205) || (strlen($value) > 100 && strlen($value) < 250 && $current_strlen < 205)) {
        $pieces[$previous_p]=$pieces[$previous_p].$value;
      } elseif (strlen($value) < 180 && $current_strlen > 205) {
        $pieces[$p]=$value;
      }else {
        $pieces[$p]=$value;
      }
      $previous_p = $p;
      $p++;
      $current_strlen = strlen($value);
      */

      $phones = array();
      $dates = array();
      $name = array();
      $time = array();
      $zipcode = array();
      //$address = array();
      $type = array();
      $age = array();
      preg_match_all($phone_pattern, $value, $phones);
      preg_match_all($date_pattern, $value, $dates);
      preg_match_all($name_pattern, $value, $name);
      preg_match_all($time_pattern, $value, $time);
      preg_match_all($zipcode_pattern, $value, $zipcode);
      preg_match_all($age_pattern, $value, $age);
      //preg_match_all($address_pattern, $value, $address);
      if(preg_match('/(shingles)|(tile)|(shingle)|(metal)/', $value, $type)){
        $type = $type[0];
      } else {
        $type = '---';
      };
      $zipcode = (!empty($zipcode[0])) ? $zipcode[0] : '---' ;
      $age = (!empty($age[0])) ? $age[0] : '---' ;
      $time = (!empty($time[0])) ? $time[0] : '---' ;
      $dates = (!empty($dates[0])) ? $dates[0] : '---' ;
      //  $address = $address[0];
      $name = $name[0];
      $phones = $phones[0];
      /*
      echo '<p>Zipcode: '.$zipcode[0].'</p>';
      echo '<p>Time: '.$time[0].'</p>';
      echo '<p>Name: '.$name[0].'</p>';
      echo '<p>Date: '.$dates[0].'</p>';
      //  echo '<p>Address: '.$address[0].'</p>';
      echo '<p>Age: '.$age[0].'</p>';
      echo '<p>Type: '.$type[0].'</p>';
      */
      $table_row[] = array(
        'Zipcode' => $zipcode[0],
        'Age' => $age[0],
        'Time' => $time[0],
        'Name' => $name[0],
        'Date' => $dates[0],
        'Type' => $type,
        'Address' => $this->str_to_address($value),
        'Phones' => $phones
      );
      /*
      $table_row['Phones'] =$zipcode[0];
      $table_row['Age'] =$age[0];
      $table_row['Time'] =$time[0];
      $table_row['Name'] =$name[0];
      $table_row['Date'] =$dates[0];
      $table_row['Type'] =$type[0];
      $table_row['Phones'] =$phones;
      */
    }
    return $table_row;

  }

  public function getEmailExport(Request $request)
  {
    $i = 0;
    while ($i >= 0 && $i < 10) {
      $i++;
      $fchkBx = 'chckBx_'.$i;
      $fDate = 'date_'.$i;
      $fText = 'bodyText_'.$i;
      $fHtml = 'bodyHtml_'.$i;
      if ($request->$fDate == '') {
        break;
      };
      if ($request->$fchkBx === 'export') {
        $data[] = array(
          'row' => $this->sortData($request->$fText),
          'checked' => $request->$fchkBx,
          'date' => $request->$fDate,
          'txt' => $request->$fText,
          'html' => $request->$fHtml
        );
      };

    }
    return view('admin.mailbox.export', compact('data'));
  }
}
