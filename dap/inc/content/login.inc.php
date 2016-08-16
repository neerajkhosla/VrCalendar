<script language="javascript" type="text/javascript" src="/dap/javascript/jsstrings.php"></script>
<script language="javascript" type="text/javascript" src="/dap/javascript/LoginInc.js"></script>

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
            <td width="251"> <p>Email address<br> 
                <span class="bodytext">(which will also be your username)</span></p></td> 
            <td width="279"> <input type="text" name="email" size="20" maxlength="60"> </td> 
          </tr> 
          <tr class="bodytextLarge"> 
            <td width="251">Password</td> 
            <td width="279"> <input type="password" name="password" size="20" maxlength="20"> </td> 
          </tr> 
          <tr class="bodytextLarge">
            <td>&nbsp;</td>
            <td><label>
              <input name="rememberMe" type="checkbox" id="rememberMe" value="rememberMe" />
            Remember me (for 2 weeks)</label></td>
          </tr>
          <tr class="bodytextLarge"> 
            <td width="251">&nbsp;</td> 
            <td width="279"><input name="LoginDAPLoginInc" type="submit" onClick="javascript:validateDAPLoginInc(this.form); return false;" value="Login"> </td> 
          </tr> 
        </table> 
        <p align="center" class="plainLink"><a href="#" class="bullettext" onClick="doDapForgotPasswordLoginInc();">Forgot Password</a></p> 
          <input type="hidden" name="submitted" value="Y"> 
          <input type="hidden" name="forgot" value="N"> 
			<?php if(isset($_GET['request'])) { ?> 
            <input type="hidden" name="request" value="<?php echo $_GET['request']; ?>"> 
            <?php } ?> 
      </form></td> 
  </tr> 
</table> 
