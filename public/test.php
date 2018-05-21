<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>

<?php
$string = 'April 15, 2003';
$pattern = '/(\w+) (\d+), (\d+)/i';
$replacement = '${1}1,$3';
// разбиваем строку по произвольному числу запятых и пробельных символов,
// которые включают в себя  " ", \r, \t, \n и \f

$mail_str = "
robert barnett 9037204820 sp felipe longview master david 701 robin ln white
oak tx 75693 04/17/07 at:12pm   spoke to:robert  homeowner:yes  type of
roof:shingles  age of roof:11  singlefam.home   story 2  insurance:yes  no
contract  cell:same 4/17/2018 12pm
roosevelt jackson 9032364021 w nancy longview master david 206 isgren dr
longview tx 75602 04/17/2018  at:  11:30am  spoke to: ms. jackson
homeowner:  yes  age of roof:  5+ yrs   type of roof: shingles  single fam
home: yes  story: 1  insurance: state farm  contract: no  cell phone: same
4/17/2018 11:30am
lawrence pasche 9032972796 w dano longview master david 314 meadowview rd
longview tx 75604 04/17/2018 10am  spoke to: lawrence  homeowner: yes  age
of roof: 10years  type of roof: shingle  single family home: single  story:
1  insurance: all state  contract: no  cellphone: no 4/17/2018 10am
billy dearion 9034527603 w dano longview master david 2301 armond dr
longview tx 75602 04/17/2018 5pm  spoke to: bobby  homeowner: yes  age of
roof: 5years  type of roof: shingles  single family home: single  story: 1
insurance: yes  contract: no  cellphone: 9034527603 4/17/2018 5pm
pamela anthony 9037574027 f charly21 longview master david 903 s fredonia st
longview tx 75602 04/17/2018 at : 6pm  spoke to : pamela  homeowner : yes
age of roof : 5-6  type of roof : shingles   single family : yes  1 or 2
story : 1  insurance : all state yes  contract : no  cell : 4/17/2018 6pm
charles crump 9032367179 w gil longview master david 1204 temple st longview
tx 75604 04/17/2018 at 10am  spoke to charles  homeowner  age of roof 8+
years  type of roof shingles  single family yes  story 1  insurance yes
contract no  cellphone same 4/17/2018 10am
melvin beall 9037579444 w dano longview master david 2514 s 13th st longview
tx 75602 04/17/2018 at 12pm  spoke to: melvin  homeowner: yes  age of roof:
6years  type of roof: metal  single family home: single  story: 1
insurance: farmers  contract: no  cellphone: 9037579444 4/17/2018 12pm
dawn sauceda 9032958962 w nancy longview master david 513 crystal dr
longview tx 75604 04/17/2018 at: 12pm  spoke to: dawn  homeowner: yes  age
of roof: 5+ yrs  type of roof: shingles  single fam home: yes  story: 1
insurance: travellers  contract: no  cell phone: same    4/17/2018 12pm
mildred ragsdale 9032970758 w nancy longview master david 1101 america dr
longview tx 75604 04/17/2018 at: 6pm  spoke to: mildred  homeowner: yes
age of roof: 10+  type of roof: shingles  single fam home: yes   story: 1
insurance: yes  contract: no  cell phone: same 4/17/2018 6pm
james lomax 9037532304 w faith12 longview master david 1322 colgate dr
longview tx 75601 4/17/2018 at 4pm  spoke to james  homeowner  age of roof
3years  type of roof shingles  single family home  1 story   insurance yes
no signed contract  cell same  4/17/2018 4pm
maria torres 9039186535 w faith12 longview master david 222 cambridge ln
longview tx 75601 4/17/2018 at 5:30pm  spoke to maria   homeowner  age of
roof 7years  type of roof shingles  single family home  1 story   insurance
farm b.  no signed contract  cell 9039186535  4/17/2018 5:30pm
doris johnson 4306257351 w faith12 longview master david 501 harrison st
longview tx 75601 4/17/2018 at 6pm  spoke to doris  homeowner  age of roof
5years  type of roof shingles  single family home  1 story   insurance
mortgage  no signed contract  cell 9039179792  has confirmed damages
4/17/2018 6pm
ronnie mckinley 9032359888 w chuck longview master david 3821 holly ridge dr
longview tx 75605 4/17/2018 at: 12pm  spoke to ronnie  homeowner  age of
roof 22yrs  type of roof shingles  single family home  1 story  insurance
yes  no contract  cellphone same 4/17/2018 12pm
cathleen shafer 9037599028 w chuck longview master david 408 e hope dr
longview tx 75604 4/17/2018 at: 4pm  spoke to cathleen  homeowner  age of
roof 5yrs  type of roof shingles  single family home  1 story  insurance
yes  no contract  cellphone 4/17/2018 4pm
avril hoffman 9514540368 w chuck longview master david 1820 page rd longview
tx 75601 4/17/2018 at: 9am  spoke to avril  homeowner  age of roof 8yrs
type of roof shingles  single family home  1 story  insurance yes  no
contract  cellphone same 4/17/2018 9am";
//$keywords = preg_split("/[\s,]+/", $mail_str);
$mail_str = strtolower($mail_str);
$mail_str = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $mail_str);
echo $mail_str;
// remove signed
$mail_str = substr($mail_str, 0, strpos($mail_str, "--"));

