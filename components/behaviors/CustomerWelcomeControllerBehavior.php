<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of CustomerWelcomeControllerBehavior
 *
 * @author kwlok
 */
class CustomerWelcomeControllerBehavior extends WelcomeControllerBehavior
{
    /**
     * @see WelcomeControllerBehavior::initBehavior()
     */
    public function initBehavior() 
    {
        if (user()->isGuest)
            throwError403(Sii::t('sii','Unauthorized Access'));
        
        //-----------------
        // SPageIndex Configuration - cont'd
        // @see SPageIndexController
        if (!user()->isActivated && user()->account->pendingSignup()){//user is registered only - normally come from social network
            $this->getOwner()->customWidgetView = true;
            $this->getOwner()->pageControl = null;//not set
            $this->getOwner()->loadPreSignupMessages();
        }            
        else {
            $this->getOwner()->defaultScope = 'orders';//to display default scope page at welcome page
        }
        $this->getOwner()->controlCallback = $this->getControlCallback();
        $this->getOwner()->modelType = $this->getOwner()->module->welcomeModel;//to display recent orders at welcome page
    }
    /**
     * @see WelcomeControllerBehavior::loadScopeFilters()
     * @return array
     */
    public function loadScopeFilters()
    {
        $filters = new CMap();
        $filters->add('orders',Helper::htmlIndexFilter(Sii::t('sii','Orders'), false));
        $filters->add('items',Helper::htmlIndexFilter(Sii::t('sii','Items'), false));
        $filters->add('dashboard',Helper::htmlIndexFilter(Sii::t('sii','Dashboard'), false));
        $filters->add('tasks',Helper::htmlIndexFilter(Sii::t('sii','Tasks'), false));
        return $filters->toArray();
    }    
    /**
     * @see WelcomeControllerBehavior::loadWidgetView()
     * @return array
     */
    public function loadWidgetView($view,$scope,$searchModel=null)
    {
        switch ($scope) {
            case 'orders':
                $this->getOwner()->modelView = $this->getOwner()->module->getView('orders.customerorderlist');
                return $this->getOwner()->renderPartial('_orders',[],true);
            case 'items':
                $this->getOwner()->modelType = 'Item';
                $this->getOwner()->modelFilter = 'mine';
                $this->getOwner()->modelView = $this->getOwner()->module->getView('items.customeritemlist');
                return $this->getOwner()->renderPartial('_items',[],true);
            case 'tasks':
                $this->getOwner()->tasksView = 'tasklist';
                return $this->getOwner()->renderPartial('_tasks',['role'=>Role::CUSTOMER],true);
            case 'dashboard':
                return $this->getOwner()->module->runControllerMethod('analytics/management','getWidgetView');//all the 3 arguments for signature are null and safe to pass in
            case 'activate':
                return $this->getOwner()->renderPartial('activate',[],true);
            default:
                throwError404(Sii::t('sii','The requested page does not exist'));
        }
    }     
    
    public function getRecentNews()
    {
        $finder= News::model()->recently();
        if (user()->onShopScope()){
            $finder = $finder->locateShop(user()->getShop());
        }
        else
            $finder= News::model()->like();
        
        return new CActiveDataProvider($finder, [
            'criteria'=>[],
            'pagination'=>array('pageSize'=>Config::getSystemSetting('news_per_page')),
        ]);        
    }   

    public function getRecentItems()
    {
        $finder= Item::model()->{$this->getOwner()->modelFilter}()->all();
        if (user()->onShopScope()){
            $finder = $finder->locateShop(user()->getShop());
        }
        return new CActiveDataProvider($finder, [
              'criteria'=>['order'=>'create_time DESC'],
              'pagination'=>['pageSize'=>Config::getSystemSetting('record_per_page')],
              'sort'=>false,
        ]);
    }    
    
    public function getRecentOrders()
    {
        $type = $this->getOwner()->modelType;
        $finder= $type::model()->mine()->all();
        if (user()->onShopScope()){
            $finder = $finder->locateShop(user()->getShop());
        }
        
        return new CActiveDataProvider($finder, [
              'criteria'=>['order'=>'create_time DESC'],
              'pagination'=>['pageSize'=>Config::getSystemSetting('record_per_page')],
              'sort'=>false,
         ]);
    }    
    
    public function getOrderExtendedSumary()
    {
        return '<span class="extendedSummary"> | '.CHtml::link(Sii::t('sii','Show All'), url('orders')).'</span>';
    }
    /**
     * OVERRIDDEN
     * @see WelcomeControllerBehavior::showAskQuestion()
     */
    public function showAskQuestion()
    {
        return true;
    }   
    /**
     * OVERRIDDEN
     * @see WelcomeControllerBehavior::showRecentNews()
     */
    public function showRecentNews()
    {
        return true;
    }       
    /**
     * A callback when view page control is changed
     * @return type
     */
    public function getControlCallback()
    {
        return [
            'dashboard'=> CHtml::encode('function(){refreshdashboard(\'/account/welcome/dashboard\');}'),
        ];
    }
    /**
     * For action Dashboard use
     * @return type
     */
    public function getChartWidgetData() 
    {
        $widget = new CMap();
        foreach (ChartFactory::getCustomerCharts() as $data) {
            $widget->add($data['id'],ChartFactory::getChartWidgetInitData([
                'id'=>$data['id'],
                'type'=>$data['type'],
                'filter'=>$data['filter'],
                'shop'=>null,
                'currency'=>null,
                'selection'=>null,
            ]));
        }
        return $widget->toArray();
    }    
    
    public function getQuestionAskUrl()
    {
        if (user()->onShopScope()){
            Yii::import('common.modules.shops.components.ShopPage');
            return ShopPage::getPageUrl(user()->shopModel, ShopPage::QUESTION);
        }
        else
            return Question::model()->askUrl;
    }      
}
