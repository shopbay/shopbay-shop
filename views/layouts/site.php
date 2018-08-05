<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
?>   
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width = device-width, initial-scale = 1.0">
    <meta name="language" content="en" />
    <link rel="canonical" href="<?php echo request()->getHostInfo();?>">
    <title><?php echo CHtml::encode($this->pageTitle);?></title>
</head>
<body class="<?php echo $this->htmlBodyCssClass;?>">
    
    <?php echo $this->htmlBodyBegin;?>

    <div class="header-container">
        <?php $this->renderPartial($this->headerView);?>
    </div>
    
    <div class="page-container">
        <?php echo $content; ?>
    </div>
    
    <div class="footer-container">
        <a href="#" class="scrollup"><i class="fa fa-arrow-circle-up"></i></a>      
        <?php $this->renderPartial($this->footerView); ?>
    </div>    

    <?php echo $this->htmlBodyEnd;?>
    
</body>
</html>