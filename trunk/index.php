<?php


ini_set('session.use_trans_sid',1);
// Setup session
session_name('toasterSession');
session_start();

// Location stuff
if(!isset($_REQUEST['varsrc']) && !isset($_SESSION['varsrc'])) {
        $varsrc = '/var/src';
} else {
        if(isset($_REQUEST['varsrc'])) {
                $varsrc = $_REQUEST['varsrc'];
                $_SESSION['varsrc'] = $varsrc;
        } else if(isset($_SESSION['varsrc'])) {
                $varsrc = $_SESSION['varsrc'];
        } else {
                $varsrc = '/var/src';
        }
}

define('BTS_TEMPLATE_DIR', './tpl');
require('Book.php');

$book = new Book;
$versions = simplexml_load_file(BTS_TEMPLATE_DIR . '/versions.xml');
foreach((array)$versions as $key => $val) {
    $book->tpl->var_array[$key] = $val;
}
$book->tpl->var_array['varsrc'] = $varsrc;
$book->display();
if(PEAR::isError($book)) {
    die($book->getMessage());
}

?>
