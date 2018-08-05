<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of QuestionsModule
 *
 * @author kwlok
 */
class QuestionsModule extends SModule 
{
    /**
     * @property string the default controller.
     */
    public $entryController = 'undefined';
    /**
     * @property string the task url when asking a question.
     */
    public $taskAskUrl = '<controller>/<action>';
    /**
     * @property string the task url when asking a question.
     */
    public $taskAnswerUrl = '<controller>/<action>';
    /**
     * Behaviors for this module
     */
    public function behaviors()
    {
        return [
            'assetloader' => [
                'class'=>'common.components.behaviors.AssetLoaderBehavior',
                'name'=>'questions',
                'pathAlias'=>'questions.assets',
            ],
        ];
    }

    public function init()
    {
        // import the module-level models and components
        $this->setImport([
            'common.widgets.spageindex.controllers.SPageIndexController',
            'common.modules.shops.controllers.ShopParentController',
        ]);

        // import module dependencies classes
        $this->setDependencies([
            'modules'=>[
                'tasks'=>[
                    'common.modules.tasks.actions.TransitionAction',
                    'common.modules.tasks.models.*',
                ],
                'tags'=>[
                    'common.modules.tags.models.Tag',
                ],
            ],
            'classes'=>[
                'listview'=>'common.widgets.SListView',
                'gridview'=>'common.widgets.SGridView',
            ],
            'views'=>[
                'question'=>'application.modules.questions.views.customer.ask',
                'questionquickview'=>'application.modules.questions.views.customer._quickview',
                'questionform'=>'application.modules.questions.views.customer._ask_form',
                'questionquickform'=>'application.modules.questions.views.customer._quickform',
                //common views
                'questiontemplate'=>'common.modules.questions.views.share._question_template',
                //account views
                'profilesidebar'=>'accounts.profilesidebar',
            ],
            'images'=>[
                //'<imageKey>'=>['<pathAlias>'=>'<imageFile>'],
            ],
        ]);          
        
        $this->defaultController = $this->entryController;

        $this->registerScripts();

    }
    /**
     * @return ServiceManager
     */
    public function getServiceManager($owner=null)
    {
        $this->setComponents([
            'servicemanager'=>[
                'class'=>'common.services.QuestionManager',
                'model'=>['Question'],
                'ownerAttribute'=>isset($owner)?$owner:'question_by',
            ],
        ]);
        return $this->getComponent('servicemanager');
    }
    
}