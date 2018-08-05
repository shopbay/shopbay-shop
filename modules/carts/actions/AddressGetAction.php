<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of AddressGetAction
 *
 * @author kwlok
 */
class AddressGetAction extends CAction 
{
    /**
     * Retrieve account profile address
     */
    public function run() 
    {
         header('Content-type: application/json');

        if (userOnScope('shop')){
            logInfo(__METHOD__.' shop scope');
            $customer = Customer::model()->mine()->find();
            if ($customer==null || $customer->address==null)
                $this->addressNotFound();

            $address = $customer->getAddressData();
            $mobile = $address->mobile;
            $recipient = $customer->alias_name;
        }
        else {
            $address = AccountAddress::model()->mine()->find();
            if ($address===null)
                $this->addressNotFound();
            $profile = AccountProfile::model()->mine()->find();
            $mobile = $profile->mobile?$profile->mobile:'';
            $recipient = $profile->alias_name?$profile->alias_name:'';
        }

        echo CJSON::encode([
            'status'=>'success',
            'recipient'=>$recipient,
            'mobile'=>$mobile,
            'address1'=>$address->address1,
            'address2'=>$address->address2,
            'postcode'=>$address->postcode,
            'city'=>$address->city,
            'state'=>$address->state,
            'country'=>$address->country,
        ]);
    }  
    
    protected function addressNotFound()
    {
        echo CJSON::encode([
            'status'=>'failure',
            'message'=> Sii::t('sii','You have not setup your profile address yet'),
        ]); 
        Yii::app()->end();
    }
}