<?php
	//Version 0.2
$lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];
if(file_exists($lldocroot . "/dap/dap-config.php")) include_once ($lldocroot . "/dap/dap-config.php");
	
?>
	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="dap.css" rel="stylesheet" type="text/css">
<?php getCss(); ?>
</head>
<body> 

<table align="center" width="600">
 <tr>
 <td>
 <div class="divalign">
 
 <?php include_once($lldocroot . "/dap/inc/content/selfService.inc.php"); ?>
 </div>

 </td>
 </tr>
 </table>
 
</body>

</html>
