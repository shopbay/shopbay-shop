<span class="sitelogo">
    <?php echo l(app()->name,app()->urlManager->createHostUrl()); ?>
    <span class="subscript rounded3"><?php echo param('APP_VERSION'); ?></span>
</span>
<?php 
$this->widget('common.widgets.susermenu.SUserMenu',[
    'type'=>SUserMenu::WELCOME,
    'user'=>user(),
    'cssClass'=>'nav-menu',
    'offCanvas'=>false,
    'mergeWith'=>[SUserMenu::LANG],
]);