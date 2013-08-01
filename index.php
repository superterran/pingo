<?php
/**
 * Pingo! By; Doug Hatcher superterran@gmail.com
 */
//ini_set('display_errors', true);
require('pingo.php');
$pingo = new pingo();
$do = array_merge($_GET, $_POST);
if(isset($_GET['do'])) $pingo->doAction($_GET['do'], $do);
$pingo->render('pingo.phtml');