<?php
namespace Admin\Controller;

class PayController extends AdminController {
    
    public function log(){
        $this->meta_title = '充值记录';
        $this->display();
    }
}