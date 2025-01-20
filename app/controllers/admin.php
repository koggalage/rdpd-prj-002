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
        $User = $this->load_model('User');
        $user_data = $User->check_login(true, ["admin"]);

        if (is_object($user_data)) {
            $data['user_data'] = $user_data;
        }

        $DB = Database::newInstance();
        $categories_all = $DB->read("select * from categories order by id desc");
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
        $User = $this->load_model('User');
        $user_data = $User->check_login(true, ["admin"]);

        if (is_object($user_data)) {
            $data['user_data'] = $user_data;
        }

        $DB = Database::newInstance();
        $products = $DB->read("select * from products order by id desc");

        $categories = $DB->read("select * from categories where disabled = 0  order by id desc");

        $product = $this->load_model("Product");
        $category = $this->load_model("Category");

        $tbl_rows = $product->make_table($products, $category);

        $data['tbl_rows'] = $tbl_rows;
        $data['categories'] = $categories;

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

}