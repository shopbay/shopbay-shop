<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of CssGetAction
 *
 * @author kwlok
 */
class CssGetAction extends CAction 
{
    public $modelFinder = 'currentShop';
    /**
     * Run action
     */
    public function run() 
    {
        $theme = isset($_GET['theme']) ? $_GET['theme'] : null;
        $style = isset($_GET['style']) ? $_GET['style'] : null;
        $css = $this->controller->{$this->modelFinder}->getCustomCss($theme,$style);
        header('Content-type: text/css');
        echo $css!=null ? $css : '/**/';//nothing
        Yii::app()->end();
    }
}
