<?php

Class User
{
    private $error = "";

    function signup($POST)
    {
        $data = array();

        $db = Database::getInstance();

        $data['name'] = trim($POST["name"]);
        $data['email'] = trim($POST["email"]);
        $data['password'] = trim($POST["password"]);
        $password2 = trim($POST["password2"]);
        
        //show($data);

        if(empty($data['email']) || !preg_match("/^[a-zA-Z_-]+@[a-zA-Z]+.[a-zA-Z]+$/", $data['email']))
        {
            $this->error .= "Please enter a valid email <br>";
        }

        if(empty($data['name']) || !preg_match("/^[a-zA-Z]+$/", $data['name']))
        {
            $this->error .= "Please enter a valid name <br>";
        }

        if($data['password'] !== $password2)
        {
            $this->error .= "Passwords do not match <br>";
        }

        if(strlen($data['password']) < 4)
        {
            $this->error .= "Password must be atleast 4 characters long <br>";
        }

        $sql = "select * from users where email = :email limit 1";
        $arr['email'] = $data['email'];
        $check = $db->read($sql, $arr);

        if(is_array($check))
        {
            $this->error .= "That email already in use <br>";
        }

        $data['url_address'] = $this->get_random_string_max(60);

        $arr = array(); //clear the arr

        $sql = "select * from users where url_address = :url_address limit 1";
        $arr['url_address'] = $data['url_address'];
        $check = $db->read($sql, $arr);

        if(is_array($check))
        {
            $data['url_address'] = $this->get_random_string_max(60);
        }

        if($this->error == "")
        {
            $data['rank'] = "customer";
            
            $data['date'] = date("Y-m-d H:i:s");

            $data['password'] = hash('sha1', $data['password']);

            $query = "insert into users (url_address,name,email,password,rank,date) values (:url_address,:name,:email,:password,:rank,:date)";
            echo($query);
            
            $result = $db->write($query,$data);
            print_r($result);

            if($result)
            {
                header("Location: " . ROOT . "login");
                die;
            }
        }

        $_SESSION['error'] = $this->error;
    }

    function login($POST)
    {
        echo('login');
        $data = array();

        $db = Database::getInstance();

        $data['email'] = trim($POST["email"]);
        $data['password'] = trim($POST["password"]);
        
        //show($data);

        if(empty($data['email']) || !preg_match("/^[a-zA-Z_-]+@[a-zA-Z]+.[a-zA-Z]+$/", $data['email']))
        {
            $this->error .= "Please enter a valid email <br>";
        }

        if(strlen($data['password']) < 4)
        {
            $this->error .= "Password must be atleast 4 characters long <br>";
        }

        if($this->error == "")
        {
            $data['password'] = hash('sha1', $data['password']);

            $sql = "select * from users where email = :email && password = :password limit 1";
            $result = $db->read($sql, $data);

            if(is_array($result))
            {
                $_SESSION['user_url'] = $result[0]->url_address;
                header("Location: " . ROOT . "home");
                die;
            }

            $this->error .= "Wrong email or password <br>";
        }

        $_SESSION['error'] = $this->error;
    }

    function get_user($url)
    {

    }

    function check_login($redirect = false, $allowed = array())
    {
        $db = Database::getInstance();

        if (count($allowed) > 0) {

            $arr['url'] = $_SESSION['user_url'];
            $query = "select * from users where url_address = :url limit 1";
            $result = $db->read($query, $arr);

            if (is_array($result)) {
                $result = $result[0];
                if (in_array($result->rank, $allowed)) {
                    return $result;
                }
            }

            header("Location: " . ROOT . "login");
            die;
        } else {

            if (isset($_SESSION['user_url'])) {
                $arr = array();
                $arr['url'] = $_SESSION['user_url'];
                $query = "select * from users where url_address = :url limit 1";

                $result = $db->read($query, $arr);

                if (is_array($result)) {
                    return $result[0];
                }
            }

            if ($redirect) {
                header("Location: " . ROOT . "login");
                die;
            }
        }

        return false;
    }

    public function logout()
    {
        if(isset($_SESSION['user_url']))
        {
            unset($_SESSION['user_url']);
        }

        header("Location: " . ROOT . "home");
                die;
    }

    private function get_random_string_max($lenght)
    {
        $array = [
            0, 1, 2, 3, 4, 5, 6, 7, 8, 9,
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
        ];

        $text = "";

        $lenght = rand(4, $lenght);

        for($i=0;$i<$lenght;$i++)
        {
            $random = rand(0,61);

            $text .= $array[$random];
        }

        return $text;
    }
}