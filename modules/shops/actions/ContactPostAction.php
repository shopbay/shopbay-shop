<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of ContactPostAction
 * Process contact form submission
 * 
 * @author kwlok
 */
class ContactPostAction extends CAction 
{
    public $modelClass = 'Shop';
    protected $model;
    /**
     * Run action
     */
    public function run() 
    {
        if (request()->getIsPostRequest() && isset($_POST['ContactForm'])){
            $this->prepareModel();
            $formClass = $this->modelClass.'ContactForm';
            $form = new $formClass($this->model,'POST');
            $form->attributes = $_POST['ContactForm'];
            logTrace(__METHOD__.' Received form data',$form->attributes);
            if ($form->validate()){
                //when form validation pass, send out email!
                $form = Yii::app()->serviceManager->execute(
                        $form,
                        [ServiceManager::NOTIFICATION=>ServiceManager::EMPTY_PARAMS],
                        ServiceManager::NO_VALIDATION);//set scenario for notificaion sending
                logTrace(__METHOD__.' Contact us message sent out ok');
                $form->setScenario('POST');//set back scenario after sending, required to show success message
                unset($_POST);
            }
            header('Content-type: application/json');
            echo CJSON::encode([
                'form'=>$form->renderPartial($this->controller),
            ]);                        
            Yii::app()->end();      
        }
        throw new CHttpException(403,Sii::t('sii','Unauthorized Access'));    
    }

    protected function prepareModel() 
    {
        $this->model = $this->controller->{'getCurrent'.$this->modelClass}();//e.g. getCurrentShop()
        $this->controller->setThemeLayout($this->model->getTheme());
        return $this->model;
    }
}