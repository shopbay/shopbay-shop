<?php
if ($page==ShopPage::HTML && isset($content)){
    /**
     * Render html page
     */
    $this->renderHtmlPage(user()->shopModel,$content,isset($cssClass)?$cssClass:'html-page');
}
else {
    $this->setCurrentPage($page);
    $pageObj = $this->loadPageObject(user()->shopModel,$page);
    /**
     * Insert form if any
     */
    if (isset($formModel))
        $pageObj->setFormModel($formModel);
    if (isset($formView))
        $pageObj->setFormViewFile($formView);
    
    $this->renderShopPage($pageObj);
}

