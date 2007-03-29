<?php

/**
 * ToasterDoc 
 * 
 * @uses Book
 * @package ToasterDoc
 * @version $id$
 * @copyright 2007 Bill Shupp
 * @author Bill Shupp <hostmaster@shupp.org> 
 * @license GPL 2.0  {@link http://www.gnu.org/licenses/gpl.txt}
 */

require_once 'Book.php';

/**
 * ToasterDoc 
 * 
 * @uses Book
 * @package ToasterDoc
 * @version $id$
 * @copyright 2007 Bill Shupp
 * @author Bill Shupp <hostmaster@shupp.org> 
 * @license GPL 2.0  {@link http://www.gnu.org/licenses/gpl.txt}
 */
class ToasterDoc extends Book
{
    /**
     * __construct 
     * 
     *  Constructor
     * 
     * @access protected
     * @return void
     */
    function __construct() {
        parent::__construct();
        $this->sessionInit();
        $this->selectVarsrc();
        $this->loadVersions();
    } 

    /**
     * sessionInit 
     * 
     * Session initialization
     * 
     * @access protected
     * @return void
     */
    protected function sessionInit() {
        ini_set('session.use_cookies',1);
        ini_set('session.use_trans_sid',0);
        session_name('toasterSession');
        session_start();
    }

    /**
     * selectVarsrc 
     * 
     * Select the software source location.  This
     * is a variable used through the document
     * 
     * @access protected
     * @return void
     */
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

    /**
     * loadVersions 
     * 
     * Load version information from versions.xml
     * 
     * @access protected
     * @return void
     */
    protected function loadVersions() {
        $versions = simplexml_load_file(BTS_TEMPLATE_DIR . '/versions.xml');
        foreach((array)$versions as $key => $val) {
            $this->tpl->var_array[$key] = $val;
        }
    }
}
