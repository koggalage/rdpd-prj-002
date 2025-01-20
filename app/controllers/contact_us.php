<?php

Class Contact_us extends Controller {

    public function index()
    {
        $User = $this->load_model('User');
        $user_data = $User->check_login();

        if(is_object($user_data))
        {
            $data['user_data'] = $user_data;
        }

        $DB = Database::newInstance();

        if (count($_POST) > 0) {
            $data['POST'] = $_POST;
            show($_POST);
        }

        $data['page_title'] = "Contact Us";
        $this->view("contact", $data);
    }
}