<?php 
return [
    /**
     * configuraion for local information 
     */
    'SITE_NAME' => readConfig('app','name'),
    'SITE_LOGO' => false, //indicate if to use a brand image as site logo; if false, SITE_NAME will be used
    /**
     * configuration for domain
     */
    'HOST_DOMAIN' => readConfig('domain','host'),    
    'API_DOMAIN' => readConfig('domain','api'),  
    'MERCHANT_DOMAIN' => readConfig('domain','merchant'),  
    /**
     * configuration for help wizard
     */
    'WIZARD_APP_ID' => 'customer',
    /**
     * configuration for shop resources
     */
    'SHOP_THEME_BASEPATH' => readConfig('system','shopThemePath'),
    'SHOP_WIDGET_BASEPATH' => readConfig('system','shopWidgetPath'),
    /**
     * configuration for OAUTH - hybridoauth login
     */
    'OAUTH' => false,
    /**
     * configuration to enable Google Analytics
     */
    'GTM_ACCOUNT' => readConfig('googleanalytics','gtmAccount'),
    'GOOGLE_ANALYTICS' => readConfig('googleanalytics','enable'),
];
