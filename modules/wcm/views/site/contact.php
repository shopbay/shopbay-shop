<div class="segment canvas">
    <h1><?php echo Sii::t('sii','Ask us Anything');?></h1>
    <h2><?php echo Sii::t('sii','We want to hear from you. Just saying hi to us is warmly appreciated too.');?></h2>
    <div class="contact-form-wrapper">
        <?php
            $this->widget('common.widgets.spage.SPage',[
                'layout' => false,
                'loader' => false,
                'flash'  => get_class($model),
                'body'=>$this->renderPartial('_contact_form',['model'=>$model],true),
            ]);    
        ?> 
    </div>
</div>
