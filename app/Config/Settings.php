<?php
namespace Config;

use CodeIgniter\Config\BaseConfig;

class Settings extends BaseConfig
{

    const TABLE = 'config';
    private $db;

    function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->get_all();

    }


    function get_all()
    {
        $builder = $this->db->table(self::TABLE);
        $Settings = $builder->get();

        foreach ($Settings->getResult() as $Setting) {
            if (!$Setting->serialized) {
                $this->{$Setting->key} = $Setting->value;//set value
            } else {
                $this->{$Setting->key} = json_decode($Setting->value, true);
            }

        }

    }
}
