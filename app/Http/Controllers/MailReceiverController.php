<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
// use Webklex\IMAP\Facades\Client;
use Webklex\IMAP\Client;
use Webklex\IMAP\Facades\Message;
use App\Accounts;
use App\DocumentHeaders;
use App\Leads;
use App\Inbox;
use App\InboxSettings;
use App\Http\Requests\CreateInboxRequest;
use App\Http\Requests\CreateLeadsRequest;
use PDF;

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

  public function fetchEmails(){
    $inboxsettings = InboxSettings::with("accounts")->get();
    foreach ($inboxsettings as $inboxset) {
      $emailAccount = Accounts::where('id', $inboxset->accounts->id)->first();
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
        $aMessage = $oFolder->searchMessages([['SINCE', Carbon::parse($inboxset->inbox_settings_date)->format('d M y')], ['FROM', $inboxset->inbox_settings_sender]], 'UTF-8');
        foreach($aMessage as $oMessage){
          if (!(Inbox::where('inbox_date', '=', $oMessage->date)->exists())) {
            $inbox= new Inbox();
            $inbox->inbox_sender = $inboxset->inbox_settings_sender;
            $inbox->inbox_date = $oMessage->date;
            $inbox->inbox_subject = $oMessage->subject;
            $inbox->inbox_text_body = $oMessage->getTextBody(true);
            $inbox->inbox_html_body = $oMessage->getHTMLBody(true);
            $inbox->inbox_edited_body = $oMessage->getTextBody(true);
            $inbox->inbox_leads_count = '';
            $inbox->save();
          }
          /*
          $data['arr'] = array(
          'inbox_sender' => $inboxset->inbox_settings_sender,
          'inbox_date' => $oMessage->date,
          'inbox_subject' => $oMessage->subject,
          'inbox_text_body' => $oMessage->getTextBody(true),
          'inbox_html_body' => $oMessage->getHTMLBody(true),
          'inbox_edited_body' => $oMessage->getTextBody(true),
          'inbox_leads_count' => ''
        );
        */
        //Inbox::create($data->all());
        //$this->storeEmails($data);
      }
    }
  }


  //return view('admin.inbox');
  return redirect()->route(config('quickadmin.route').'.inbox.index');
}


public function storeEmails( $request)
{
  //$arr = serialize($request);

  $inbox= new Inbox();
  $inbox->inbox_sender = $request['inbox_sender'];
  $inbox->inbox_date = $request['inbox_date'];
  $inbox->inbox_subject = $request['inbox_subject'];
  $inbox->inbox_text_body = $request['inbox_text_body'];
  $inbox->inbox_html_body = $request['inbox_html_body'];
  $inbox->inbox_edited_body = $request['inbox_edited_body'];
  $inbox->inbox_leads_count = $request['inbox_leads_count'];
  $inbox->save();

  //Inbox::create($request->all());

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
  $notes_pattern = "/((0?[1-9]|1[012])([:.][0-5][0-9])?(\s?[ap]m)|([01]?[0-9]|2[0-3])([:.][0-5][0-9]))[^\]].*/";
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
$notes = array();
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
preg_match_all($notes_pattern, $value, $notes);
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
$phones = preg_replace('/\s+/', '', $phones[0]);
$notes = (!empty($notes[0])) ? $notes[0] : '---' ;
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
  'Lead' => $value,
  'Zipcode' => $zipcode[0],
  'Age' => $age[0],
  'Time' => $time[0],
  'Name' => $name[0],
  'Date' => $dates[0],
  'Type' => $type,
  'Address' => $this->str_to_address($value),
  'Phones' => $phones,
  'Notes' => $notes[0]
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
  $documentheaders = DocumentHeaders::all();

  $i = 0;
  while ($i >= 0 && $i < 100) {
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
  return view('admin.mailbox.export', compact('data', 'documentheaders'));
}
public function leadsCount()
{
  $inbox = Inbox::all();
  foreach ($inbox as $oneEmail) {
    $rows = $this->sortData($oneEmail->inbox_edited_body);
    foreach ($rows as $row) {
      $lead= new Leads();
      $lead->lead_name = $row['Name'];
      $lead->lead_phones =implode("|",$row['Phones']);
      $lead->lead_time = date('g:i a', strtotime($row['Time']));
      $lead->lead_roof_age = $row['Age'];
      $lead->lead_foor_type = $row['Type'];
      if (strlen($row['Address'])<255) {
        $lead->lead_address = $row['Address'];
      } else {
        $lead->lead_address = 'Incorrect Data';
      }
      $lead->lead_notes = $row['Notes'];
      $lead->inbox_id = $oneEmail->id;
      $lead->save();

      /*
      'Lead' => $value,
      'Zipcode' => $zipcode[0],
      'Age' => $age[0],
      'Time' => $time[0],
      'Name' => $name[0],
      'Date' => $dates[0],
      'Type' => $type,
      'Address' => $this->str_to_address($value),
      'Phones' => $phones,
      'Notes' => $notes[0]
      */
    }
  }

  return redirect()->route(config('quickadmin.route').'.inbox.index');
}
public function leadsTest()
{
  return view('welcome');
}
public function getEmailFileExport(Request $request)
{
  $headerFooter = DocumentHeaders::where('id', $request->headerFooter)->first();

  $i = 0;
  while ($i >= 0 && $i < 300) {
    $i++;
    $fLoop = 'loop_'.$i;
    $fchckFileBx = 'chckFileBx_'.$i;
    $fName = 'name_'.$i;
    $fPhones = 'phones_'.$i;
    $fTime = 'time_'.$i;
    $fAge = 'age_'.$i;
    $fType = 'type_'.$i;
    $fAddress = 'address_'.$i;
    $fNotes = 'notes_'.$i;
    /*    if ($request->$fLoop == '') {
    break;
  };
  */
  if ($request->$fchckFileBx === 'exportToFile') {
    $data[] = array(
      'checked' => $request->$fchckFileBx,
      'name' => $request->$fName,
      'phones' => $request->$fPhones,
      'time' => date('g:i a', strtotime($request->$fTime)),
      'age' => $request->$fAge,
      'type' => $request->$fType,
      'address' => $request->$fAddress,
      'notes' => $request->$fNotes,
    );
    usort($data, function($a, $b) {
      return $a['time'] <=> $b['time'];
    });
  };

}
$pdf = PDF::loadView('admin.mailbox.download', compact('data', 'headerFooter'));
return $pdf->stream('admin.mailbox.download');
//  return view('admin.mailbox.download', compact('data'));
}
public function getEmailXLSExport(Request $request)
{
  return view('pages.home');
}

}
