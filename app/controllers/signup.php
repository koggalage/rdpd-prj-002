<?php

Class Signup extends Controller {
    public function index()
    {
        //echo "This is the home class inside index method";

        $data['page_title'] = "Signup";

        if($_SERVER['REQUEST_METHOD'] == "POST")
        {
            //show($_POST);

            $User = $this->load_model("User");
            $User->signup($_POST);
        }


        $this->view("signup", $data);
    }
}