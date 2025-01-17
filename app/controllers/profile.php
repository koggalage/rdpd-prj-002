<?php

Class Profile extends Controller {
    public function index()
    {
        $User = $this->load_model('User');
        $Order = $this->load_model('Order');

        $user_data = $User->check_login(true);

        if(is_object($user_data))
        {
            $data['user_data'] = $user_data;
        }
        
        $orders = $Order->get_orders_by_user($user_data->url_address);

        if (is_array($orders)) {
            foreach ($orders as $key => $row) {
                $details = $Order->get_order_details($row->id);

                $totals = array_column($details, "total");
                $grand_total = array_sum($totals);

                $orders[$key]->details = $details;
                $orders[$key]->grand_total = $grand_total;
            }
        }

        $data['page_title'] = "Profile";
        $data['orders'] = $orders;

        $this->view("profile", $data);
    }
}