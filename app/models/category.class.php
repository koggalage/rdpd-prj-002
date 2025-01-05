<?php

Class Category{

    function create($DATA)
    {
        $DB = Database::getInstance();

        $arr['category'] = ucwords($DATA->data);

        if(!preg_match("/^[a-zA-Z]+$/", trim($arr['category'])))
        {
            $_SESSION['error'] = "Please enter a valid category name";
        }

        if(!isset($_SESSION['error']) || $_SESSION['error'] == "")
        {
            
            $query = "insert into categories (category) values (:category)";
            $check = $DB->write($query,$arr);

            if($check) {
                return true;
            }
        }

        return false;
    }

    function edit($DATA)
    {
        
    }

    function delete($DATA)
    {
        
    }

    function get_all()
    {
        $DB = Database::newInstance();
        return $DB->read("select * from categories order by id desc");
    }

    function make_table($cats)
    {
        $result = "";
        if (is_array($cats)) {

            foreach ($cats as $cat_row) {
                 $result .= "<tr>";

                 $result .= '
                        <td><a href="basic_table.html#">' . $cat_row->category . '</a></td>
                        <td><span class="label label-info label-mini">' . $cat_row->disabled . '</span></td>
                        <td>
                            <button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>
                            <button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
                            <button class="btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>
                        </td>
                    ';
                 $result .= "</tr>";
            }
        }

        return  $result;
    }

}