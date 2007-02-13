<?php

$wrapfile = $argv[1];
$contentfile = $argv[2];
$dontdelete = $argv[3];

$wrapcontents = file_get_contents($wrapfile);
$contents = file_get_contents($contentfile);
$result = ereg_replace('{contents}', $contents, $wrapcontents);

$fp = fopen('/tmp/validate.tmp', 'w');
fwrite($fp, $result);
fclose($fp);
$out = shell_exec("xmllint --dtdvalid ./sdocbook.dtd /tmp/validate.tmp");
echo $out;
if(!isset($dontdelete))
    unlink('/tmp/validate.tmp');

?>
