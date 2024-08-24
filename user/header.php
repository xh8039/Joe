<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {http_response_code(404);exit;}
if ($this->user->hasLogin()) {
    ?>
    <script>
    let from = '<?php print $_GET['from'] ?>';
    window.location.href = from ? from : "<?php print Typecho_Common::url('/', Helper::options()->index) ?>";
    </script>
    <?php
}