<?php

class Product
{

    function create($DATA, $FILES)
    {
        $_SESSION['error'] = "";

        $DB = Database::newInstance();

        $arr['description'] = ucwords($DATA->description);
        $arr['quantity'] = $DATA->quantity;
        $arr['category'] = $DATA->category;
        $arr['price'] = $DATA->price;
        $arr['date'] = date("Y-m-d H:i:s");
        $arr['user_url'] = $_SESSION['user_url'];

        if (!preg_match("/^[a-zA-Z]+$/", trim($arr['description']))) {
            $_SESSION['error'] .= "Please enter a valid description name <br>";
        }

        if (!is_numeric($arr['quantity'])) {
            $_SESSION['error'] .= "Please enter a valid quantity <br>";
        }

        if (!is_numeric($arr['category'])) {
            $_SESSION['error'] .= "Please enter a valid category <br>";
        }

        if (!is_numeric($arr['price'])) {
            $_SESSION['error'] .= "Please enter a valid price <br>";
        }

        $arr['image'] = "";
        $arr['image2'] = "";
        $arr['image3'] = "";
        $arr['image4'] = "";

        $allowed[] = "image/jpeg";
        $size = 10;
        $size = ($size * 1024 * 1024);

        $folder = "uploads/";

        if (!file_exists($folder))
        {
            mkdir($folder, 0777, true);
        }

        //check for files
        foreach ($FILES as $key => $img_row) {
            if ($img_row['error'] == 0 && in_array($img_row['type'], $allowed))
            {
                if($img_row['size'] < $size)
                {
                    $destination = $folder . $img_row['name'];
                    move_uploaded_file($img_row['tmp_name'], $destination);
                    $arr[$key] = $destination;
                }else{
                    $_SESSION['error'] .= $key  . " is bigger than required size <br>";
                }
            }
        }

        

        if (!isset($_SESSION['error']) || $_SESSION['error'] == "") {

            $query = "insert into products (description, quantity, category, price, date, user_url, image, image2, image3, image4) values (:description,:quantity,:category,:price,:date,:user_url,:image,:image2,:image3,:image4)";
            
            //show($arr);
            $check = $DB->write($query, $arr);

            if ($check) {
                return true;
            }
        }

        return false;
    }

    function edit($id, $description)
    {
        $DB = Database::newInstance();
        $arr['id'] = (int) $id;
        $arr['description'] = $description;
        $query = "update products set description = :description where id = :id limit 1";
        $DB->write($query, $arr);
    }

    function delete($id)
    {
        $DB = Database::newInstance();
        $id = (int) $id;
        $query = "delete from products where id = '$id' limit 1";
        $DB->write($query);
    }

    function get_all()
    {
        $DB = Database::newInstance();
        return $DB->read("select * from products order by id desc");
    }

    function make_table($prods, $model = null)
    {
        $result = "";
        if (is_array($prods)) {

            foreach ($prods as $prod_row) {

                $edit_args = $prod_row->id . ",'" . $prod_row->description . "'";

                $one_cat = $model->get_one($prod_row->category);

                $result .= "<tr>";

                $result .= '
                        <td><a href="basic_table.html#">' . $prod_row->id . '</a></td>
                        <td><a href="basic_table.html#">' . $prod_row->description . '</a></td>
                        <td><a href="basic_table.html#">' . $prod_row->quantity . '</a></td>
                        <td><a href="basic_table.html#">' . $one_cat->category . '</a></td>
                        <td><a href="basic_table.html#">' . $prod_row->price . '</a></td>
                        <td><a href="basic_table.html#">' . date("jS M Y H:i:s", strtotime($prod_row->date)) . '</a></td>
                        <td><a href="basic_table.html#"><img src="'. ROOT . $prod_row->image.'" style="width: 70px; height: 70px;" /></a></td>
                        <td></td>
                        <td>
                            <button onclick="show_edit_product(' . $edit_args . ',event)" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
                            <button onclick="delete_row(' . $prod_row->id . ')" class="btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>
                        </td>
                    ';
                $result .= "</tr>";
            }
        }

        return $result;
    }

}