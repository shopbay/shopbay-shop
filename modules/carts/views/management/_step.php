<div class="step-container">
    <span id="step" class="step-wrapper">
        <table class="step">
            <tbody>
                <tr>  
                     <td>
                        <?php  $index = 0; foreach ($this->getSteps($current) as $key => $value): ?>
                           <?php if ($value['visible']):?>
                                <?php $index++;?>
                                <!--<span class="arrow <?php echo $current>=Process::getId($key)?'passed':'pending';?>" style="display:<?php echo $value['arrow']?'':'none';?>">
                                       >
                                </span> -->
                                <span class="<?php echo $current>=Process::getId($key)?'passed':'pending';?>">
                                   <span class="number"><?php echo $index;?></span> 
                                   <?php echo $value['label'];?>   
                                </span>  
                           <?php endif;?>
                        <?php  endforeach;?>
                     </td>
                </tr>        
            </tbody>
        </table>
    </span>
    <span class="step-title"><?php echo Sii::t('sii','Checkout');?></span>          
</div>
