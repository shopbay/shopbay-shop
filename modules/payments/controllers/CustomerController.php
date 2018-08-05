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
        // check if module requisites exists
        $missingModules = $this->getModule()->findMissingModules();
        if ($missingModules->getCount()>0)
            user()->setFlash($this->getId(),[
                    'message'=>Helper::htmlList($missingModules),
                    'type'=>'notice',
                    'title'=>Sii::t('sii','Missing Module'),
                ]);  
        //-----------------
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->modelType = 'Payment';
        $this->route = 'orders/payment';
        $this->viewName = Sii::t('sii','Payments');
        $this->enableViewOptions = true;
        $this->enableSearch = false;
        //-----------------//
    }
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array_merge(parent::actions(),[
            'view'=>[
                'class'=>'common.components.actions.ReadAction',
                'model'=>$this->modelType,
                'finderMethod'=>'paymentNo',
            ],                    
        ]);
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
}