<!DOCTYPE html>
<html lang="ru">
<head>
  <meta http-equiv="Cache-Control" content="no-cache, must-revalidate" />
</head>
<body>
<?php
/**
 * fingerquery.php
 */
require('./fingercore.php');
require('./endings.php');
$endings = array('минуту', 'минуты', 'минут');

$finger = $_GET['finger'];
$chatid = $_GET['chatid'];

$debug = true;

$rwd = new WDb($debug);
$res = $rwd->Connect();

if ($res != WDb::RWD_OK)
{
   print "A call to connect has failed: " . $rwd->errorMessage($res) . " (" . $res . ")<br />";
}
else
{
  if ($debug) {
    print "<p>";
    $v = $rwd->showSQLiteVersion();
    print "<br/>SQLite version: $v";
    $v = $rwd->showScriptVersion();
    print "<br/>Script version: $v";
    $v = $rwd->showDatabaseVersion();
    print "<br/>Database version: $v";
    print "</p>";
  }
  print "<p>";
  print "<br/>Ваш уникальный идентификатор: $finger";
  $res = $rwd->Query($finger, $chatid);
  $scenario=$res["scenario"];
  print "<br/>Used fingercore scenario: '$scenario'";
  print "</p>";

  $ret = $res["ret"];
  if ($ret != WDb::RWD_OK) {
    print "<br/>Внутренняя ошибка:" . $rwd->errorMessage($ret) . " ($ret) <br />";
  }
  elseif ($res["allow"]) {
    if (!empty($chatid)) {
      if ($res["exist"])  print "<br/>Сервис разрешён. Присоединение к существующему чату $chatid.<br/>";
      else                print "<br/>Сервис разрешён. Новый чат $chatid.<br/>";
    }
    else {
      print "<br/>Сервис разрешён. Возможно создание нового чата.";
    }
  }
  elseif ($res["blacklisted"]) {
    print "<br/>Этот идентификатор в чёрном списке. Сервис запрещён навсегда.<br/>";
  }
  else {
    $wait = floor($res["wait"]/60);
    $e = getNumEnding($wait, $endings);
    print "<br/>Сервис запрещён. Нужно подождать $wait $e .<br/>";
    $oldchatid = $res["oldchatid"];
    if (!empty($oldchatid)) {
      $r  = floor($res["reconnect"]/60);
      $e = getNumEnding($r, $endings);
      print "<br/>Возможно присоединение к существующему чату $oldchatid в течение $r $e.<br/>";
    }
  }
}
print "<br />Спасибо за интерес.";
?>
</body>
</html>
