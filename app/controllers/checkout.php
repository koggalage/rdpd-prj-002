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

        rsort($ROWS); //reverse sort
        $data['ROWS'] = $ROWS;
        $this->view("checkout", $data);
    }

}