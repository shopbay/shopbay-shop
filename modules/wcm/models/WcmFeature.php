<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of WcmFeature
 *
 * @author kwlok
 */
class WcmFeature 
{
    public static function title($page)
    {
        if (static::startsWith($page, 'features_'))//certain scenario page id is startign with 'features_'
           $page = substr($page, strlen('features_'));//remove start with string
        
        switch ($page){
            case 'chatbots':
                return Sii::t('sii','Chatbot');
            case 'themes':
                return Sii::t('sii','Theme Store');
            case 'cart':
                return Sii::t('sii','Shopping Cart');
            case 'products':
                return Sii::t('sii','Products and Inventory');
            case 'payments':
                return Sii::t('sii','Payment Gateway');
            case 'orders':
                return Sii::t('sii','Orders Fulfillment');
            case 'website':
                return Sii::t('sii','Website Builder');
            case 'shops':
                return Sii::t('sii','Shops Management');
            case 'sales':
                return Sii::t('sii','Sales Channels');
            case 'marketing':
                return Sii::t('sii','Marketing Tools');
            case 'analytics':
                return Sii::t('sii','Business Analytics');
            case 'crm':
                return Sii::t('sii','Customers Management');
            case 'hosting':
                return Sii::t('sii','Hosting and Security');
            case 'help':
                return Sii::t('sii','Help Center');
            case 'highlights':
            default:
                return Sii::t('sii','All you need to start ecommerce');
        }
    }
    
