<!DOCTYPE html>
<html lang="ru">
<head>
</head>
<body>
<?php
/**
 * fingerquery.php
 */
 require('./fingercore.php');

 $finger = $_GET['finger'];
 $chatid = $_GET['chatid'];

 $rwd = new WDb(false);
 $res = $rwd->Connect();      

 if (res != WDb::RWD_OK)
 {
   print "A call to connect has failed: " . $rwd->errorMessage($res) . " (" . $res . ")<br />";
 }
 else
 {
  print "<br/>Ваш уникальный идентификатор $finger<br/>";
  $res = $rwd->Query($finger, $chatid);
   $r = $res["ret"];
   if ($r != WDb::RWD_OK) {
      print "<br/>Внутренняя ошибка:" . $rwd->errorMessage($r) . " ($r) <br />";
   }
   elseif ($res["allow"]) {
    if ($res["exist"])  print "<br/>Сервис разрешён. Присоединение к существующему чату $chatid.<br/>"; 
    else                print "<br/>Сервис разрешён. Новый чат $chatid.<br/>"; 
   }
   else {
    $w = $res["wait"];
    print "<br/>Сервис запрещён. Нужно подождать $w сек.<br/>";
   }
 }
 print "<br />Спасибо за интерес.";
 
?>
</body>
</html>
