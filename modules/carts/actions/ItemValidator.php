<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

/**
 * Description of ItemValidator
 *
 * @author kwlok
 */
class ItemValidator 
{
    public static function isInteger ($int) 
    {
        return (is_numeric($int) === TRUE && (int)$int  == $int);
    }

    public static function validateItemQuantity($value) 
    {
        $form = new CartItemForm('quantity');
        $form->quantity = $value;
        $form->validate();
        return $form->getError('quantity');//empty array means no error
       
    }

    public static function validateItemShippingSurcharge($value) 
    {
        $item = new CartItemForm('shippingSurcharge');
        $item->shipping_surcharge = $value;
        $item->validate();
        return $item->getError('shipping_surcharge');//empty array means no error
    }

}
