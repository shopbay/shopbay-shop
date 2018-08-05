<?php
logHttpHeader();
$basepath = dirname(__FILE__).DIRECTORY_SEPARATOR.'..';
$appName = basename(dirname(dirname(__FILE__)));// The app directory name, e.g. shopbay-app
$webapp = new SWebApp($appName,$basepath);
//$webapp->enableSystemTrace = true;
$webapp->import([
    'application.models.*',
    'application.components.*',
]);
$webapp->setCommonComponent('authManager',['class'=> 'ShopAuthManager']);
$webapp->setCommonComponent('ctrlManager',['class'=> 'ShopControllerManager']);
$webapp->addComponents([
    'themeManager'=>[
        'basePath'=>$webapp->params['SHOP_THEME_BASEPATH'],
    ],
    'user'=> [
        'class'=>'ShopUser',
        //enable cookie-based authentication
        'allowAutoLogin'=>true,
        'loginUrl'=>['/login'],
    ],
    'request' => [
        'class' => 'common.components.SHttpRequest',
        'enableCsrfValidation' => true,
        'enableCookieValidation'=>true,
        'csrfTokenName'=>$webapp->params['CSRF_TOKEN_NAME'],
        'csrfSkippedRoutes'=> [
            'shops/storefront/fbshop',
            'site/locale',
        ],
    ],     
    'session' => [
        'class' => 'SHttpSession',
    ],    
    'urlManager'=> [
        'class'=>'ShopUrlManager',
        'hostDomain'=>$webapp->params['HOST_DOMAIN'],
        'merchantDomain'=>$webapp->params['MERCHANT_DOMAIN'],
        //'cdnDomain'=>'',//if not set, follow host domain
        'secureRoutes'=>[],
        'forceSecure'=>false,
        'excludeSecureRoutes'=>['cart/management/add'],
        'urlFormat'=>'path',
        'showScriptName'=>false,
        'rules'=> [
            'login'=>'auth/login',
            'login/form'=>'auth/loginform',
            'login/*'=>'auth/login/*',
            'shops/login/<action:\w+>/*'=>'auth/guest/*',
            'account/activate/*'=>'activation/index/*',
            'account/authenticate/logout'=>'auth/logout',
            'account/authenticate/<action:\w+>'=>'accounts/authenticate/<action>',
            'account/authenticate/<action:\w+>/*'=>'accounts/authenticate/<action>/*',
            'account/profile'=>'profile/index',
            'account/profile/<action:\w+>'=>'profile/<action>',
            'account/profile/<action:\w+>/*'=>'profile/<action>/*',
            'account/password'=>'profile/password',
            'account/forgotpassword'=>'profile/forgotpassword',
            'account/email'=>'profile/email',
            'account/notifications'=>'profile/notifications',
            'account/welcome/dashboard'=>'accounts/welcome/dashboard',
            'account/welcome/*'=>'accounts/welcome/index/*',
            'account/signup/customer/*'=>'register/customer/*',
            'signup/customer/*'=>'register/customer',
            'management/captcha/*'=>'accounts/management/captcha',
            'register'=>'register/index',
            'register/<action:\w+>/*'=>'register/<action>/*',
            'likes'=>'likes/management/index',
            'likes/<controller>/<action:\w+>'=>'likes/<controller>/<action>',
            'likes/<controller>/<action:\w+>/*'=>'likes/<controller>/<action>',
            'comments'=>'comments/management/index',
            'comments/<controller>/<action:\w+>'=>'comments/<controller>/<action>',
            'comments/<controller>/<action:\w+>/*'=>'comments/<controller>/<action>',
            'sitemap.xml'=>'site/index',
            'orders'=>'orders/customer/index',
            'orders/*'=>'orders/customer/index/*',
            'orders/<controller>/<action:\w+>/*'=>'orders/<controller>/<action>/*',
            'items'=>'items/customer/index',
            'items/*'=>'items/customer/index/*',
            'items/<controller>/<action:\w+>/*'=>'items/<controller>/<action>/*',
            'questions'=>'questions/customer/index',
            'questions/*'=>'questions/customer/index/*',
            'payments'=>'payments/customer/index',
            'activities'=>'activities/customer/index',
            'activities/*'=>'activities/customer/index/*',
            'analytics/<controller>/<action:\w+>/*'=>'analytics/<controller>/<action>/*',
            'messages'=>'messages/management/index',
            'messages/<controller>/<action:\w+>/*'=>'messages/<controller>/<action>/*',
            'tasks'=>'tasks/customer/index',
            'tasks/<controller>'=>'tasks/<controller>',
            'tasks/<controller>/<action:\w+>'=>'tasks/<controller>/<action>',
            'tasks/<controller>/<action:\w+>/*'=>'tasks/<controller>/<action>',
            //'news'=>'news/customer/index',//disable, this is in conflict with shop news page url
            //default to site/index to handle all other url patterns
            '<controller:[a-zA-Z0-9-]+>/*'=>'site/index',
        ],
    ],
    'googleAnalytics' => [
        'class' =>'common.extensions.googleAnalytics.GoogleAnalytics',
        'enable'=> $webapp->parseBoolean('GOOGLE_ANALYTICS'),
        'gtmAccount' => $webapp->params['GTM_ACCOUNT'],
    ],
    'filter'=> [
        'class'=>'SFilter',
        'rules'=>['flash'],
    ],
]);
return $webapp->toArray();