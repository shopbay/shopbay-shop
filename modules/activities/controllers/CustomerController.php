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
        $this->modelType = 'Activity';
        $this->pageControl = SPageIndex::CONTROL_ARROW;
        $this->viewName = Sii::t('sii','Activities');
        $this->enableViewOptions = false;
        $this->enableSearch = false;
        //-----------------//        
        $this->defaultScope = 'operational';
        $this->route = 'activities/customer/index';
    }
    /**
     * OVERRIDE METHOD
     * @see SPageIndexController
     * @return array
     */
    public function getScopeFilters()
    {
        $filters = new CMap();
        $filters->add('operational',Helper::htmlIndexFilter(Sii::t('sii','All'), false));
        $filters->add('like',Helper::htmlIndexFilter(Sii::t('sii','Like'), false));
        $filters->add('comment',Helper::htmlIndexFilter(Sii::t('sii','Comment'), false));
        $filters->add('question',Helper::htmlIndexFilter(Sii::t('sii','Question'), false));
        return $filters->toArray();
    }
    /**
     * OVERRIDE METHOD
     * @see SPageIndexController
     * @return array
     */
    public function getScopeDescription($scope)
    {
        switch ($scope) {
            case 'operational':
                return Sii::t('sii','This lists all your activity records.');
            case 'like':
                return Sii::t('sii','This lists all your activity records related to likes.');
            case 'comment':
                return Sii::t('sii','This lists all your activity records related to comments.');
            case 'question':
                return Sii::t('sii','This lists all your activity records related to questions.');
            default:
                return null;
        }
    }        
}
