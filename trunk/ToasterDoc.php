<?php

require_once('Book.php');
class ToasterDoc extends Book
{
    function __construct() {
        parent::__construct();
        $this->sessionInit();
        $this->selectVarsrc();
        $this->loadVersions();
    } 

    protected function sessionInit() {
        ini_set('session.use_trans_sid',1);
        session_name('toasterSession');
        session_start();
    }

    protected function selectVarsrc() {
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
        $this->tpl->var_array['varsrc'] = $varsrc;
    }

    protected function loadVersions() {
        $versions = simplexml_load_file(BTS_TEMPLATE_DIR . '/versions.xml');
        foreach((array)$versions as $key => $val) {
            $book->tpl->var_array[$key] = $val;
        }
    }
}
