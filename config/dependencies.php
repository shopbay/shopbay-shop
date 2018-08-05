<?php
//Set aliases and import module dependencies
$root = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..';

$depends = [
    'base'=>[
    //----------------------
    // Alias mapping
    //----------------------
        'common' => 'shopbay-kernel', //actual folder name
        'api' => 'shopbay-api',
        'shop'=>'shopbay-shop',
    ],
    //---------------------------
    // Common modules / resources
    //---------------------------
    'module'=> [
        'common' => [
            'import'=> [
                'components.*',
                'controllers.*',
                'models.*',
                'extensions.*',
                'services.WorkflowManager',
                'widgets.SWidget',
                'widgets.spagelayout.SPageLayout',
                'widgets.susermenu.SUserMenu',
                'widgets.SStateDropdown',
                'widgets.sloader.SLoader',
                'widgets.stooltip.SToolTip',
                'widgets.SButtonColumn',
                'widgets.soffcanvasmenu.SOffCanvasMenu',
                'modules.carts.models.CartItemForm',
                'modules.activities.models.Activity',
                'modules.activities.behaviors.*',
                'modules.shops.components.*',
                'modules.shops.models.*',
                'modules.shops.behaviors.*',
                'modules.themes.models.*',
                'modules.payments.models.*',
                'modules.questions.models.*',
                'modules.questions.behaviors.*',
            ],
        ],
        'rights' => [
            'import'=> [
                'components.*',
            ],
        ],
        'accounts' => [
            'import'=> [
                'components.*',
                'users.Role',
                'users.Task',
                'users.WebUser',
            ],
            'config'=> [
                'apiLoginRoute'=>'oauth2/customer/login',
                'apiActivateRoute'=>'oauth2/customer/activate',
                'welcomeModel'=>'Order',
                'welcomeView'=>'index',
                'welcomeControllerBehavior'=>'application.components.behaviors.CustomerWelcomeControllerBehavior',
                'redirectSubdomainAfterLoginRoute'=>true, 
                'redirectShopAfterLoginRoute'=>true,
                'useReturnUrl'=>true, 
                'afterLoginRoute'=>'/welcome', 
                'afterLogoutRoute'=>'/', 
            ],
        ],        
        'images' => [
            'import'=> [
                'components.*',
                'components.Img',
            ],
            'config'=> [
                 'createOnDemand'=>true, // requires apache mod_rewrite enabled
            ],
        ],
        'tasks'=> [
            'import'=>[
                'models.*',
                'behaviors.WorkflowBehavior',
            ],
            'config'=>[
                'entryController'=>'customer',
                'runAsBuyer'=>true,
            ],
        ],
        'messages'=> [
            'import'=>[
                'models.Message',
            ],
        ],
        'likes' => [
            'import'=> [
                'models.LikeForm',
                'models.Like',
                'behaviors.LikableBehavior',
            ],
        ],
        'comments' => [
            'import'=> [
                'models.CommentForm',
                'models.Comment',
                'behaviors.CommentableBehavior',
            ],
        ],
        'notifications'=> [
            'config'=> [
                'entryController'=>'subscription',
            ],            
        ],
        'analytics' => [
            'import'=> [
                'models.*',
                'components.ChartFactory',
            ],
            'config'=> [
                'dashboardControllerBehavior'=>'application.components.behaviors.CustomerDashboardControllerBehavior',
            ],
        ],
        'search' => [
            'import'=> [
                'behaviors.SearchableBehavior',
            ],
        ],
        'tickets' => [
            'import'=> [
                'models.Ticket',
            ],
        ],
        'tutorials' => [
            'import'=> [
                'models.Tutorial',
                'models.TutorialSeries',
            ],
        ],
        'help'=> [
            'config'=> [
                'entryController'=>'customer',
            ],            
        ],
        'plans' => [
            'import'=>[
                'models.*',
            ],
        ],   
        'billings' => [
            'import'=>[
                'models.*',
            ],
        ],          
        'media' => [
            'import'=>[
                'behaviors.MultipleMediaBehavior',
                'models.Media',
                'models.MediaAssociation',
            ],
        ],
        'customers'=> [
            'import'=>[
                'models.CustomerAccount',
                'models.Customer',
            ],
        ],
        'pages'=> [
            'import'=>[
                'models.Page',
            ],
        ],
        //plain modules contains components/behaviors/models without controllers/views
        'themes'=> [],        
        'brands' => [],
        'campaigns' => [],
        'products' => [],
        'shippings' => [],
        'taxes' => [],
        'inventories' => [
            'import'=>[
                'models.LowInventoryDataProvider',
            ],
        ],
    ],
    //----------------------
    // Local modules
    // Format: local module name
    //----------------------
    'local'=>[
        'activities'=> [
            'config'=> [
                'entryController'=>'customer',
            ],
        ],
        'shops' => [
            'config'=> [
                'entryController'=>'storefront',
            ],
        ],
        'carts' => [],
        'orders' => [
            'config'=> [
                'entryController'=>'customer',
            ],
        ],
        'items' => [
            'config'=> [
                'entryController'=>'customer',
            ],
        ],
        'payments'=> [
            'config'=> [
                'entryController'=>'customer',
            ],
        ],
        'news'=> [
            'config'=> [
                'entryController'=>'customer',
            ],
        ],
        'questions'=> [
            'config'=> [
                'entryController'=>'customer',
                'taskAskUrl'=>'/tasks/question/ask', 
                'taskAnswerUrl'=>'/tasks/question/answer', 
            ],
        ],
        'wcm' => [],//this is for simple web content management module for top page
    ],
];

// The app directory path, e.g. /path/to/shopbay-app
$appPath = dirname(dirname(__FILE__));

loadDependencies(ROOT,$depends, $appPath);

return $depends;

