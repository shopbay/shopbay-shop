<p>
    <?php echo Sii::t('sii','Please activate your account using the token key that we have sent to your mailbox <em>{email}</em>.',array('{email}'=>$email));?>
</p>
        
<p><?php echo Sii::t('sii','Check your email and follow the link to complete the process of creating your account.');?></p>

<p>
    <?php 
        //Note: Disable activation token resending for now as this could cause new signup to click this and confuse them with multiple tokens
        //todo Need to find a proper place for this feature; A good place will be at Admin console for customer support.
        //echo CHtml::link(Sii::t('sii','Resend Activation Email'),url('register/resend',array('email'=>$email)));
    ?>
</p>
    