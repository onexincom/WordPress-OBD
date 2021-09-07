<?php if (!defined('OBD_CONTENT')) {
    exit('Access Denied');
} ?>

<?php
// esc_html
$list = onexin_bigdata_htmlspecialchars($list);
?>

<div id="screen-meta-links">
  <div id="contextual-help-link-wrap">
  <a href="<?php esc_html_e($baseurl);?>&amp;op=readme" id="help-link" class="show-settings" target="_blank"><?php esc_html_e('Help', 'onexin-bigdata'); ?></a>
  </div>
  <div id="screen-options-link-wrap">
  <a href="<?php esc_html_e($baseurl);?>&amp;op=settings" id="settings-link" class="show-settings" ><?php esc_html_e('OBD Settings', 'onexin-bigdata'); ?></a>
  </div>
</div>
 
<div class="wrap">
  <h2 class="z"> <?php esc_html_e('OBD BigData', 'onexin-bigdata'); ?> <a href="javascript:;" class="add-new-h2 iconEdit" data-id="0"><?php esc_html_e('Add New', 'onexin-bigdata'); ?></a></h2>
    <ul class="subsubsub">
      <li><a href="<?php esc_html_e($baseurl);?>"<?php if (!isset($_GET['status'])) {
            echo ' class="current"';
                   }?>><?php esc_html_e('Stats', 'onexin-bigdata'); ?></a> |</li>
      <li><a href="<?php esc_html_e($baseurl);?>&amp;status=0"<?php if ($_GET['status'] == '0') { 
            echo ' class="current"'; 
                   }?>><?php esc_html_e('Standby List', 'onexin-bigdata'); ?></a> |</li>
      <li><a href="<?php esc_html_e($baseurl);?>&amp;status=1" <?php if($_GET['status'] == '1') {
            echo ' class="current"'; 
                   }?>><?php esc_html_e( 'Posted', 'onexin-bigdata' ); ?></a></li>
    </ul>
    
  <div id="obd-content"><!-- style="min-width:660px; max-width:780px;" -->
    <div class="tablenav top">
      <div class="alignleft actions">
      <form method="get" action="<?php esc_html_e($baseurl);?>">
        <input type="hidden" name="page" value="onexin-bigdata.php">
        <input type="hidden" name="op" value="stats">
          <input type="text" name="name" value="<?php esc_html_e($_GET['name']);?>" placeholder="<?php esc_html_e('Enter the search content', 'onexin-bigdata'); ?>" class="pc vm">
          <input type="text" name="status" value="<?php esc_html_e($_GET['status']);?>" size="3" placeholder="<?php esc_html_e('Status', 'onexin-bigdata'); ?>" class="pc vm">
          <?php esc_html_e('Start date:', 'onexin-bigdata'); ?>
          <input type="text" size="16" name="starttime" class="px vm" value="<?php echo gmdate('Y-m-d H:i', $starttime)?>">
          -
          <input type="text" size="16" name="endtime" class="px vm" value="<?php echo gmdate('Y-m-d H:i', $endtime)?>">
          <input type="submit" class="button" value="<?php esc_html_e('Search', 'onexin-bigdata'); ?>">
      </form>
      </div>
      <?php if ($multi) {
            echo $multi;
      }?>    
      <br class="clear">  
    </div>
    
      <div class="xld xlda mtm" id="obd-list">
          <table class="wp-list-table widefat fixed tags">
            <thead>
              <tr>
                <td width="20"><input type="checkbox" class="checkbox vm" id="chkall"></td>
                <th width="60"><?php esc_html_e('Status', 'onexin-bigdata'); ?></th>
                <th><?php esc_html_e('Name', 'onexin-bigdata'); ?></th>
                <th width="160"><?php esc_html_e('Catid / Module / Time', 'onexin-bigdata'); ?></th>
                <th width="60"><?php esc_html_e('Options', 'onexin-bigdata'); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php if (is_array($list)) {
                    foreach ($list as $value) { ?>
              <tr id="post-<?php echo sanitize_key($value['bid']);?>">
                <td title="<?php echo sanitize_key($value['resid']);?>"><input type="checkbox" name="bidarray[]" value="<?php echo sanitize_key($value['bid']);?>" class="checkbox vm"></td>
                <td title="<?php echo sanitize_key($value['resid']);?>"><?php if ($value['status'] == '1') { ?>
                                        <?php esc_html_e('Posted', 'onexin-bigdata'); ?>
                           <?php } elseif ($value['status'] == '2') { ?>
                               <?php esc_html_e('Draft', 'onexin-bigdata'); ?>
                           <?php } elseif ($value['status'] == '0') { ?>
                                                   <?php esc_html_e('Standby', 'onexin-bigdata'); ?>
                           <?php } else { ?>
                                                   <?php esc_html_e('Disable', 'onexin-bigdata'); ?>
                           <?php } ?></td>
                <td><div style="max-width:500px;">
                                            <?php if ($value['link']) { ?>
                    (<a href="<?php echo sanitize_url($value['link']);?>" target="_blank">View</a>)
                    (<a href="post.php?post=<?php echo sanitize_url(str_replace("../?p=", "", $value['link']));?>&action=edit" target="_blank">Edit</a>)
                                            <?php } elseif ($value['ip']) { ?>
                    (<?php esc_html_e($value['ip']);?>)
                                            <?php } ?>
                    (<a href="<?php esc_html_e($baseurl);?>&amp;op=stats&amp;bid=<?php echo sanitize_key($value['bid']);?>"><?php echo sanitize_key($value['bid']);?></a>) <a href="<?php echo sanitize_url($value['url']);?>" target="_blank">
                                            <?php if ($value['name']) { ?>
                                                <?php esc_html_e($value['name']);?><br>
                                            <?php } ?>
                                            <?php echo sanitize_url($value['url']);?></a></div></td>
                <td><?php esc_html_e($value['catid']);?> / <?php echo sanitize_key($value['i']);?><br>
                                            <?php echo gmdate('Y-m-d H:i:s', $value['dateline'] + get_option('gmt_offset') * HOUR_IN_SECONDS)?></td>
                <td><a href="javascript:;" title="edit" class="iconEdit" data-id="<?php echo sanitize_key($value['bid']);?>"><?php esc_html_e('Edit', 'onexin-bigdata'); ?></a> <a href="javascript:;" title="delete" class="iconDel" data-id="<?php echo sanitize_key($value['bid']);?>"><?php esc_html_e('Delete', 'onexin-bigdata'); ?></a></td>
              </tr>
                    <?php }
              } ?>
              <tr>
                <td><input type="checkbox" class="checkbox vm" id="chkall"></td>
                <td colspan="4"><?php esc_html_e('Select All', 'onexin-bigdata'); ?> &nbsp;&nbsp;
                  <a class="button" id="bigdatasubmit" data-value="bigdatasubmit"><?php esc_html_e('Batch Deletion', 'onexin-bigdata'); ?></a>
                  &nbsp;&nbsp;
                  <a class="button" id="bigdatasubmit" data-value="bigdatasubmit3"><?php esc_html_e('Batch Disable', 'onexin-bigdata'); ?></a>
                  &nbsp;&nbsp;
                  <a class="button" id="bigdatasubmit" data-value="bigdatasubmit0"><?php esc_html_e('Batch Standby', 'onexin-bigdata'); ?></a>
                  &nbsp;&nbsp; </td>
              </tr>
            </tbody>
          </table>
      </div>
      
    <div class="tablenav bottom">
      <?php if ($multi) {
            echo $multi;
      }?>   
    </div>
      
  </div>
  <!--#obd-content--> 
