<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('shop.models.RegistrationForm');
Yii::import('common.modules.accounts.models.AccountTypeTrait');
/**
 * Description of RegisterCustomerForm (after first order has been placed)
 *
 * @author kwlok
 */
class RegisterCustomerForm extends RegistrationForm
{
    use AccountTypeTrait;
    
    public $order_no;
    /**
     * Constructor.
     */
    public function __construct($shop_id)
    {
        parent::__construct($shop_id,'create');
    }         
}
