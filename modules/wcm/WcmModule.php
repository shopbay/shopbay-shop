<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

/**
 * Description of WcmModule
 *
 * @author kwlok
 */
class WcmModule extends SModule 
{
    /**
     * @property string the default controller.
     */
    public $entryController = 'SiteController';
    /**
     * Page source; Either db or file, default to "db"
     * 
     * db: content are stored in table s_wcm_page
     * file: markdown files located at folder wcm/content
     * 
     * @property string the page source.
     */
    public $pageSource = 'db';
    /**
     * Behaviors for this module
     */
    public function behaviors()
    {
        return [
            'assetloader' => [
                'class'=>'common.components.behaviors.AssetLoaderBehavior',
                'name'=>'wcm',
                'pathAlias'=>'wcm.assets',
            ],
        ];
    }

    public function init()
    {
        // import the module-level models and components
        $this->setImport([
            'wcm.models.*',
            'wcm.controllers.*',
            'common.modules.wcm.models.WcmContentTrait',
            'common.widgets.spagetab.SPageTab',
        ]);
        
        // import module dependencies classes
        $this->setDependencies([
            'modules'=>[],
        ]);             

        $this->defaultController = $this->entryController;

        //load layout and common css/js files
        $this->registerScripts();
        
    }

}