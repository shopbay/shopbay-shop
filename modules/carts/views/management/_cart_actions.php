<?php echo Sii::t('sii','Select ');?>
<span class="footer-button">
    <?php echo CHtml::link(Sii::t('sii','All'),'',array('onclick'=>'javascript:selectall(\''.$this->getCartName($shop).'\');'));?>
</span> 
/ <span class="footer-button">
    <?php echo CHtml::link(Sii::t('sii','None'),'',array('onclick'=>'javascript:selectnone(\''.$this->getCartName($shop).'\');'));?>
</span> 
<!--/ <span class="footer-button">
    <?php //echo CHtml::link(Sii::t('sii','Remove'),'',array('onclick'=>'javascript:removebatch(\''.$this->getCartName($shop).'\');'));?>
</span>-->