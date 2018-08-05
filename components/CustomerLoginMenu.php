<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.widgets.susermenu.components.UserLoginMenu");
/**
 * Description of CustomerLoginMenu
 *
 * @author kwlok
 */
class CustomerLoginMenu extends UserLoginMenu 
{
    /**
     * Menu constructor
     * @param type $user
     * @param array $config
     */
    public function __construct($user,$config=[]) 
    {
        parent::__construct($user, $config);
        
        $this->items[static::$messages] = $this->getMessageMenu();
        $this->items[static::$dashboard] = new UserMenuItem([
            'id'=> static::$dashboard,
            'label'=>Sii::t('sii','Dashboard'),
            'icon'=>'<i class="fa fa-fw fa-line-chart"></i>',
            'iconPlacement'=>$this->iconPlacement,
            'url'=>url('dashboard'),
            'visible'=>$user->isAuthorizedActivated,
        ]);
        $this->items[static::$orders] = new UserMenuItem([
            'id'=> static::$orders,
            'label'=>Sii::t('sii','My Orders'),
            'icon'=>'<i class="material-icons md-24">content_copy</i>',
            'iconPlacement'=>$this->iconPlacement,
            'url'=>url('orders'),
            'visible'=>$user->isAuthorizedActivated,
        ]);                 
        $this->items[static::$profile] = $this->getProfileMenu();
        $this->items[static::$logout] = $this->getLogoutMenu();
    }
    
    public function getProfileMenuItems() 
    {
        return [
            ['label'=>CHtml::tag('div', ['class'=>$this->isMenuActive('accounts/profile/index')?'active':''], Sii::t('sii','Profile')),'url'=> url('account/profile'),'active'=>$this->isMenuActive(['accounts/profile/index','profile/index','profile/password','profile/email','profile/notifications']),'visible'=>$this->user->isRegistered],
            ['label'=>CHtml::tag('div', ['class'=>$this->isMenuActive('likes/management/index')?'active':''], Sii::t('sii','Likes')), 'url'=>url('likes'),'active'=>$this->isMenuActive('likes/management/index'),'visible'=>$this->user->isAuthorizedActivated],
            ['label'=>CHtml::tag('div', ['class'=>$this->isMenuActive('comments/management/index')?'active':''], Sii::t('sii','Comments')), 'url'=>url('comments'),'active'=>$this->isMenuActive('comments/management/index'),'visible'=>$this->user->isAuthorizedActivated],
            ['label'=>CHtml::tag('div', ['class'=>$this->isMenuActive('questions/customer/index')?'active':''], Sii::t('sii','Questions')),'url'=>url('questions'),'active'=>$this->isMenuActive('questions/customer/index'),'visible'=>$this->user->isAuthorizedActivated],
            ['label'=>CHtml::tag('div', ['class'=>$this->isMenuActive('payments/customer/index')?'active':''], Sii::t('sii','Payments')), 'url'=>url('payments'),'active'=>$this->isMenuActive('payments/customer/index'),'visible'=>$this->user->isAuthorizedActivated],
            ['label'=>CHtml::tag('div', ['class'=>$this->isMenuActive('activities/customer/index')?'active':''], Sii::t('sii','Activities')),'url'=>url('activities'),'active'=>$this->isMenuActive('activities/customer/index'),'visible'=>$this->user->isAuthorizedActivated],
        ];
    }

    public function getAccountMenuItems() 
    {
        //not implementing
    }

}
