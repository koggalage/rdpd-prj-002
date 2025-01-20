<?php

Class Settings
{
    private $error = "";
    protected static $SETTINGS = null;

    function get_all_settings()
    {
        $db = Database::newInstance();
        $query = "select * from settings";
        return $db->read($query);
    }

    //Magic Function. Runs when you called a method that not exist.
    static function __callStatic($name, $params)
    {
        if (self::$SETTINGS) {
            $settings = self::$SETTINGS;
        } else {
            $settings = self::get_all_settings_as_object();
        }
        
        if (isset($settings->$name))
        {
            return $settings->$name;
        }
        
        return "";
    }

    public static function get_all_settings_as_object()
    {
        $db = Database::newInstance();
        $query = "select * from settings";
        $data = $db->read($query);

        $settings = (object)[];

        if (is_array($data)) {
            foreach ($data as $row) {
                $setting_name = $row->setting;
                $settings->$setting_name = $row->value;
            }
        }

        self::$SETTINGS = $settings;;
        return $settings;
    }

    public function save_settings($POST) 
    {
        $db = Database::newInstance();

        foreach ($POST as $key => $value) {

            $arr = array();
            $arr['setting'] = $key;

            if (strstr($key, "_link")) {

                if (trim($value) != "" && !strstr($value, "https://")) {
                    $value = "https://" . $value;
                }
                
                $arr['value'] = $value;
            } else {
                $arr['value'] = $value;
            }
            
            $query = "update settings set value = :value where setting = :setting limit 1";
            $db->write($query, $arr);
        }
    }
}