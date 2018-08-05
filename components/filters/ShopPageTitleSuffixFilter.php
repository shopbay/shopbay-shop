<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.components.filters.PageTitleSuffixFilter');
/**
 * Description of ShopPageTitleSuffixFilter
 *
 * @author kwlok
 */
class ShopPageTitleSuffixFilter extends PageTitleSuffixFilter
{
    /**
     * Performs the pre-action filtering.
     * @param CFilterChain $filterChain the filter chain that the filter is on.
     * @return boolean whether the filtering process should continue and the action
     * should be executed.
     */
    protected function preFilter($filterChain)
    {
        if (user()->onShopScope() && $this->hideOnShopScope)
            $filterChain->controller->pageTitleSuffix = null;
        elseif (user()->onShopScope() && $this->useShopName){
            if (isset(user()->shopModel))
                $filterChain->controller->pageTitleSuffix = user()->shopModel->localeName(user()->getLocale());
        }
        else {
            $filterChain->controller->pageTitleSuffix = Yii::app()->name;
        }
        return true;
    }
    /**
     * Logic being applied after the action is executed
     * @param type $filterChain
     */
    protected function postFilter($filterChain)
    {
        //put logic here
    }    
}
