<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of OrderControllerTrait
 *
 * @author kwlok
 */
trait OrderControllerTrait 
{
    /**
     * OVERRIDE METHOD
     * @see SPageIndexController
     * @return array
     */
    public function getScopeFilters()
    {
        $filters = new CMap();
        $filters->add('all',Helper::htmlIndexFilter(array('code'=>'all','text'=>Sii::t('sii','All')),$this->pageControl==SPageIndex::CONTROL_ARROW,true));
        //unpaid here is a grouping
        $filters->add('unpaid',Helper::htmlIndexFilter(array('code'=>'unpaid','text'=>Sii::t('sii','Unpaid')),$this->pageControl==SPageIndex::CONTROL_ARROW,true));
        $excludes = array_merge(array(Process::DEFERRED));//deferred status is grouped into "unpaid" - refer to workflowable
        $keys =  array_values(WorkflowManager::getAllEndProcesses(SActiveRecord::restoreTablename($this->modelType),$excludes));
        foreach ($keys as $key) {
            //logTrace(__METHOD__.' '.Helper::phpSafe($key).' , ');
            $filters->add(Helper::phpSafe($key),Helper::htmlIndexFilter(array('code'=>$key,'text'=>Process::getDisplayText(ucwords($key))),$this->pageControl==SPageIndex::CONTROL_ARROW,true));
        }
        return $filters->toArray();
    }    
    /**
     * OVERRIDE METHOD
     * Return the array of scope description for purchase order for customer
     * 
     * @see SPageIndexAction
     * @return array
     */
    public function getScopeDescription($scope)
    {
        switch ($scope) {
            case 'all':
                return Sii::t('sii','This lists all the orders that you have purchased.');
            case 'unpaid':
                return Sii::t('sii','Unpaid orders are orders that require you to make payment to confirm.');
            case 'paid':
                return Sii::t('sii','Paid orders are orders that pending merchant payment verification before shipping.');
            case 'ordered':
                return Sii::t('sii','These are orders with confirmed by merchant and they should have started shipping your purchased items.');
            case 'rejected':
                return Sii::t('sii','These are orders that rejected by merchant due to unsuccessful payment verification. You can check the reason of rejection.');
            case 'cancelled':
                return Sii::t('sii','These are orders that either cancelled by merchant or yourselves. You can check the reason of cancellation.');
            case 'fulfilled':
                return Sii::t('sii','These are orders that have been fully fulfilled and all their purchased items are shipped.');
            case 'partial_fulfilled':
                return Sii::t('sii','These are orders that have been partially fulfilled and there are pending purchased items to be shipped or in other status.');
            case 'refunded':
                return Sii::t('sii','Refunded orders are orders that merchant had cancelled and refunded you. If you have not received the refund, please contact merchant.');
            default:
                return null;
        }
    }      
    
}

