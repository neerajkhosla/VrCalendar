<?php include_once ("dap-config.php");	 ?>
<html>
<head>
<title>Error</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="/dap/inc/content/dap.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<table width="640" border="0" cellspacing="10" cellpadding="10" align="center">
  <tr valign="top"> 
    <td colspan="3"><div align="center"> 
        <p><b><font size="3" face="Arial, Helvetica, sans-serif" color="#CC0000">Oops!</font></b></p>
        <p><font size="2" face="Arial, Helvetica, sans-serif"><b>An error has 
          occurred!</b></font></p>
        <p><font face="Arial, Helvetica, sans-serif" size="2"><font color="#CC0000"> 
          <i><b> 
          <?php 
		  
			if( isset($_GET['msg']) && ($_GET['msg'] != "") ) {
				if( defined($_GET['msg']) ) {
					$_GET['msg'] = constant($_GET['msg']);
				} else {
					$_GET['msg'] = str_replace("_"," ",$_GET['msg']);
				}
				if(isset($_GET['email'])) $_GET['msg'] = $_GET['msg'] . " " . $_SESSION['email'];
			}		  
		  	echo stripslashes($_GET['msg']); 
		  
		  ?>
          </b></i></font></font></p>
        <p><font face="Arial, Helvetica, sans-serif" size="2">Click here to go 
          <a href="javascript:history.go(-1);">back</a>.</font></p>
        </div>
    </td>
  </tr>
</table>
</body>
</html>
