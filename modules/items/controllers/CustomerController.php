<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.orders.components.GuestOrderControllerTrait');
/**
 * Description of CustomerController
 *
 * @author kwlok
 */
class CustomerController extends SPageIndexController 
{
    use ItemControllerTrait, GuestOrderControllerTrait;
    
    public function init()
    {
        parent::init();
        //-----------------
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->modelType = 'Item';
        $this->viewName = Sii::t('sii','Items');
        $this->route = 'items/customer/index';
        $this->pageControl = SPageIndex::CONTROL_ARROW;
        $this->searchMap = [
            'order_no' => 'order_no',
            'shipping_no' => 'shipping_order_no',
            'date' => 'create_time',
            'item' => 'name',
            'unit_price' => 'unit_price',
            'total_price' => 'total_price',
        ];        
        //-----------------//  
        // SPageFilter Configuration
        // @see SPageFilterControllerTrait
        $this->filterFormModelClass = 'ItemFilterForm';
        $this->filterFormHomeUrl = url('items/customer');
        $this->filterFormQuickMenu = [
            array('id'=>'items','title'=>Sii::t('sii','View Items'),'subscript'=>Sii::t('sii','items'), 'url'=>url('items'),'linkOptions'=>['class'=>'active']),
            array('id'=>'po','title'=>Sii::t('sii','View Orders'),'subscript'=>Sii::t('sii','orders'), 'url'=>url('orders')),
        ];
        //-----------------
        // Exclude following actions from rights filter 
        //-----------------
        $this->rightsFilterActionsExclude = [
            'imageget',
            'track',
        ];
        //-----------------//   
    }
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array_merge(parent::actions(),[
            'view'=>[
                'class'=>'common.components.actions.LanguageReadAction',
                'model'=>$this->modelType,
                'loadModelMethod'=>'prepareModel',
                'pageTitleAttribute'=>'name',
            ],
            'imageget'=>[
                'class'=>'common.components.actions.ImageGetAction',
            ],
            'track'=>[
                'class'=>'common.components.actions.LanguageReadAction',
                'model'=>$this->modelType,
                'modelFilter'=>'guest',
                'pageTitleAttribute'=>'name',
                'loadModelMethod'=>'prepareGuestModel',
                'viewFile'=>'track',
            ],            
        ]);
    }    
    
    public function prepareModel()
    {
        $orderNo = current(array_keys($_GET));//take the first key as order no
        if (!empty($_GET[$orderNo])){//has item id
            $model = Item::model()->mine()->byOrderNo($orderNo, Helper::urlstrdetr($_GET[$orderNo]))->find();            
            if($model===null)
                throwError404(Sii::t('sii','The requested page does not exist'));  
            return $model;
        }
        else
            throwError404(Sii::t('sii','The requested page does not exist'));    
    }
    
    public function prepareGuestModel()
    {
        $key = current(array_keys($_GET));//take the first key as order no
        $item = explode(Helper::PIPE_SEPARATOR, base64_decode($key));//@see Item::getAccessUrl()
        logTrace(__METHOD__,$item);
        if (isset($item[0])&&isset($item[1])){
            $model = Item::model()->guest()->byOrderNo($item[1], $item[0])->find();            
            if($model!=null)
                return $model;
        }
        throwError404(Sii::t('sii','The requested page does not exist'));  
    }
    /**
     * OVERRIDE METHOD
     * @see SPageIndexController
     * @return CDbCriteria
     */
    public function getSearchCriteria($model)
    {
        $criteria = new CDbCriteria;
        $criteria->compare('order_no',$model->order_no,true);
        $criteria->compare('shipping_order_no',$model->shipping_order_no,true);
        $criteria->compare('unit_price',$model->unit_price,true);
        $criteria->compare('total_price',$model->total_price,true);
        $criteria = QueryHelper::parseLocaleNameSearch($criteria, 'name', $model->name);
        $criteria = QueryHelper::prepareDatetimeCriteria($criteria, 'create_time', $model->create_time);
        //$criteria->compare('status', QueryHelper::parseStatusSearch($model->status));//already supported as scope filter in spageindex
        
        return $criteria;
    }
    
    public function getSectionsData($model) 
    {
        Yii::import('common.modules.tasks.views.workflow.sections.ItemSections');
        $sections = new ItemSections($this,$model);
        $sections->addProcessHistory();
        return $sections->getData(false);//false = custom sections            
    }    
    /**
     * @inheritdoc
     */
    public function getDataProvider($scope,$searchModel=null)
    {
        $type = $this->modelType;
        $type::model()->resetScope();
        $finder = $type::model()->{$this->modelFilter}()->{$scope}();
        
        if (user()->onShopScope()){
            $finder = $finder->locateShop(user()->getShop());
        }
        
        if ($searchModel!=null)
            $finder->getDbCriteria()->mergeWith($searchModel->getDbCriteria());
        logTrace(__METHOD__.' criteria',$finder->getDbCriteria());
        return new CActiveDataProvider($finder, [
            'criteria'=>['order'=>$this->sortAttribute.' DESC'],
            'pagination'=>['pageSize'=>Config::getSystemSetting('record_per_page')],
            'sort'=>false,
        ]);
    }      
}