    public static function items($page)
    {
        if (static::startsWith($page, 'features_'))//certain scenario page id is startign with 'features_'
           $page = substr($page, strlen('features_'));//remove start with string
        
        switch ($page) {
            case 'chatbots':
                return [
                    CHtml::link(Sii::t('sii','Conversational Commerce'),static::url('chatbots#conversational-commerce')),
                    CHtml::link(Sii::t('sii','Sell where customers already are'),static::url('chatbots#reach-customers')),
                    CHtml::link(Sii::t('sii','Easy and fast setup'),static::url('chatbots#easy-fast-setup')),
                    CHtml::link(Sii::t('sii','Rich Messaging'),static::url('chatbots#rich-messaging')),
                    CHtml::link(Sii::t('sii','Subscription messaging'),static::url('chatbots#subscription-messaging')),
                    CHtml::link(Sii::t('sii','Business always on'),static::url('chatbots#business-always-on')),
                    CHtml::link(Sii::t('sii','Live chat service'),static::url('chatbots#live-chat-service')),
                    CHtml::link(Sii::t('sii','Bot discovery'),static::url('chatbots#bot-discovery')),
                    CHtml::link(Sii::t('sii','Built with AI'),static::url('chatbots#built-with-ai')),
                    CHtml::link(Sii::t('sii','Navigate through commands'),static::url('chatbots#command-line-access')),
                ];
            case 'orders':
                return [
                    CHtml::link(Sii::t('sii','Purchase and Shipping Orders'),static::url('orders#po-so')),
                    CHtml::link(Sii::t('sii','Workflow-based Orders Processing'),static::url('orders#workflow')),
                    CHtml::link(Sii::t('sii','Tiered Shipping Fee Model'),static::url('orders#shippings')),
                    CHtml::link(Sii::t('sii','Tax Support'),static::url('orders#tax')),
                    CHtml::link(Sii::t('sii','Flexible Order Number Formatting'),static::url('orders#ordernum')),
                    CHtml::link(Sii::t('sii','Event Alerts'),static::url('orders#alerts')),
                ];
            case 'products':
                return [
                    CHtml::link(Sii::t('sii','Batch Upload Products'),static::url('products#batch-upload')),
                    CHtml::link(Sii::t('sii','Product Categorization'),static::url('products#category')),
                    CHtml::link(Sii::t('sii','Product Brands'),static::url('products#brands')),
                    CHtml::link(Sii::t('sii','Product Attributes'),static::url('products#attributes')),
                    CHtml::link(Sii::t('sii','Product Shipping Surcharge'),static::url('products#surcharge')),
                    CHtml::link(Sii::t('sii','Product Images Zoomer'),static::url('products#image-zoomer')),
                    CHtml::link(Sii::t('sii','Reviews, Ratings and Questions'),static::url('products#reviews')),
                    CHtml::link(Sii::t('sii','Inventory Management'),static::url('products#inventory')),
                ];
            case 'website':
                return [
                    CHtml::link(Sii::t('sii','Manage Multiple Sites'),static::url('website#multi-sites')),
                    CHtml::link(Sii::t('sii','Website Design'),static::url('website#design')),
                    CHtml::link(Sii::t('sii','Mobile Responsive'),static::url('website#responsive')),
                    CHtml::link(Sii::t('sii','Brand Logo and Favicon'),static::url('website#logo')),
                    CHtml::link(Sii::t('sii','Manage Web Content'),static::url('website#content')),
                    CHtml::link(Sii::t('sii','Custom Domain'),static::url('website#domain')),
                    CHtml::link(Sii::t('sii','Custom Navigation Menu'),static::url('website#navigation')),
                    CHtml::link(Sii::t('sii','Customer Feedback and Questions'),static::url('website#feedback')),
                    CHtml::link(Sii::t('sii','Automatic Sitemap Generation'),static::url('website#sitemap')),
                    CHtml::link(Sii::t('sii','Multi-languages Support'),static::url('website#multi-languages')),
                ];
            case 'shops':
                return [
                    CHtml::link(Sii::t('sii','Manage Multiple Shops'),static::url('shops#multi-shops')),
                    CHtml::link(Sii::t('sii','Design Your Shop'),static::url('shops#design')),
                    CHtml::link(Sii::t('sii','Brand Logo and Favicon'),static::url('shops#logo')),
                    CHtml::link(Sii::t('sii','Manage Shop Pages'),static::url('shops#pages')),
                    CHtml::link(Sii::t('sii','Custom Domain'),static::url('shops#domain')),
                    CHtml::link(Sii::t('sii','Customer Feedback and Questions'),static::url('shops#feedback')),
                    CHtml::link(Sii::t('sii','Custom Navigation Menu'),static::url('shops#navigation')),
                    CHtml::link(Sii::t('sii','Automatic Sitemap Generation'),static::url('shops#sitemap')),
                ];
            case 'cart':
                return [
                    CHtml::link(Sii::t('sii','Smart Shopping Cart'),static::url('cart#shopping')),
                    CHtml::link(Sii::t('sii','Checkout Configuration'),static::url('cart#checkout')),
                ];
            case 'payments':
                return [
                    CHtml::link(Sii::t('sii','Online Payment Method'),static::url('payments#online')),
                    CHtml::link(Sii::t('sii','Offline Payment Method'),static::url('payments#offline')),
                ];
            case 'crm':
                return [
                    CHtml::link(Sii::t('sii','Customer accounts'),static::url('crm#accounts')),
                    CHtml::link(Sii::t('sii','Add leads'),static::url('crm#leads')),
                    CHtml::link(Sii::t('sii','Know customers\' shopping behaviors'),static::url('crm#behaviors')),
                ];
            case 'themes':
                return [
                    CHtml::link(Sii::t('sii','Theme Design Templates'),static::url('themes#templates')),
                    CHtml::link(Sii::t('sii','Theme Page Editor'),static::url('themes#editor')),
                    CHtml::link(Sii::t('sii','CSS Editing'),static::url('themes#css')),
                ];
            case 'sales':
                return [
                    CHtml::link(Sii::t('sii','Multiple Sales Channels'),static::url('sales#multi-sales-channels')),
                    CHtml::link(Sii::t('sii','Mobile Commerce'),static::url('sales#mobile')),
                    CHtml::link(Sii::t('sii','Conversational Commerce'),static::url('sales#conversational-commerce')),
                    CHtml::link(Sii::t('sii','Facebook Store'),static::url('sales#facebook')),
                ];
            case 'marketing':
                return [
                    CHtml::link(Sii::t('sii','Sales Campaigns'),static::url('marketing#sales')),
                    CHtml::link(Sii::t('sii','Powerful Product Campaigns'),static::url('marketing#products')),
                    CHtml::link(Sii::t('sii','Promotional Code'),static::url('marketing#promocode')),
                    CHtml::link(Sii::t('sii','News Blog'),static::url('marketing#news')),
                    CHtml::link(Sii::t('sii','Share on Social Media'),static::url('marketing#social-media')),
                    CHtml::link(Sii::t('sii','SEO and Traffic Growth'),static::url('marketing#seo')),
                ];
            case 'crm':
                return [
                    CHtml::link(Sii::t('sii','Customer Accounts'),static::url('crm#accounts')),
                    CHtml::link(Sii::t('sii','Add Customer Leads'),static::url('crm#leads')),
                    CHtml::link(Sii::t('sii','Know Customers\' Shopping Behaviors'),static::url('crm#behaviors')),
                ];
            case 'analytics':
                return [
                    CHtml::link(Sii::t('sii','Know Business Performance'),static::url('analytics#dashboard')),
                ];
            case 'hosting':
                return [
                    CHtml::link(Sii::t('sii','Cloud hosting, No Installation'),static::url('hosting#cloud')),
                    CHtml::link(Sii::t('sii','Regular Updates'),static::url('hosting#updates')),
                    CHtml::link(Sii::t('sii','Regular Backups'),static::url('hosting#backups')),
                ];
            case 'help':
                return [
                    CHtml::link(Sii::t('sii','Online Support'),static::url('help#online')),
                    CHtml::link(Sii::t('sii','Community Support'),static::url('help#community')),
                ];
            case 'highlights':
            default:
                return [
                    CHtml::link(Sii::t('sii','Fast setup responsive brand website'),static::url('website')),
                    CHtml::link(Sii::t('sii','In-built shopping cart'),static::url('cart')),
                    CHtml::link(Sii::t('sii','Pick and choose beautiful themes'),static::url('themes')),
                    CHtml::link(Sii::t('sii','Manage products and inventory'),static::url('products')),
                    CHtml::link(Sii::t('sii','Multiple sales channels ready'),static::url('sales')),
                    CHtml::link(Sii::t('sii','Run promotions and sales offers'),static::url('marketing')),
                    CHtml::link(Sii::t('sii','Support online and offline payments'),static::url('payments')),
                    CHtml::link(Sii::t('sii','Fulfill orders on any device'),static::url('orders')),
                    CHtml::link(Sii::t('sii','Monitor sales and growth'),static::url('analytics')),
                    CHtml::link(Sii::t('sii','Track customer shopping behaviors'),static::url('crm')),
                    CHtml::link(Sii::t('sii','Mobile Commerce'),static::url('sales#mobile')),
                    CHtml::link(Sii::t('sii','Conversational Commerce'),static::url('chatbots')),
                ];
        }
    }
    
    public static function url($page)
    {
        return app()->urlManager->createHostUrl('/features/'.$page);
    }
    
    public static function menu()
    {
        return [
            ['label'=>static::title('website'),'url'=> static::url('website')],
            ['label'=>static::title('cart'),'url'=> static::url('cart')],
            ['label'=>static::title('products'),'url'=> static::url('products')],
            ['label'=>static::title('payments'),'url'=> static::url('payments')],
            ['label'=>static::title('orders'),'url'=> static::url('orders')],
            ['label'=>static::title('sales'),'url'=> static::url('sales')],
            ['label'=>static::title('marketing'),'url'=> static::url('marketing')],
            ['label'=>static::title('crm'),'url'=> static::url('crm')],
            ['label'=>static::title('analytics'),'url'=> static::url('analytics')],
            ['label'=>static::title('themes'),'url'=> static::url('themes')],
            ['label'=>static::title('chatbots'),'url'=> static::url('chatbots')],
            ['label'=>static::title('hosting'),'url'=> static::url('hosting')],
            ['label'=>static::title('help'),'url'=> static::url('help')],
        ];
    }

    private static function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

}
