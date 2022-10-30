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

 if ($res != WDb::RWD_OK)
 {
   print "A call to connect has failed: " . $rwd->errorMessage($res) . " (" . $res . ")<br />";
 }
 else
 {

  print "<p>";
  $v = $rwd->showSQLiteVersion();
  print "<br/>SQLite version: $v";
  $v = $rwd->showScriptVersion();
  print "<br/>Script version: $v";
  $v = $rwd->showDatabaseVersion();
  print "<br/>Database version: $v";
  print "</p>";

  print "<br/>Ваш уникальный идентификатор: $finger<br/>";

  $res = $rwd->Query($finger, $chatid);
  $ret = $res["ret"];
  if ($ret != WDb::RWD_OK) {
    print "<br/>Внутренняя ошибка:" . $rwd->errorMessage($ret) . " ($ret) <br />";
  }

  elseif ($res["allow"]) {
    if ($res["exist"])  print "<br/>Сервис разрешён. Присоединение к существующему чату $chatid.<br/>";
    else                print "<br/>Сервис разрешён. Новый чат $chatid.<br/>";
  }
  else {
    $wait = $res["wait"];
    print "<br/>Сервис запрещён. Нужно подождать $wait сек.<br/>";
    $reconnect = $res["reconnect"];
    if (!is_null($reconnect)) {
      print "<br/>Возможно присоединение к существующему чату $reconnect.<br/>";
    }
  }
 }
 print "<br />Спасибо за интерес.";

?>
</body>
</html>
