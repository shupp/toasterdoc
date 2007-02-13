<?php

define('BTS_TEMPLATE_DIR', './tpl');
require('Book.php');

$book = new Book;
$book->display();
if(PEAR::isError($book)) {
    die($book->getMessage());
}

?>
