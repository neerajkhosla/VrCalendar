<?php
header("Content-Type: text/javascript");

if( file_exists("../inc/language/jscustom.js") ) {
	echo mb_convert_encoding(file_get_contents("../inc/language/jscustom.js"), "UTF-8", "auto");
} else {
	echo mb_convert_encoding(file_get_contents("../inc/language/jsenglish.js"), "UTF-8", "auto");
}
?>