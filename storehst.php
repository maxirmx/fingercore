<?php
  /**
   * storehst. AJAX интерфейс для сохранения истории чата
   *
   * @category history
   * @package fingercore
   * @subpackage main
   * @version 00.05.00
   * @author  Максим Самсонов <maxim@samsonov.net>
   * @copyright  2022 Максим Самсонов, его родственники и знакомые
   * @license    https://github.com/maxirmx/p.samsonov.net/blob/main/LICENSE MIT
   */

  header('Cache-Control: no-cache, must-revalidate');
  header('Content-type: application/json; charset=utf-8');

  require(__DIR__. '/fingercore.php');

  $history = $_GET['history'];
  $chatid = $_GET['chatid'];

  $rwd = new WDb(false);
  $rwd->Connect();
  $res = $rwd->storeHistory($chatid, $history);

  print json_encode($res);
?>
