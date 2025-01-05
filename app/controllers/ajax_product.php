<?php

class Ajax_product extends Controller
{
    public function index()
    {
        //if form data
        //print_r($_POST);

        $data = file_get_contents("php://input");
        $data = json_decode($data);

        if (is_object($data) && isset($data->data_type)) {

            $DB = Database::getInstance();
            $product = $this->load_model('Product');

            if ($data->data_type == 'add_product') {

                $check = $product->create($data);

                if ($_SESSION['error'] != "") {

                    $arr['message'] = $_SESSION['error'];
                    $_SESSION['error'] = "";
                    $arr['message_type'] = "error";
                    $arr['data'] = "";
                    $arr['data_type'] = "add_new";

                    echo json_encode($arr);

                } else {

                    $arr['message'] = "Product added successfully!";
                    $arr['message_type'] = "info";
                    $prods = $product->get_all();
                    $arr['data'] = $product->make_table($prods);
                    $arr['data_type'] = "add_new";

                    echo json_encode($arr);

                }
            } else if ($data->data_type == 'disable_row') {

                $disabled = ($data->current_state == "Enabled") ? 1 : 0;
                $id = $data->id;

                $query = "update products set disabled = '$disabled' where id = '$id' limit 1";
                $DB->write($query);

                $arr['message'] = "";
                $_SESSION['error'] = "";
                $arr['message_type'] = "info";

                $prods = $product->get_all();
                $arr['data'] = $product->make_table($prods);

                $arr['data_type'] = "disable_row";

                echo json_encode($arr);

            } else if ($data->data_type == 'edit_product') {

                $product->edit($data->id, $data->product);
                $arr['message'] = "Your row was successfully edited";
                $_SESSION['error'] = "";
                $arr['message_type'] = "info";
                
                $prods = $product->get_all();
                $arr['data'] = $product->make_table($prods);

                $arr['data_type'] = "edit_product";

                echo json_encode($arr);
            }
             else if ($data->data_type == 'delete_row') {

                $product->delete($data->id);
                $arr['message'] = "Your row was successfully deleted";
                $_SESSION['error'] = "";
                $arr['message_type'] = "info";
                
                $prods = $product->get_all();
                $arr['data'] = $product->make_table($prods);

                $arr['data_type'] = "delete_row";

                echo json_encode($arr);

            }
        }
    }

}