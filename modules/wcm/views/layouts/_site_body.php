<?php echo $this->htmlBodyBegin;?>

<div class="page-container">
    
    <?php if (isset($this->headerView)):?>
    <div class="nav-container">
        <?php $this->renderPartial($this->headerView);?>
    </div>
    <?php endif;?>
    
    <?php echo $content; ?>
    <?php $this->smodalWidget();?>
</div>

<?php if (isset($this->footerView)):?>
<div class="footer-container">
    <a href="#" class="scrollup"><i class="fa fa-arrow-circle-up"></i></a>      
    <?php $this->renderPartial($this->footerView);?>
</div>    
<?php endif;?>

<?php echo $this->htmlBodyEnd;?>