</div>
<script>
;(function ( $, window, undefined ) {

$("div #bigdatasubmit").click(function() {
    var value = $(this).data("value");
    $.post("<?php esc_html_e($baseurl);?>&op=bigdata&"+value, $("#obd-list input").serialize(), function(data) {
        window.location.reload();
    }, "html");
});

$("div .iconEdit").click(function() {
    var id = $(this).data("id");    
    $("#edit-" + id).remove();  
    $("#post-" + id).hide();
    var trtd = '<tr id="edit-'+ id +'"><td colspan="5" id="edit-loading-'+ id +'"> loading... </td></tr>';
    if(id > 0){
        $(trtd).insertAfter($("#post-" + id));
    }else{
        $(trtd).insertBefore($("#obd-list tbody>tr:eq(0)"));
    }
    $.get("<?php esc_html_e($baseurl);?>&op=manage&bid=" + id, function(data) {
        data = data.split("<!--remove-admin-header-->")[1];
        data = data.split("<!--remove-admin-footer-->")[0];
        $("#edit-loading-"+ id).html(data);
    }, "html");
});

$("div .iconDel").click(function() {
    var id = $(this).data("id");
    $.get("<?php esc_html_e($baseurl);?>&op=delete&bid="+id, function(data) {
        $("#post-" + id).remove();
    }, "html");
});

$("div #chkall").click(function(){
    $("div input[type='checkbox']").prop("checked", (($(this).prop("checked") == true) ? true : false));
});

})( jQuery, window);
</script> 
