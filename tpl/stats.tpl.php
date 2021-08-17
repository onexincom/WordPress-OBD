<?php if(!defined('OBD_CONTENT')) exit('Access Denied'); ?>
<!--<script src="wp-includes/js/jquery/jquery.min.js"></script>-->

<div id="screen-meta-links">
  <div id="contextual-help-link-wrap">
  <a href="<?php echo $baseurl;?>&amp;op=readme" id="help-link" class="show-settings" target="_blank"><?php echo __( 'Help', 'onexin-bigdata' ); ?></a>
  </div>
  <div id="screen-options-link-wrap">
  <a href="<?php echo $baseurl;?>&amp;op=settings" id="settings-link" class="show-settings" ><?php echo __( 'OBD Settings', 'onexin-bigdata' ); ?></a>
  </div>
</div>
 
<div class="wrap">
  <h2 class="z"> <?php echo __( 'OBD BigData', 'onexin-bigdata' ); ?> <a href="javascript:;" class="add-new-h2 iconEdit" data-id="0"><?php echo __( 'Add New', 'onexin-bigdata' ); ?></a></h2>
    <ul class="subsubsub">
      <li><a href="<?php echo $baseurl;?>&amp;op=stats"<?php if($_GET['op']=='stats') echo ' class="current"';?>><?php echo __( 'Stats', 'onexin-bigdata' ); ?></a> |</li>
      <li><a href="<?php echo $baseurl;?>"<?php if(empty($_GET['op'])) echo ' class="current"';?>><?php echo __( 'Standby List', 'onexin-bigdata' ); ?></a></li>
    </ul>
    
  <div id="obd-content"><!-- style="min-width:660px; max-width:780px;" -->
    <div class="tablenav top">
      <div class="alignleft actions">
      <form method="get" action="<?php echo $baseurl;?>">
        <input type="hidden" name="page" value="onexin-bigdata.php">
        <input type="hidden" name="op" value="stats">
          <input type="text" name="name" value="<?php echo esc_html($_GET['name']);?>" placeholder="<?php echo __( 'Enter the search content', 'onexin-bigdata' ); ?>" class="pc vm">
          <input type="text" name="status" value="<?php echo esc_html($_GET['status']);?>" size="3" placeholder="<?php echo __( 'Status', 'onexin-bigdata' ); ?>" class="pc vm">
          <?php echo __( 'Start date:', 'onexin-bigdata' ); ?>
          <input type="text" size="16" name="starttime" class="px vm" value="<?php echo gmdate('Y-m-d H:i', $starttime)?>">
          -
          <input type="text" size="16" name="endtime" class="px vm" value="<?php echo gmdate('Y-m-d H:i', $endtime)?>">
          <input type="submit" class="button" value="<?php echo __( 'Search', 'onexin-bigdata' ); ?>">
      </form>
      </div>
      <?php if($multi) {echo $multi;}?>    
      <br class="clear">  
    </div>
    
      <div class="xld xlda mtm" id="obd-list">
          <table class="wp-list-table widefat fixed tags">
            <thead>
              <tr>
                <td width="20"><input type="checkbox" class="checkbox vm" id="chkall"></td>
                <th width="60"><?php echo __( 'Status', 'onexin-bigdata' ); ?></th>
                <th><?php echo __( 'Name', 'onexin-bigdata' ); ?></th>
                <th width="160"><?php echo __( 'Catid / Module / Time', 'onexin-bigdata' ); ?></th>
                <th width="60"><?php echo __( 'Options', 'onexin-bigdata' ); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php if(is_array($list)) foreach($list as $value) { ?>
              <tr id="post-<?php echo $value['bid'];?>">
                <td title="<?php echo $value['resid'];?>"><input type="checkbox" name="bidarray[]" value="<?php echo $value['bid'];?>" class="checkbox vm"></td>
                <td title="<?php echo $value['resid'];?>"><?php if($value['status']=='1') { ?>
                  <?php echo __( 'Posted', 'onexin-bigdata' ); ?>
                  <?php } elseif($value['status']=='2') { ?>
                  <?php echo __( 'Draft', 'onexin-bigdata' ); ?>
                  <?php } elseif($value['status']=='0') { ?>
                  <?php echo __( 'Standby', 'onexin-bigdata' ); ?>
                  <?php } else { ?>
                  <?php echo __( 'Disable', 'onexin-bigdata' ); ?>
                  <?php } ?></td>
                <td><div style="max-width:500px;">
                    <?php if($value['link']) { ?>
                    (<a href="<?php echo $value['link'];?>" target="_blank">View</a>)
                    (<a href="post.php?post=<?php echo str_replace("../?p=", "", $value['link']);?>&action=edit" target="_blank">Edit</a>)
                    <?php } elseif($value['ip']) { ?>
                    (<?php echo $value['ip'];?>)
                    <?php } ?>
                    (<a href="<?php echo $baseurl;?>&amp;op=stats&amp;bid=<?php echo $value['bid'];?>"><?php echo $value['bid'];?></a>) <a href="<?php echo $value['url'];?>" target="_blank">
                    <?php if($value['name']) { ?>
                    <?php echo $value['name'];?><br>
                    <?php } ?>
                    <?php echo $value['url'];?></a></div></td>
                <td><?php echo $value['catid'];?> / <?php echo $value['i'];?><br>
                  <?php echo gmdate('Y-m-d H:i:s', $value['dateline'] + get_option( 'gmt_offset' ) * HOUR_IN_SECONDS)?></td>
                <td><a href="javascript:;" title="edit" class="iconEdit" data-id="<?php echo $value['bid'];?>"><?php echo __( 'Edit', 'onexin-bigdata' ); ?></a> <a href="javascript:;" title="delete" class="iconDel" data-id="<?php echo $value['bid'];?>"><?php echo __( 'Delete', 'onexin-bigdata' ); ?></a></td>
              </tr>
              <?php } ?>
              <tr>
                <td><input type="checkbox" class="checkbox vm" id="chkall"></td>
                <td colspan="4"><?php echo __( 'Select All', 'onexin-bigdata' ); ?> &nbsp;&nbsp;
                  <a class="button" id="bigdatasubmit" data-value="bigdatasubmit"><?php echo __( 'Batch Deletion', 'onexin-bigdata' ); ?></a>
                  &nbsp;&nbsp;
                  <a class="button" id="bigdatasubmit" data-value="bigdatasubmit3"><?php echo __( 'Batch Disable', 'onexin-bigdata' ); ?></a>
                  &nbsp;&nbsp;
                  <a class="button" id="bigdatasubmit" data-value="bigdatasubmit0"><?php echo __( 'Batch Standby', 'onexin-bigdata' ); ?></a>
                  &nbsp;&nbsp; </td>
              </tr>
            </tbody>
          </table>
      </div>
      
    <div class="tablenav bottom">
      <?php if($multi) {echo $multi;}?>   
    </div>
      
  </div>
  <!--#obd-content--> 
</div>
<script>
;(function ( $, window, undefined ) {

$("div #bigdatasubmit").click(function() {
	var value = $(this).data("value");
	$.post("<?php echo $baseurl;?>&op=bigdata&"+value, $("#obd-list input").serialize(), function(data) {
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
	$.get("<?php echo $baseurl;?>&op=manage&bid=" + id, function(data) {
		data = data.split("<!--remove-admin-header-->")[1];
		data = data.split("<!--remove-admin-footer-->")[0];
		$("#edit-loading-"+ id).html(data);
	}, "html");
});

$("div .iconDel").click(function() {
	var id = $(this).data("id");
	$.get("<?php echo $baseurl;?>&op=delete&bid="+id, function(data) {
		$("#post-" + id).remove();
	}, "html");
});

$("div #chkall").click(function(){
	$("div input[type='checkbox']").prop("checked", (($(this).prop("checked") == true) ? true : false));
});

})( jQuery, window);
</script> 
