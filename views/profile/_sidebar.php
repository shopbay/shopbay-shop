<div class="sidebar-menu avatar">
    <?php echo user()->getAvatar();?>
</div>
<?php 
$this->widget('zii.widgets.CMenu', array(
    'items'=>isset($menu)?$menu:user()->getProfileMenu(),
    'encodeLabel'=>false,                            
    'htmlOptions'=>array('class'=>'sidebar-menu theme profile'),
));
