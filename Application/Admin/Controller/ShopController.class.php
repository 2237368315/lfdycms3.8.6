<?php
namespace Admin\Controller;

class ShopController extends AdminController {

    public function index(){
        $this->meta_title = '商品管理';
        $this->display();
    }

    public function add(){
        $this->meta_title = '商品添加';
        $this->display('edit');
    }
}