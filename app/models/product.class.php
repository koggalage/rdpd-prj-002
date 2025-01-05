<?php

class Product
{

    function create($DATA)
    {
        $DB = Database::newInstance();

        $arr['description'] = ucwords($DATA->data);

        if (!preg_match("/^[a-zA-Z]+$/", trim($arr['description']))) {
            $_SESSION['error'] = "Please enter a valid description name";
        }

        if (!isset($_SESSION['error']) || $_SESSION['error'] == "") {

            $query = "insert into products (description) values (:description)";
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
        $arr['id'] = (int)$id;
        $arr['description'] = $description;
        $query = "update products set description = :description where id = :id limit 1";
        $DB->write($query, $arr);
    }

    function delete($id)
    {
        $DB = Database::newInstance();
        $id = (int)$id;
        $query = "delete from products where id = '$id' limit 1";
        $DB->write($query);
    }

    function get_all()
    {
        $DB = Database::newInstance();
        return $DB->read("select * from products order by id desc");
    }

    function make_table($cats)
    {
        $result = "";
        if (is_array($cats)) {

            foreach ($cats as $cat_row) {

                $edit_args = $cat_row->id.",'".$cat_row->description."'";

                $result .= "<tr>";

                $result .= '
                        <td><a href="basic_table.html#">' . $cat_row->description . '</a></td>
                        <td></td>
                        <td>
                            <button onclick="show_edit_description('.$edit_args.',event)" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
                            <button onclick="delete_row('.$cat_row->id.')" class="btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>
                        </td>
                    ';
                $result .= "</tr>";
            }
        }

        return $result;
    }

}