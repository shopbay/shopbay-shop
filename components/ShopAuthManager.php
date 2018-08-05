<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of ShopAuthManager
 *
 * @author kwlok
 */
class ShopAuthManager extends SAuthManager
{
    /**
     * Performs access check for the specified user.
     * @param string $itemName the name of the operation that need access check
     * @param mixed $userId the user ID. This should can be either an integer and a string representing
     * the unique identifier of a user. See {@link IWebUser::getId}.
     * @param array $params name-value pairs that would be passed to biz rules associated
     * with the tasks and roles assigned to the user.
     * Since version 1.1.11 a param with name 'userId' is added to this array, which holds the value of <code>$userId</code>.
     * @return boolean whether the operations can be performed by the user.
     */
    public function checkAccess($itemName,$userId,$params=[])
    {
        if ($this->checkGuestAccess($itemName, $userId))
            return true;
        else 
            return parent::checkAccess($itemName, $userId, $params);
    }
        
    public function checkGuestAccess($itemName,$userId)
    {
        if (in_array($itemName, $this->exemptedTasks)||
            ($userId==Account::GUEST && $itemName==Task::MY_OBJECT)){
            logInfo(__METHOD__." $itemName for user $userId exempted.");
            return true;
        }
        else 
            return false;
    }
}
