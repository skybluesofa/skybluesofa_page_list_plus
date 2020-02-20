<?php  defined('C5_EXECUTE') or die("Access Denied.");
if ($controller->debugInformation) {
    if (strpos($controller->showDebugInformationLocation, 'onscreen') !== false) {
        ?>
        <div class="skybluesofa-plp-debuginfo">
            <hr>
            <pre><?php echo $controller->debugInformation; ?></pre>
        </div>
    <?php 
    }
    if (strpos($controller->showDebugInformationLocation, 'console') !== false) {
        ?>
        <script>console.log("<?php echo preg_replace('#[\r\n]+#', '\n',addslashes($controller->debugInformation)); ?>");</script>
    <?php 
    }
}
?>
