<?php
$this->widget('common.widgets.spage.SPage',[
    'id'=>'site_page',
    'heading'=> [
        'name'=> isset($heading) ? $heading : false,
    ],
    'linebreak'=>false,
    'body'=> isset($body) ? $body : '',
]);