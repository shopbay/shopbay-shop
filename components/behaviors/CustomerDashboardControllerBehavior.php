<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.analytics.components.DashboardControllerBehavior');
Yii::import('common.modules.analytics.components.ChartFactory');
Yii::import('common.modules.analytics.charts.CustomerGrowthContainerChart');
/**
 * Description of CustomerDashboardControllerBehavior
 *
 * @author kwlok
 */
class CustomerDashboardControllerBehavior extends DashboardControllerBehavior
{
    /**
     * @see DashboardControllerBehavior::initBehavior()
     */
    public function initBehavior() 
    {
        if (user()->isGuest)
            throwError403(Sii::t('sii','Unauthorized Access'));
    }
    /**
     * @see DashboardControllerBehavior::loadScopeFilters()
     * @return array
     */
    public function loadScopeFilters()
    {
        $filters = new CMap();
        if (user()->isAuthorized){
            foreach (ChartFactory::getCustomerCharts() as $chart) {
                $filters->add($chart['id'],Helper::htmlIndexFilter(ChartFactory::getChartName($chart['id']), false));
            }
        }
        return $filters->toArray();
    }    
    /**
     * @see DashboardControllerBehavior::loadWidgetView()
     * @return array
     */
    public function loadWidgetView($view,$scope,$searchModel=null)
    {
        $view = '';
        foreach (ChartFactory::getCustomerCharts($scope) as $chart) {
            $view .= $this->renderChartWidget($chart);
        }
        return $view;
    }

    public function loadCharts() 
    {
        return ChartFactory::getCustomerCharts($this->getOwner()->getScope());
    }

    public function loadDefaultScope() 
    {
        return CustomerGrowthContainerChart::ID;
    }
    
    public function renderPageIndex($content) 
    {
        $this->getOwner()->spageindexWidget($content);
    }
    
}
