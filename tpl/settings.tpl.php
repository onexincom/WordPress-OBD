<?php if (!defined('OBD_CONTENT')) {
    exit('Access Denied');
} ?>
<!--remove-admin-header-->

<div class="wrap">
  <h2>
    <?php esc_html_e('OBD Settings', 'onexin-bigdata'); ?>
  </h2>
  <form method="post" action="<?php esc_html_e($baseurl);?>&op=settings">
    <?php wp_nonce_field('onexin-bigdata_options', 'onexin-bigdata'); ?>
    <table class="form-table">
      <tr>
        <td valign="top"><strong>
          <?php esc_html_e('OBD Enable', 'onexin-bigdata'); ?>
          </strong></td>
        <td valign="top">
            <input type="radio" name="isopen" value="1"<?php if ($options['isopen'] == '1') {
                echo ' checked';
                                                       } ?>><?php esc_html_e('Yes', 'onexin-bigdata') ?></input>&nbsp;
            <input type="radio" name="isopen" value="0"<?php if ($options['isopen'] == '0') {
                echo ' checked';
                                                       } ?>><?php esc_html_e('No', 'onexin-bigdata') ?></input>
        </td>
      </tr>
      <tr>
        <td valign="top"><strong>
          <?php esc_html_e('Via HTML Style:', 'onexin-bigdata'); ?>
          </strong></td>
        <td valign="top"><textarea cols="50" rows="3" class="large-text code" name="from_style2"><?php esc_html_e(stripslashes($options['from_style2'])); ?></textarea>
          via: {occsite} &lt;a href=&quot;{occurl}&quot;&gt;{occurl}&lt;/a&gt;</td>
      </tr>
      <tr>
        <td valign="top"><strong>
          <?php esc_html_e('Simulated users:', 'onexin-bigdata'); ?>
          </strong></td>
        <td valign="top"><textarea cols="50" rows="3" class="large-text code" name="portal_users"><?php esc_html_e(stripslashes($options['portal_users'])); ?></textarea>
          <?php esc_html_e('Multiple | separated', 'onexin-bigdata'); ?></td>
      </tr>
      
      
      <tr>
        <td valign="top"><strong><?php esc_html_e('Title add prefix', 'onexin-bigdata'); ?></strong></td>
        <td valign="top"><input type="text" name="title_prefix" class="regular-text code" size="50" value="<?php esc_html_e($options['title_prefix']); ?>" />
            <?php esc_html_e('Multiple | separated', 'onexin-bigdata'); ?></td>
      </tr>
      <tr>
        <td valign="top"><strong><?php esc_html_e('Title add suffix', 'onexin-bigdata'); ?></strong></td>
        <td valign="top"><input type="text" name="title_suffix" class="regular-text code" size="50" value="<?php esc_html_e($options['title_suffix']); ?>" />
            <?php esc_html_e('Multiple | separated', 'onexin-bigdata'); ?></td>
      </tr>
        
      <tr>
        <td valign="top"><strong><?php esc_html_e('Title filter', 'onexin-bigdata'); ?></strong><br /><br /></td>
        <td valign="top"><textarea cols="50" rows="3" class="large-text code" name="filter_title"><?php esc_html_e(stripslashes($options['filter_title'])); ?></textarea>
            <?php esc_html_e('One per line, between the old words and the new words with lines connecting, e.g.: ', 'onexin-bigdata'); ?><code>oldcar|||newcar</code></td>
      </tr>  
      <tr>
        <td valign="top"><strong><?php esc_html_e('Content filter', 'onexin-bigdata'); ?></strong><br /><br /></td>
        <td valign="top"><textarea cols="50" rows="3" class="large-text code" name="filter_content"><?php esc_html_e(stripslashes($options['filter_content'])); ?></textarea>
            <?php esc_html_e('One per line, between the old words and the new words with lines connecting, e.g.: ', 'onexin-bigdata'); ?><code>oldcar|||newcar</code></td>
      </tr>
      
      <tr>
        <td valign="top"><strong>
          <?php esc_html_e('Rest Time:', 'onexin-bigdata'); ?>
          </strong></td>
        <td valign="top"><input type="text" name="worktime" class="regular-text code" value="<?php echo trim($options['worktime']); ?>" /></td>
      </tr>
      <tr>
        <td valign="top"><strong>
          <?php esc_html_e('Trigger N/PV:', 'onexin-bigdata'); ?>
          </strong></td>
        <td valign="top"><input type="text" name="perpv" class="regular-text code" value="<?php echo intval($options['perpv']); ?>" /></td>
      </tr>
      <tr>
        <td valign="top"><strong>
          <?php esc_html_e('OID:', 'onexin-bigdata'); ?>
          </strong></td>
        <td valign="top"><input type="text" name="oid" class="regular-text code" value="<?php echo intval($options['oid']); ?>" />
            <?php esc_html_e('Account ID, ONEXIN platform to obtain and maintain consistent: http://we.onexin.com.', 'onexin-bigdata'); ?></td>
      </tr>
      <tr>
        <td valign="top"><strong>
          <?php esc_html_e('Token:', 'onexin-bigdata'); ?>
          </strong></td>
        <td valign="top"><input type="text" name="token" class="regular-text code" value="<?php esc_html_e(stripslashes($options['token'])); ?>" />
            <?php esc_html_e('Communication TOKEN, ONEXIN platform to obtain and maintain consistent: http://we.onexin.com.', 'onexin-bigdata'); ?></td>
      </tr>
    </table>
    <p class="submit">
      <input type="submit" name="optionssubmit" class="button-primary" value="<?php esc_html_e('Save Changes', 'onexin-bigdata', 'onexin-bigdata'); ?>" />
    </p>
  </form>
</div>

<!--remove-admin-footer-->
