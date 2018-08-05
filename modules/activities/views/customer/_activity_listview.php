<?php
    $this->renderView('recent',array(
            'data'=>$data,
            'trimLength'=>isset($trimLength)?$trimLength:null)
    );
