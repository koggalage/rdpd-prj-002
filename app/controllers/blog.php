<?php

Class Blog extends Controller {

    public function index()
    {
        //check if its a search
        $search = false;
        if (isset($_GET['find'])) {
            $find = addslashes($_GET['find']);
            $search = true;
        }

        $User = $this->load_model('User');
        $image_class = $this->load_model('Image');
        $user_data = $User->check_login();

        if(is_object($user_data))
        {
            $data['user_data'] = $user_data;
        }

        $DB = Database::newInstance();

        if ($search) {
            $arr['description'] = "%" . $find . "%";
            $ROWS = $DB->read("select * from products where description like :description", $arr);
        }else {
            $ROWS = $DB->read("select * from products");
        }
        
        

        if($ROWS) {
            foreach  ($ROWS as $key => $row) {
                $ROWS[$key]->image = $image_class->get_thumb_post($ROWS[$key]->image);
            }
        }

        //get all categories
        $category = $this->load_model('Category');
        $data['categories'] = $category->get_all();

        //get all slider content
        $Slider = $this->load_model('Slider');
        $data['slider'] = $Slider->get_all();

        if($data['slider']) {
            foreach  ($data['slider'] as $key => $row) {
                $data['slider'][$key]->image = $image_class->get_thumb_post($data['slider'][$key]->image, 484, 441);
            }
        }

        $data['ROWS'] = $ROWS;
        $data['show_search'] = true;

        $data['page_title'] = "Blog";
        $this->view("blog", $data);
    }
}