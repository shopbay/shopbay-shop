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
        user()->setFlash($this->getId(),array('message'=>Helper::htmlList($missingModules),
                                        'type'=>'notice',
                                        'title'=>Sii::t('sii','Missing Module')));    
        //-----------------
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->modelType = 'Question';
        $this->route = 'questions/customer/index';
        $this->viewName = Sii::t('sii','Questions');
        $this->enableViewOptions = false;
        $this->enableSearch = false;
        $this->sortAttribute = 'question_time';       
        //$this->pageControl = SPageIndex::CONTROL_ARROW;
        //-----------------//
    }
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array_merge(parent::actions(),array(
            'view'=>array(
                'class'=>'common.components.actions.ReadAction',
                'model'=>$this->modelType,
                'pageTitleAttribute'=>'title',
                'accountAttribute'=>'question_by'
            ),                    
            'ask'=>array(
                'class'=>'common.components.actions.CreateAction',
                'model'=>$this->modelType,
                'createModelMethod'=>'prepareModel',
                'setAttributesMethod'=>'setModelAttributes',
                'service'=>'ask',
                'viewFile'=>'ask',
            ),
            'update'=>array(
                'class'=>'common.components.actions.UpdateAction',
                'model'=>$this->modelType,
                'loadModelMethod'=>'prepareModel',
                'setAttributesMethod'=>'setModelAttributes',
                'service'=>'update',
            ), 
            'delete'=>array(
                'class'=>'common.components.actions.DeleteAction',
                'model'=>$this->modelType,
            ),
            'activate'=>array(
                'class'=>'TransitionAction',
                'nameAttribute'=>'question',
                'modelType'=>$this->modelType,
                'validate'=>array('scenario'=>'activate','field'=>'type'),
                'flashTitle'=>Sii::t('sii','{object} Activation',array('{object}'=>Question::model()->displayName())),
                'flashMessage'=>Sii::t('sii','Question is activated successfully.'),
            ),
            'deactivate'=>array(
                'class'=>'TransitionAction',
                'nameAttribute'=>'question',
                'modelType'=>$this->modelType,
                'flashTitle'=>Sii::t('sii','{object} Deactivation',array('{object}'=>Question::model()->displayName())),
                'flashMessage'=>Sii::t('sii','Question is deactivated successfully.'),
            ),
        ));
    }  
    /**
     * Prepare model for update action
     * @param type $id
     * @return \modelType
     */
    public function prepareModel($id=null)
    {
        if (isset($id)){//update action
            $model = $this->loadModel($id);
            $model->question = $model->htmlbr2nl($model->question);
            $model->tags = explode(',', $model->tags);//convert back to array
        }
        else {
            $model = new $this->modelType;
            $model->setScenario('ask');
        }
        return $model;        
    }
    /**
     * Set model attributes for write/edit action
     * @param type $model
     * @return type
     */
    public function setModelAttributes($model)
    {
        if(isset($_POST[$this->modelType])) {
            $model->attributes = $_POST[$this->modelType];
            if (!isset($_POST[$this->modelType]['tags']))
                $model->tags = '';//no tag specified
                
            if (is_array($model->tags)){
                $model->tags = implode(',', $model->tags);
            }
            if (!isset($model->obj_type))
                $model->obj_type = Question::model()->tableName();
            return $model;
        }
        throwError400(Sii::t('sii','Bad Request'));
    } 
    /**
     * Return sectino data
     * @param type $model
     * @return type
     */
    public function getSectionsData($model) 
    {
        $sections = new CList();
        //section 1: Process History
        $sections->add(array('id'=>'history','name'=>Sii::t('sii','Process History'),'heading'=>true,
                             'viewFile'=>$this->getModule()->getView('history'),'viewData'=>array('dataProvider'=>$model->searchTransition($model->id))));
        return $sections->toArray();
    }      
    /**
     * Return page menu (with auto active class)
     * @param type $model
     * @return type
     */
    public function getPageMenu($model)
    {
        return array(
            array('id'=>'view','title'=>Sii::t('sii','View {object}',array('{object}'=>$model->displayName())),'subscript'=>Sii::t('sii','view'),  'url'=>$model->viewUrl,'linkOptions'=>array('class'=>$this->action->id=='view'?'active':'')),
            array('id'=>'ask','title'=>Sii::t('sii','Ask {object}',array('{object}'=>$model->displayName())),'subscript'=>Sii::t('sii','ask'), 'url'=>array('ask')),
            array('id'=>'update','title'=>Sii::t('sii','Update {object}',array('{object}'=>$model->displayName())),'subscript'=>Sii::t('sii','update'), 'url'=>array('update', 'id'=>$model->id),'visible'=>$model->updatable(),'linkOptions'=>array('class'=>$this->action->id=='update'?'active':'')),
            array('id'=>'delete','title'=>Sii::t('sii','Delete {object}',array('{object}'=>$model->displayName())),'subscript'=>Sii::t('sii','delete'), 'visible'=>$model->deletable(), 
                    'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),
                                         'onclick'=>'$(\'.page-loader\').show();',
                                         'confirm'=>Sii::t('sii','Are you sure you want to delete this {object}?',array('{object}'=>strtolower($model->displayName()))))),
            array('id'=>'deactivate','title'=>Sii::t('sii','Deactivate {object}',array('{object}'=>$model->displayName())),'subscript'=>Sii::t('sii','deactivate'), 'visible'=>$model->deactivable(), 
                'linkOptions'=>array('submit'=>url('questions/customer/deactivate',array('Question[id]'=>$model->id)),
                                     'onclick'=>'$(\'.page-loader\').show();',
                                     'confirm'=>Sii::t('sii','Are you sure you want to deactivate this {object}?',array('{object}'=>strtolower($model->displayName()))),
            )),
            array('id'=>'activate','title'=>Sii::t('sii','Activate {object}',array('{object}'=>$model->displayName())),'subscript'=>Sii::t('sii','activate'), 'visible'=>$model->activable(), 
                'linkOptions'=>array('submit'=>url('questions/customer/activate',array('Question[id]'=>$model->id)),
                                     'onclick'=>'$(\'.page-loader\').show();',
                                     'confirm'=>Sii::t('sii','Are you sure you want to activate this {object}?',array('{object}'=>strtolower($model->displayName()))),
            )),
        );
    }
    /**
     * OVERRIDE METHOD
     * @see SPageIndexController
     * @return array
     */
    public function getScopeFilters()
    {
        $filters = new CMap();
        $filters->add('all',Helper::htmlIndexFilter(Sii::t('sii','All'), false));
        $filters->add('asked',Helper::htmlIndexFilter(Sii::t('sii','Asked'), false));
        $filters->add('answered',Helper::htmlIndexFilter(Sii::t('sii','Answered'), false));
        return $filters->toArray();
    }
    /**
     * OVERRIDE METHOD
     * Return the array of scope description
     * 
     * @see SPageIndexAction
     * @return array
     */
    public function getScopeDescription($scope)
    {
        switch ($scope) {
            case 'all':
                return Sii::t('sii','This lists every questions that you had asked in the past.');
            case 'asked':
                return Sii::t('sii','This lists every questions that are pending answers.');
            case 'answered':
                return Sii::t('sii','This lists every questions that have answers.');
            default:
                return null;
        }
    }         
 
    protected function getQuestionAskUrl()
    {
        if (userOnScope('shop')){
            Yii::import('common.modules.shops.components.ShopPage');
            return ShopPage::getPageUrl(user()->shopModel, ShopPage::QUESTION);
        }
        else
            return Question::model()->askUrl;
    }
}