<?php

class Admin extends Controller
{

    public function index()
    {
        $User = $this->load_model('User');
        $user_data = $User->check_login(true, ["admin"]);

        if (is_object($user_data)) {
            $data['user_data'] = $user_data;
        }

        $data['current_page'] = "dashboard";
        $data['page_title'] = "Admin";
        $this->view("admin/index", $data);
    }

    public function categories(): void
    {
        //pagination formula
        $limit = 10;
        $offset = Page::get_offset($limit);

        $User = $this->load_model('User');
        $user_data = $User->check_login(true, ["admin"]);

        if (is_object($user_data)) {
            $data['user_data'] = $user_data;
        }

        $DB = Database::newInstance();
        $categories_all = $DB->read("select * from categories order by id desc limit $limit offset $offset");
        $categories = $DB->read("select * from categories where disabled = 0  order by id desc");


        $category = $this->load_model("Category");
        $tbl_rows = $category->make_table($categories_all);
        $data['tbl_rows'] = $tbl_rows;
        $data['categories'] = $categories;
        $data['current_page'] = "categories";
        $data['page_title'] = "Admin - Categories";
        $this->view("admin/categories", $data);
    }

    public function products(): void
    {
        $search = false;
        if (isset($_GET['search'])) {
            //show($_GET);
            $search = true;
        }

        //pagination formula
        $limit = 10;
        $offset = Page::get_offset($limit);

        $User = $this->load_model('User');
        $user_data = $User->check_login(true, ["admin"]);

        if (is_object($user_data)) {
            $data['user_data'] = $user_data;
        }

        $DB = Database::newInstance();

        if ($search) {
            //generate a search query
            $query = Search::make_query($_GET, $limit, $offset);
            $products = $DB->read($query);
        } else {
            $products = $DB->read("SELECT prod.*,
                                            brands.brand as brand_name,
                                            cat.category as category_name
                                      FROM products as prod
                                      join brands on brands.id = prod.brand 
                                      join categories as cat on cat.id = prod.category 
                                      order by prod.id desc 
                                      limit $limit 
                                      offset $offset");
        }


        $categories = $DB->read("select * from categories where disabled = 0  order by views desc");
        $brands = $DB->read("select * from brands where disabled = 0  order by views desc");

        $product = $this->load_model("Product");
        $category = $this->load_model("Category");

        $tbl_rows = $product->make_table($products, $category);

        $data['tbl_rows'] = $tbl_rows;
        $data['categories'] = $categories;
        $data['brands'] = $brands;

        $data['current_page'] = "products";
        $data['page_title'] = "Admin - Products";
        $this->view("admin/products", $data);
    }

    public function orders(): void
    {
        $User = $this->load_model('User');
        $Order = $this->load_model('Order');

        $user_data = $User->check_login(true, ["admin"]);

        if (is_object($user_data)) {
            $data['user_data'] = $user_data;
        }

        $orders = $Order->get_all_orders();

        if (is_array($orders)) {
            foreach ($orders as $key => $row) {
                $details = $Order->get_order_details($row->id);
                $orders[$key]->grand_total = 0;

                if (is_array($details)) {
                    $totals = array_column($details, "total");
                    $grand_total = array_sum($totals);
                    $orders[$key]->grand_total = $grand_total;
                }

                $orders[$key]->details = $details;

                $user = $User->get_user($row->user_url);
                $orders[$key]->user = $user;
            }
        }

        $data['orders'] = $orders;
        $data['current_page'] = "orders";
        $data['page_title'] = "Admin - Orders";
        $this->view("admin/orders", $data);
    }

    public function users($type = "customers"): void
    {
        $User = $this->load_model('User');
        $Order = $this->load_model('Order');


        $user_data = $User->check_login(true, ["admin"]);

        if (is_object($user_data)) {
            $data['user_data'] = $user_data;
        }

        $type = addslashes($type);

        if ($type == "admins") {
            $users = $User->get_admins();
        } else {
            $users = $User->get_customers();
        }

        if (is_array($users)) {
            foreach ($users as $key => $row) {
                $orders_num = $Order->get_orders_count($row->url_address);
                $users[$key]->orders_count = $orders_num;
            }
        }

        $data['users'] = $users;
        $data['current_page'] = "users";
        $data['page_title'] = "Admin - $type";
        $this->view("admin/users", $data);
    }

    function settings($type = '')
    {
        $User = $this->load_model('User');
        $Settings = new Settings();

        $user_data = $User->check_login(true, ["admin"]);

        if (is_object($user_data)) {
            $data['user_data'] = $user_data;
        }

        //select the right page
        if ($type == "socials") {
            if (count($_POST) > 0) {
                $Settings->save_settings($_POST);
                header("Location: " . ROOT . "admin/settings/socials");
                die;
            }

            $data['settings'] = $Settings->get_all_settings();
        } else if ($type == "slider_images") {


            $data['action'] = "show";

            $Slider = $this->load_model('Slider');


            //read all slider images
            $data['rows'] = $Slider->get_all();

            if (isset($_GET['action']) && $_GET['action'] == "add") {

                $data['action'] = "add";

                //if new row was posted
                if (count($_POST) > 0) {

                    $Image = $this->load_model('Image');

                    $data['errors'] = $Slider->create($_POST, $_FILES, $Image);

                    $data['POST'] = $_POST;
                    header("Location: " . ROOT . "admin/settings/slider_images");
                    die;
                }



            } else if (isset($_GET['action']) && $_GET['action'] == "edit") {
                $data['action'] = "edit";
                $data['id'] = null;

                if (isset($_GET['id'])) {
                    $data['id'] = $_GET['id'];
                }

            } else if (isset($_GET['action']) && $_GET['action'] == "delete") {
                $data['action'] = "add";
            } else if (isset($_GET['action']) && $_GET['action'] == "delete_confirmed") {
                $data['action'] = "add";
            }
        }

        $data['type'] = $type;
        $data['current_page'] = "settings";
        $data['page_title'] = "Admin - $type";
        $this->view("admin/settings", $data);
    }

    public function messages(): void
    {
        $User = $this->load_model('User');
        $Message = $this->load_model('Message');

        $user_data = $User->check_login(true, ["admin"]);

        if (is_object($user_data)) {
            $data['user_data'] = $user_data;
        }

        $mode = "read";

        if (isset($_GET['delete'])) {
            $mode = "delete";
        }

        if (isset($_GET['delete_confirmed'])) {
            $mode = "delete_confirmed";
            $id = $_GET['delete_confirmed'];
            $messages = $Message->delete($id);
        }

        if ($mode == "delete") {
            $id = $_GET['delete'];
            $messages = $Message->get_one($id);
        } else {
            $messages = $Message->get_all();
        }

        $data['mode'] = $mode;
        $data['messages'] = $messages;
        $data['current_page'] = "messages";
        $data['page_title'] = "Admin - Messages";
        $this->view("admin/messages", $data);
    }

    public function blogs(): void
    {
        $User = $this->load_model('User');
        $Post = $this->load_model('Post');
        $Image = $this->load_model('Image');

        $user_data = $User->check_login(true, ["admin"]);

        if (is_object($user_data)) {
            $data['user_data'] = $user_data;
        }

        $mode = "read";

        if (isset($_GET['edit'])) {
            $mode = "edit";
        }

        if (isset($_GET['delete'])) {
            $mode = "delete";
        }

        if (isset($_GET['add_new'])) {
            $mode = "add_new";
        }

        if (isset($_GET['delete_confirmed'])) {
            $mode = "delete_confirmed";
            $id = $_GET['delete_confirmed'];
            $blogs = $Post->delete($id);
        }

        if ($mode == "edit") {
            $id = $_GET['edit'];
            $blogs = $Post->get_one($id);

            $data['POST'] = (array) $blogs;
        } else if ($mode == "delete") {
            $id = $_GET['delete'];
            $blogs = $Post->get_one($id);

            if ($blogs) {

                if (file_exists($blogs->image)) {
                    $blogs->image = $Image->get_thumb_post($blogs->image);
                }

                $blogs->user_data = $User->get_user($blogs->user_url);
            }

            $data['POST'] = (array) $blogs;
        } else {
            $blogs = $Post->get_all();

            if ($blogs) {

                foreach ($blogs as $key => $row) {
                    if (file_exists($blogs[$key]->image)) {
                        $blogs[$key]->image = $Image->get_thumb_post($blogs[$key]->image);
                    }

                    $blogs[$key]->user_data = $User->get_user($blogs[$key]->user_url);
                }
            }
        }

        //if something was posted
        if (count($_POST) > 0) {


            if ($mode == "edit") {
                $Post->edit($_POST, $_FILES, $Image);
            } else {
                $Post->create($_POST, $_FILES, $Image);
            }


            if (isset($_SESSION['error']) && $_SESSION['error'] != "") {
                $data['errors'] = $_SESSION['error'];
                $data['POST'] = $_POST;
            } else {
                redirect("admin/blogs");
            }
        }

        $data['mode'] = $mode;
        $data['blogs'] = $blogs;
        $data['current_page'] = "blogs";
        $data['page_title'] = "Admin - Blog Posts";
        $this->view("admin/blogs", $data);
    }

}