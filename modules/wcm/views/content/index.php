<?php
/**
 * This file is part of Shopbay.org (https://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
?>
<?php $params = $this->getContentParams(); ?>
<div class="segment landing top">
    <?php echo $this->renderMarkdown('landing_overview',$params); ?>
</div>
<?php echo $this->renderMarkdown('landing_highlight1',$params); ?>
<?php echo $this->renderMarkdown('landing_highlight2',$params); ?>
<?php echo $this->renderMarkdown('landing_highlight3',$params); ?>
<?php echo $this->renderMarkdown('landing_highlight4',$params); ?>
<?php echo $this->renderMarkdown('landing_highlight5',$params); ?>
<?php
//Carousel setup for features_highlights
// Not displaying Carousel Indicators and Controls
// As Indicators seems not working, and control click UX is not so good (page jumping and url contains #admin_laptop_carousel
$this->renderCarouselWidget('$("#admin_laptop_carousel").carousel();$("#admin_mobile_carousel").carousel();');
