<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of ActivitiesModule
 *
 * @author kwlok
 */
class ActivitiesModule extends SModule 
{
    /**
     * @property string the default controller.
     */
    public $entryController = 'undefined';
    /**
     * Behaviors for this module
     */
    public function behaviors()
    {
        return [
            'assetloader' => [
                'class'=>'common.components.behaviors.AssetLoaderBehavior',
                'name'=>'activities',
                'pathAlias'=>'activities.assets',
            ],
        ];
    }
    
    public function init()
    {
        // import the module-level models and components
        $this->setImport([
            'activities.models.*',
            'activities.components.*',
            'common.modules.activities.models.Activity',
            'common.widgets.spageindex.controllers.SPageIndexController',
        ]);
        // import module dependencies classes
        $this->setDependencies([
            'modules'=>[
                'questions'=>[
                    'common.modules.questions.models.Question',
                ],             
                'payments'=>[
                    'common.modules.payments.models.PaymentMethod',
                ],             
            ],
            'classes'=>[
                'listview'=>'common.widgets.SListView',
                'gridview'=>'common.widgets.SGridView',
            ],
            'views'=>[        
                'recent'=>'common.modules.activities.views.base._activity',
                'profilesidebar'=>'accounts.profilesidebar',
            ],
        ]);  

        $this->defaultController = $this->entryController;

        $this->registerScripts();

    }

}
