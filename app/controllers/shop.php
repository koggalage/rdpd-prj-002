<?php

Class Shop extends Controller {
    public function index()
    {
        $User = $this->load_model('User');
        $image_class = $this->load_model('Image');
        $user_data = $User->check_login();

        if(is_object($user_data))
        {
            $data['user_data'] = $user_data;
        }

        $DB = Database::newInstance();

        $ROWS = $DB->read("select * from products");
        
        $data['page_title'] = "Shop";

        if($ROWS) {
            foreach  ($ROWS as $key => $row) {
                $ROWS[$key]->image = $image_class->get_thumb_post($ROWS[$key]->image);
            }
        }
        $data['ROWS'] = $ROWS;
        $this->view("shop", $data);
    }
}