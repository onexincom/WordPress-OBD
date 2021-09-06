<?php if (!defined('OBD_CONTENT')) {
    exit('Access Denied');
} ?>
<!--remove-admin-header-->

<?php
// esc_html
$res = onexin_bigdata_htmlspecialchars($res);
?>

<table id="manage-<?php esc_html_e($bid);?>" class="bm_msg" style="width:100%">
  <input type="hidden" name="managesubmit" value="" />
  <tr style="display:none;">
    <td class="bm_c"> BID: </td>
    <td><input type="text" name="bid" class="px vm" size="30" value="<?php esc_html_e($bid);?>" /></td>
  </tr>
  <tr>
    <td class="bm_c"><?php esc_html_e('Name', 'onexin-bigdata'); ?>: </td>
    <td><input type="text" name="name" id="name" class="px vm" size="30" value="<?php echo $res['name'];?>" /></td>
  </tr>
  <tr>
    <td class="bm_c"><?php esc_html_e('URL', 'onexin-bigdata'); ?>: </td>
    <td>
    <?php if ($bid == 0) {?>
    <textarea type="text" placeholder="<?php esc_html_e('Batch Add URL, one for each line, example: Website URL###title.', 'onexin-bigdata'); ?>" value="" name="url" id="url" class="px vm" style="width: 100%; height:210px"></textarea>
    <?php } else {?>
    <input type="text" name="url" id="url" class="px vm" size="60" value="<?php echo $res['url'];?>" />
    <?php }?>    
    </td>
  </tr>
  <tr>
    <td class="bm_c"><?php esc_html_e('Module', 'onexin-bigdata'); ?>: </td>
    <td><input type="text" name="i" id="i" class="px vm" size="30" value="<?php echo $res['i'];?>" /></td>
  </tr>
  <tr>
    <td class="bm_c"><?php esc_html_e('Catid', 'onexin-bigdata'); ?>: </td>
    <td><input type="text" name="catid" id="catid" class="px vm" size="30" value="<?php echo $res['catid'];?>" /></td>
  </tr>
  <tr>
    <td class="bm_c"><?php esc_html_e('Status', 'onexin-bigdata'); ?>: </td>
    <td><input type="text" name="status" id="status" class="px vm" size="30" value="<?php if ($bid) {
        ?><?php echo $res['status'];?><?php
                                                                                    } else {
                                                                                        ?>0<?php
                                                                                    } ?>" />
      <?php esc_html_e('0.Standby, 1.Posted, 3.Disable', 'onexin-bigdata'); ?></td>
  </tr>
  <tr style="display:none;">
    <td class="bm_c"> DATE: </td>
    <td><input type="text" name="cronpublishdate" id="cronpublishdate" class="px vm" size="30" value="<?php echo $res['cronpublishdate'];?>" /></td>
  </tr>
  <tr>
    <td colspan="2"><p class="o pns"> <a href="javascript:;" data-id="<?php esc_html_e($bid);?>" id="managesubmit" class="button-primary save alignleft"><?php esc_html_e('Update', 'onexin-bigdata'); ?></a> <a href="javascript:;" style="margin-left:10px;" onclick="jQuery('#edit-' + <?php esc_html_e($bid);?>).remove();jQuery('#post-' + <?php esc_html_e($bid);?>).show();" class="button-secondary cancel alignleft"><?php esc_html_e('Cancel', 'onexin-bigdata'); ?></a> </p></td>
  </tr>
</table>
<script>
;(function ( $, window, undefined ) {

$("div #managesubmit").click(function() {
    var id = $(this).data("id");
    $.post("<?php esc_html_e($baseurl);?>&op=manage&bid="+id, $("#manage-"+id+" input, #manage-"+id+" textarea").serialize(), function(data) {
        window.location.reload();
    }, "html");
});

})( jQuery, window);
</script> 

<!--remove-admin-footer--> 
