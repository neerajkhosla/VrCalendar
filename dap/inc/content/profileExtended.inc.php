          <tr>
            <td><div align="left"><?php echo USER_PROFILE_COMPANY_LABEL; ?></div></td>
            <td><div align="left">
              <input name="company" type="text" id="company">
            </div></td>
          </tr>
          <tr>
            <td><div align="left"><?php echo USER_PROFILE_TITLE_LABEL; ?></div></td>
            <td><div align="left">
              <input name="title" type="text" id="title">
            </div></td>
          </tr>
		  <tr>
            <td valign="top"><div align="left"><?php echo USER_PROFILE_ADDRESS1_LABEL; ?></div></td>
            <td><div align="left">
              <input name="address1" type="text" id="address1">
            </div></td>
          </tr>
          <tr>
            <td><div align="left"><?php echo USER_PROFILE_ADDRESS2_LABEL; ?></div></td>
            <td><div align="left">
              <input name="address2" type="text" id="address2">
            </div></td>
          </tr>
          <tr>
            <td><div align="left"><?php echo USER_PROFILE_CITY_LABEL; ?></div></td>
            <td><div align="left">
              <input name="city" type="text" id="city">
            </div></td>
          </tr>
          <tr>
            <td><div align="left"><?php echo USER_PROFILE_STATE_LABEL; ?></div></td>
            <td><div align="left">
                    <select name="state">
					<?php
						if( file_exists(DAP_ROOT . DAP_INC . '/language/customstates.php') ) {
							include (DAP_ROOT . DAP_INC . '/language/customstates.php');
						} else {
							include (DAP_ROOT . DAP_INC . '/language/states.php');
						}
                    ?>
					</select>
            </div></td>
          </tr>
          <tr>
            <td><div align="left"><?php echo USER_PROFILE_ZIP_LABEL; ?></div></td>
            <td><div align="left">
              <input name="zip" type="text" id="zip">
            </div></td>
          </tr>
          <tr>
            <td><div align="left"><?php echo USER_PROFILE_COUNTRY_LABEL; ?></div></td>
            <td><div align="left">
              <input name="country" type="text" id="country">
            </div></td>
          </tr>
          <tr>
            <td><div align="left"><?php echo USER_PROFILE_PHONE_LABEL; ?></div></td>
            <td><div align="left">
              <input name="phone" type="text" id="phone">
            </div></td>
          </tr>
          <tr>
            <td><div align="left"><?php echo USER_PROFILE_FAX_LABEL; ?></div></td>
            <td><div align="left">
              <input name="fax" type="text" id="fax">
            </div></td>
          </tr>
