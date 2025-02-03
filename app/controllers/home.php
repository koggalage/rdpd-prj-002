<?php

class Home extends Controller
{

    public function index()
    {
        //pagination formula
        $limit = 10;
        $offset = Page::get_offset($limit);

        //check if its a search
        $search = false;
        if (isset($_GET['find'])) {
            $find = addslashes($_GET['find']);
            $search = true;
        }

        if (isset($_GET['search'])) {
            $search = true;
        }

        $User = $this->load_model('User');
        $image_class = $this->load_model('Image');
        $user_data = $User->check_login();

        if (is_object($user_data)) {
            $data['user_data'] = $user_data;
        }

        $DB = Database::newInstance();

        $data['page_title'] = "Home";

        //read main posts
        if ($search) {
            if (isset($_GET['find'])) {
                $arr['description'] = "%" . $find . "%";
                $ROWS = $DB->read("select * from products where description like :description limit $limit offset $offset", $arr);
            } else {
                //Advanced Search
                //generate a search query
                $query = Search::make_query($_GET, $limit, $offset);
                $ROWS = $DB->read($query);
            }
        } else {
            $ROWS = $DB->read("select * from products limit $limit offset $offset");
        }



        if ($ROWS) {
            foreach ($ROWS as $key => $row) {
                $ROWS[$key]->image = $image_class->get_thumb_post($ROWS[$key]->image);
            }
        }

        $data['ROWS'] = $ROWS;

        //carousel posts
        $carousel_pages_count = 3;
        $page_size = 3;


        for ($i = 0; $i < $carousel_pages_count; $i++) {
            $skip = $page_size * $i;
            
            $Slider_ROWS[$i] = $DB->read("SELECT * FROM products ORDER BY id LIMIT 3 OFFSET $skip");

            if ($Slider_ROWS[$i]) {
                foreach ($Slider_ROWS[$i] as $key => $row) {
                    $Slider_ROWS[$i][$key]->image = $image_class->get_thumb_post($Slider_ROWS[$i][$key]->image);
                }

                //$data['Slider_ROWS'][] = $Slider_ROWS[$i];
            }

            $data['Slider_ROWS'][] = $Slider_ROWS[$i];
        }


        //get products for lower segment

        //get all categories
        $category = $this->load_model('Category');
        $data['categories'] = $category->get_all();

        //get all slider content
        $Slider = $this->load_model('Slider');
        $data['slider'] = $Slider->get_all();

        $data['segment_data'] = $this->get_segment_data($DB, $data['categories'], $image_class);


        if ($data['slider']) {
            foreach ($data['slider'] as $key => $row) {
                $data['slider'][$key]->image = $image_class->get_thumb_post($data['slider'][$key]->image, 484, 441);
            }
        }

        $data['show_search'] = true;

        $this->view("index", $data);
    }

    private function get_segment_data($DB, $categories, $image_class)
    {
        $results = array();
        $mycats = array();
        $num = 0;

        foreach ($categories as $cat) {

            $arr['id'] = $cat->id;
            $ROWS = $DB->read("select * from products where category = :id order by rand() limit 5", $arr);

            if (is_array($ROWS)) {
                
                $cat->category = str_replace(" ", "_", $cat->category);
                $cat->category = preg_replace("/\W+/", "", $cat->category); //Anything not a word will be replaced

                //crop images
           
                    foreach ($ROWS as $key => $row) {
                        $ROWS[$key]->image = $image_class->get_thumb_post($ROWS[$key]->image);
                    }

                $results[$cat->category] = $ROWS;

                $num++;
                if ($num > 5) {
                    break;
                }
            }
        }

        return $results;
    }
}