<?php

Class Checkout extends Controller {
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
        $ROWS = false;

        $prod_ids = array();

        if (isset($_SESSION['CART'])) {
            $prod_ids = array_column($_SESSION['CART'], 'id');
            $ids_str = "'" . implode("','", $prod_ids) . "'";

            $ROWS = $DB->read("select * from products where id in ($ids_str)");
        }

        if (is_array($ROWS)) {
            foreach($ROWS as $key => $row) {
                foreach($_SESSION['CART'] as $item) {
                    if ($row->id == $item['id']) {
                        $ROWS[$key]->cart_qty = $item['qty'];
                        break;
                    }
    
                }
            }
        }
        
        $data['page_title'] = "Checkout";

        $data['sub_total'] = 0;

        if($ROWS) {
            foreach  ($ROWS as $key => $row) {
                $ROWS[$key]->image = $image_class->get_thumb_post($ROWS[$key]->image);
                $mytotal = $row->price * $row->cart_qty;

                $data['sub_total'] += $mytotal;
            }
        }

        if (is_array($ROWS))
        {
            rsort($ROWS); //reverse sort
        }
        
        $data['ROWS'] = $ROWS;

        //get countries
        $countries = $this->load_model('Countries');
        $data['countries'] = $countries->get_countries();

        if(count($_POST) > 0) {
            $sessionid = session_id();
            $user_url = "";

            if (isset($_SESSION['user_url'])) {
                $user_url = $_SESSION['user_url'];
            }

            $order = $this->load_model('Order');
            $order->save_order($_POST, $ROWS, $user_url, $sessionid);

            $data['errors'] = $order->errors;

            //header("Location:" . ROOT . "thank_you");
            //die;
        }

        //clear $_SESSION
        // foreach ($_SESSION as $key => $value) {
        //     unset($_SESSION[$key]);
        // }

        $this->view("checkout", $data);
    }

}