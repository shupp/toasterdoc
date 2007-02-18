<?php

require_once('BTS.php');
require_once('PEAR.php');
require_once( 'I18Nv2.php');
require_once( 'I18Nv2/Negotiator.php');

class Book
{

    public $tpl = null;
    public $outline = null;
    public $rendererConfig = null;
    public $renderersAvailable = null;
    public $renderer = null;
    public $currentPage = null;
    public $previousPage = null;
    public $nextPage = null;
    public $pages = null;

    function __construct() {
        $this->tpl = new BTS;
        $this->outline = $this->simpleLoad('outline.xml');
        if(PEAR::isError($this->outline)) return $this->outline;
        $this->rendererConfig = $this->simpleLoad('renderers.xml');
        if(PEAR::isError($this->rendererConfig)) return $this->rendererConfig;
        $this->setLocale();
    }

    protected function setLocale() {
        $neg = &new I18Nv2_Negotiator;
        I18Nv2::setLocale($neg->getLocaleMatch());
        bindtextdomain("messages", "./locale");
        textdomain("messages");
    }

    protected function simpleLoad($file) {
        $xml = $this->tpl->display($file, 1);
        if(!($temp = simplexml_load_string($xml)))
           return PEAR::raiseError("Error: cannot load $file");
        return $temp;
    }

    protected function xmlObjToArray($inObject, $item) {
        $outArray = array();
        $count = 0;
        foreach($inObject as $key => $val) {
            foreach($val->$item as $key1 => $val1) {
                $outArray[$count] = (string)$val1;
                $count++;
            }
        }
        return $outArray;
    }

    protected function setPages() {
        // Build array of pages from outline
        $chapters = $this->outline->xpath('chapter');
        $this->pages = $this->xmlObjToArray($chapters, 'pages');

        // See if page was requested and set if appropriate
        if(isset($_REQUEST['page'])) {
            // See if all was requested
            if($_REQUEST['page'] == 'all') {
                $this->currentPage = 'all';
                return;
            }
            if(in_array($_REQUEST['page'], $this->pages)) {
                $this->currentPage = $_REQUEST['page'];
            }
        }
        // Otherwise, set default
        if(!isset($this->currentPage)) $this->currentPage = $this->outline->defaultPage;

        // Find previus and next pages
        $curKey = null;
        foreach($this->pages as $key => $val) {
            if($val == $this->currentPage) {
                $curKey = $key;
                break;
            }
        }
        // Set values
        if($curKey > 0) $this->previousPage = $this->pages[$curKey - 1];
        if($curKey < (count($this->pages) - 1)) $this->nextPage = $this->pages[$curKey + 1];
    }

    protected function setRenderer() {
        $this->renderersAvailable = $this->xmlObjToArray($this->rendererConfig, 'type');
        if(isset($_REQUEST['renderer'])) {
            if(in_array($_REQUEST['renderer'], $this->renderersAvailable)) {
                $this->renderer = $_REQUEST['renderer'];
            }
        }
        if($this->renderer == null) 
            $this->renderer = $this->rendererConfig->defaultRenderer;
    }

    public function display() {
        $this->setPages();
        $this->setRenderer();
        $this->tpl->assign('previousPage', $this->previousPage);
        $this->tpl->assign('nextPage', $this->nextPage);
        $this->tpl->assign('defaultPage', (string)$this->outline->defaultPage);

        // Gather contents
        if($this->currentPage != 'all') {
            $contents = $this->tpl->display($this->currentPage . '.xml', 1);
        } else {
            $contents = '';
            foreach($this->pages as $key => $val) {
                $contents .= $this->tpl->display($val . '.xml', 1);
            }
        }

        $this->tpl->assign('contents', $contents);

        $xmlIn = $this->tpl->display((string)$this->outline->mainWrapper, 1);
        // Load the XML source
        $xml = new DOMDocument;
        $xml->loadXML($xmlIn);

        $xslIn = $this->tpl->display((string)$this->renderer . '.xsl', 1);
        // Load the XSL source
        $xsl = new DOMDocument;
        $xsl->loadXML($xslIn);

        // Configure the transformer
        $proc = new XSLTProcessor;
        // attach the xsl rules
        $proc->importStyleSheet($xsl);

        echo $proc->transformToXML($xml);
        return;
    }

}
?>