// dates
$date_pattern = "/\d{1,2}\/\d{1,2}\/\d{2,4}/";
$phone_pattern = '/[0-9]{3}[\-][0-9]{7}|[0-9]{3}[\s][0-9]{7}|[0-9]{3}[\s][0-9]{3}[\s][0-9]{4}|[0-9]{10}|[0-9]{3}[\-][0-9]{3}[\-][0-9]{4}/';
$name_pattern = "/^([a-zA-Z\s ]*)/";
$time_pattern = "/((0?[1-9]|1[012])([:.][0-5][0-9])?(\s?[ap]m)|([01]?[0-9]|2[0-3])([:.][0-5][0-9]))/";
$zipcode_pattern = "/\s[0-9]{5}\s/";
$address_pattern = "/(?<=>)[a-z\s]*(?=<\/a>)/";
//$age_pattern = "/(?<=roof age:|age of roof:|age of roof|roof age|age)(.*?)(?=yrs|year|type)/";
$age_pattern = "/(?<=age|age:|age :|roof|roof:|roof :)((\s)?\d+(\s)?(\+)?(\s)?((month|months|yrs|years|year))?)|(\d+)?(\s)?(years|year|yrs|months|month)/";
$matches = array();

// returns all results in array $matches
//preg_match_all($phone_pattern, $text, $matches);
//$matches = $matches[0];

//var_dump($matches);

//preg_match($date_pattern, $mail_str, $matches);


$exp_ar = explode(PHP_EOL, $mail_str);
$i = 1;
?>
<div>
<?php
$i1=0;
$p1 = 0;
foreach ($exp_ar as $value) {
  $phones = array();
  $dates = array();
  $name = array();
  $time = array();
  $zipcode = array();
  $address = array();
  $type = array();
  $age = array();
  preg_match_all($phone_pattern, $value, $phones);
  preg_match_all($date_pattern, $value, $dates);
  preg_match_all($name_pattern, $value, $name);
  preg_match_all($time_pattern, $value, $time);
  preg_match_all($zipcode_pattern, $value, $zipcode);
  preg_match_all($age_pattern, $value, $age);
  preg_match_all($address_pattern, $value, $address);
  preg_match('/(shingles)|(tile)|(shingle)|(metal)/', $value, $type);
  $zipcode = $zipcode[0];
  $age = $age[0];
  $address = $address[0];
  $time = $time[0];
  $name = $name[0];
  $dates = $dates[0];
  $phones = $phones[0];
  /*
  echo '<p>Zipcode: '.$zipcode[0].'</p>';
  echo '<p>Time: '.$time[0].'</p>';
  echo '<p>Name: '.$name[0].'</p>';
  echo '<p>Date: '.$dates[0].'</p>';
  echo '<p>Address: '.$address[0].'</p>';
  echo '<p>Age: '.$age[0].'</p>';
  echo '<p>Type: '.$type[0].'</p>';
  echo "<pre>";
  var_dump($phones);
  echo "</pre>";
  */

  if (strlen($value) < 10 || (strlen($value) < 100 && $current_strlen > 250 && $current_strlen < 290) || (strlen($value) < 100 && $current_strlen < 205) || (strlen($value) > 100 && strlen($value) < 250 && $current_strlen < 205)) {
    echo "<br />----added ". $i1 . "|" . $p1 ."<br />";
    echo $value;
    echo "<br />----<br />";
  } elseif (strlen($value) < 180 && $current_strlen > 205) {
    echo "<br /><br />";
    echo $value;
  }else {
    echo "<br /><br />";
    echo $value;
  }
  echo "<br />----current ". $i1 . "|" . $p1 ."<br />";

  $p1 = $i1;
  $i1++;
  $current_strlen = strlen($value);
}
//print_r($exp_ar);
//print_r($keywords);
//echo preg_replace($pattern, $replacement, $string);
?>
</div>
</body>
</html>
