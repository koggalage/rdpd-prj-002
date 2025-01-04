<?php

Class Logout extends Controller {
    public function index()
    {
        //echo "This is the home class inside index method";

        $User = $this->load_model('User');
        $User->logout();
    }
}