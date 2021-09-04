<?php if (!defined('ABSPATH')) {
    exit('Access Denied');
} ?>
<script src="<?php echo home_url('/onexin-bigdata/api.php?oid=' . sanitize_key($options['oid']) . '&_=' . microtime(true));?>" async></script>
