<?php


define('BTS_TEMPLATE_DIR', './tpl');
require('ToasterDoc.php');

$book = new ToasterDoc;
$book->display();
if(PEAR::isError($book)) {
    die($book->getMessage());
}

?>
