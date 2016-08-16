<script language="javascript" type="text/javascript" src="/dap/javascript/jsstrings.php"></script>
<script language="javascript" type="text/javascript" src="/dap/javascript/LoginInc.js"></script>
<script language="javascript" type="text/javascript">
	//Overriding authenticate URL for loginAs
	authenticate_url = "/dap/authenticateAs.php";
</script>
<?php 
	if(isset($_GET['msg'])) { ?> 
<p align="center"><font face="Arial, Helvetica, sans-serif" size="2"><font color="#CC0000"><i><b><?php echo $_GET['msg']; ?></b></i></font><i><b> </b></i></font></p> 
<p> 
  <?php }
?> 
<table width="640" border="0" cellspacing="10" cellpadding="10" align="center"> 
  <tr> 
    <td> <p align="center"><font size="3"><b><font face="Verdana, Arial, Helvetica, sans-serif"><u><font face="Arial, Helvetica, sans-serif">Login</font></u></font></b></font></p> 
      <form name="loginFormLoginInc" method="post" onSubmit="validateDAPLoginInc(document.loginFormLoginInc); return false;"> 
        <table width="600" border="0" cellspacing="10" cellpadding="10"> 
          <tr class="bodytextLarge"> 
            <td width="251"> <p>Email address of User<br />
              that you wish to log in as</p></td> 
            <td width="279"> <input type="text" name="emailAs" size="20" maxlength="60"> </td> 
          </tr>
          <tr class="bodytextLarge">
            <td><p>DAP Admin Email</p></td>
            <td><input type="text" name="email" size="20" maxlength="60" /></td>
          </tr> 
          <tr class="bodytextLarge"> 
            <td width="251">DAP Admin Password</td> 
            <td width="279"> <input type="password" name="password" size="20" maxlength="20"> </td> 
          </tr>
          <tr class="bodytextLarge"> 
            <td width="251">&nbsp;</td> 
            <td width="279"><input name="LoginDAPLoginInc" type="submit" onClick="javascript:validateDAPLoginInc(this.form); return false;" value="Login"> </td> 
          </tr> 
        </table>
      <input type="hidden" name="submitted" value="Y"> 
      <input type="hidden" name="rememberMe" id="rememberMe" value="" />
          <input type="hidden" name="forgot" value="N"> 
			<?php if(isset($_GET['request'])) { ?> 
            <input type="hidden" name="request" value="<?php echo $_GET['request']; ?>"> 
            <?php } ?> 
      </form></td> 
  </tr> 
</table> 
