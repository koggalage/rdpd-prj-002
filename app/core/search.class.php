<?php

class Search
{
    public static function get_categories($name = '')
    {
        $DB = Database::newInstance();
        $query = "select id, category from categories where disabled = 0 order by views desc";
        $data = $DB->read($query);

        if (is_array($data)) {
            foreach ($data as $row) {
                echo "<option value='$row->id' " . self::get_sticky('select', $name, $row->id) . ">$row->category</option>";
            }
        }
    }

    public static function get_years($name)
    {
        $DB = Database::newInstance();
        $query = "select date from products group by year(date)";
        $data = $DB->read($query);

        if (is_array($data)) {
            foreach ($data as $row) {
                $year = date('Y', strtotime($row->date));
                echo "<option " . self::get_sticky('select', $name, $year) . ">" . $year . "</option>";
            }
        }
    }

    public static function get_brands()
    {
        $DB = Database::newInstance();
        $query = "select id, brand from brands where disabled = 0 order by views desc";
        $data = $DB->read($query);

        if (is_array($data)) {
            $num = 0;
            foreach ($data as $row) {
                echo "<input " . self::get_sticky('checkbox', 'brand-'.$num, $row->id) . " id=\"$row->id\" value=\"$row->id\" type=\"checkbox\" class=\"form-checkbox\" name=\"brand-$num\" > 
                    <label for=\"$row->id\">$row->brand</label> . &nbsp ";

                $num++;
            }
        }
    }

    public static function get_sticky($type, $name, $value = '', $default = null)
    {
        switch ($type) {
            case 'textbox':
                echo isset($_GET[$name]) ? $_GET[$name] : "";
                break;

            case 'number':

                $def = 0;

                if ($default) {
                    $def = $default;
                }
                
                echo isset($_GET[$name]) ? $_GET[$name] : $def;
                break;

            case 'select':
                return (isset($_GET[$name]) && $value == $_GET[$name]) ? "selected='true'" : "";
                //break;

            case 'checkbox':
                return (isset($_GET[$name]) && $value == $_GET[$name]) ? "checked='true'" : "";
                //break;

            default:
                # code...
                break;
        }
    }

    public static function make_query($GET, $limit, $offset)
    {
        $params = array();

            if (isset($GET['description']) && trim($GET['description']) != "") {
                $params['description'] = $GET['description'];
            }

            if (isset($GET['category']) && trim($GET['category']) != "--Select Category--") {
                $params['category'] = $GET['category'];
            }

            if (isset($GET['year']) && trim($GET['year']) != "--Select Year--") {
                $params['year'] = $GET['year'];
            }

            if (isset($GET['min-price']) && trim($GET['min-price']) != "" && trim($GET['max-price']) != "" && trim($GET['max-price']) != "0") {
                $params['min-price'] = (float)$GET['min-price'];
                $params['max-price'] = (float)$GET['max-price'];
            }

            if (isset($GET['min-qty']) && trim($GET['min-qty']) != "" && trim($GET['max-qty']) != "" && trim($GET['max-qty']) != "0") {
                $params['min-qty'] = (int)$GET['min-qty'];
                $params['max-qty'] = (int)$GET['max-qty'];
            }

            $brands = array();
            foreach ($GET as $key => $value) {
                // code...
                if (strstr($key, "brand-")) {
                    $brands[] = $value;
                }
            }

            if (count($brands) > 0) {
                $params['brands'] = implode("','", $brands);
            }

            $query = "SELECT prod.*,
                      brands.brand as brand_name,
                      cat.category as category_name
                      FROM products as prod
                      join brands on brands.id = prod.brand 
                      join categories as cat on cat.id = prod.category";

            if (count($params) > 0) {
                $query .= " WHERE ";
            }

            if (isset($params['description'])) {
                $query .= " prod.description like '%$params[description]%' AND ";
            }

            if (isset($params['category'])) {
                $query .= " cat.id = '$params[category]' AND ";
            }

            if (isset($params['min-price'])) {
                $query .= " (prod.price BETWEEN '".$params['min-price']."' AND '".$params['max-price']."') AND ";
            }

            if (isset($params['min-qty'])) {
                $query .= " (prod.quantity BETWEEN '".$params['min-qty']."' AND '".$params['max-qty']."') AND ";
            }

            if (isset($params['year'])) {
                $query .= " YEAR(prod.date) = '$params[year]' AND ";
            }

            if (isset($params['brands'])) {
                $query .= " brands.id in ('" . $params['brands'] . "') AND ";
            }

            $query = trim($query);
            $query = trim($query, 'AND'); //ltrim - left, rtrim - right 

            $query .= " order by prod.id desc 
                       limit $limit 
                       offset $offset";

                       return $query;
    }

}