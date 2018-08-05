<div>
    <span style="float:right;"><?php echo l(Sii::t('sii','More'),url('/mall?view=items'));?></span>
    <h3><?php echo Sii::t('sii','Recent Purchases');?></h3>
    <p>
        <?php echo Sii::t('sii','You may also like other interesting stuff people are buying recently');?>
    </p>
    <?php 
        $this->widget('common.widgets.SListView', array(
            'dataProvider'=>Item::model()->notMine('t')->searchRecently(3),
            'itemView'=>$this->module->getView('items.itemrecent'),
            'summaryText'=>false,
        )); 
    ?>
</div>