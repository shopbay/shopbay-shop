<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

/**
 * Description of CustomerController
 *
 * @author kwlok
 */
class CustomerController extends SPageIndexController 
{
    public function init()
    {
        parent::init();
        //-----------------
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->modelType = 'News';
        $this->viewName = Sii::t('sii','News');
        $this->route = 'news/customer/index';
        //$this->modelFilter = 'like';//not in use; see getFinder()
        $this->enableViewOptions = false;
        $this->sortAttribute = 'update_time';
        //-----------------//
    }
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array_merge(parent::actions(),[
            'view'=> [
                'class'=>'common.components.actions.LanguageReadAction',
                'model'=>$this->modelType,
                'modelFilter'=>null,
                'loadModelMethod'=>'prepareModel',
                'accountAttribute'=>'subscriber',//will be switched to shop news page when user is on shop scope, see getNewsViewUrl()
                'pageTitleAttribute'=>'headline',
            ], 
        ]);
    }    
    
    public function prepareModel()
    {
        $search = current(array_keys($_GET));//take the first key as search attribute
        $finder = $this->getFinder()->retrieve($search);
        $model = $finder->find();
        if($model===null){
            throw new CHttpException(404,Sii::t('sii','Page not found'));
        }
        return $model;
    }
    /**
     * OVERRIDE METHOD
     * @see SPageIndexController
     * @return array
     */
    public function getScopeFilters()
    {
        $filters = new CMap();
        $filters->add('all',Helper::htmlIndexFilter('All', false));
        return $filters->toArray();
    }     
    /**
     * @inheritdoc
     */
    public function getDataProvider($scope,$searchModel=null)
    {
        $finder = $this->getFinder()->{$scope}();
        
        if ($searchModel!=null)
            $finder->getDbCriteria()->mergeWith($searchModel->getDbCriteria());

        logTrace(__METHOD__.' criteria',$finder->getDbCriteria());

        return new CActiveDataProvider($finder, [
            'criteria'=>['order'=>$this->sortAttribute.' DESC'],
            'pagination'=>['pageSize'=>Config::getSystemSetting('record_per_page')],
            'sort'=>false,
        ]);
    }      
    
    protected function getFinder()
    {
        $type = $this->modelType;
        $type::model()->resetScope();
        
        if (user()->onShopScope()){
            $finder = $type::model()->locateShop(user()->getShop());//select all news for the shop
        }
        else {
            $finder = $type::model()->like();//select all user likes
        }
        
        return $finder;
    }
    
    public function getNewsViewUrl($model)
    {
        if (user()->onShopScope()){
            Yii::import('common.modules.shops.components.ShopPage');
            return ShopPage::getPageUrl(user()->shopModel, ShopPage::NEWS).'?article'.$model->id;
        }
        else {
            return $model->viewUrl;
        }
        
    }
}