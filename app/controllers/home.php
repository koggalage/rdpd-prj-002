<?php

Class Home extends Controller {
    public function index()
    {
        $User = $this->load_model('User');
        $user_data = $User->check_login();

        if(is_object($user_data))
        {
            $data['user_data'] = $user_data;
        }

        $DB = Database::newInstance();

        $ROWS = $DB->read("select * from products");
        
        $data['page_title'] = "Home";
        $data['ROWS'] = $ROWS;
        $this->view("index", $data);
    }
}