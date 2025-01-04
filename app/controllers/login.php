<?php

Class Login extends Controller {
    public function index()
    {
        //echo "This is the home class inside index method";

        $data['page_title'] = "Login";

        if($_SERVER['REQUEST_METHOD'] == "POST")
        {
            //show($_POST);

            $User = $this->load_model("User");
            $User->login($_POST);
        }
        
        $this->view("login", $data);
    }
}