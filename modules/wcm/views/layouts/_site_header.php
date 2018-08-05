<span class="sitelogo <?php echo app()->ctrlManager->hasSiteLogo ? 'on' :'off'; ?>">
    <?php echo app()->ctrlManager->getSiteLogo(true); ?>
    <span class="subscript rounded3"><?php echo param('APP_VERSION'); ?></span>
</span>
<?php 
$this->widget('wcm.components.WcmMenu',[
    'user'=>user(),
    'cssClass'=>'nav-menu',
    'offCanvas'=>false,
]);
?>
<div class="color-bar">
    <div class="red"></div>
    <div class="yellow"></div>
    <div class="green"></div>
    <div class="blue"></div>    
</div>
