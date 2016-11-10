<?php

namespace Gloves\Model;

defined('ABSPATH') or die('No script kiddies please!');

abstract class Model
{

    /**
     * Contains fields of model as $name => $type
     *
     * @var array
     */
    protected static $fields;
    protected static $version;
    protected static $tableName;

    public static function create()
    {
        global $wpdb;

        $class = \explode('\\', get_called_class());
        $tableName = $wpdb->prefix . \strtolower(\end($class));
        $tableName = static::$tableName;
        //var_dump($tableName);
        if (get_option($tableName . '_version') != static::$version) {
            static::drop();
            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE $tableName (
		ID mediumint(9) NOT NULL AUTO_INCREMENT, ";
            foreach (static::$fields as $name => $type) {
                $sql .= "$name $type NOT NULL, ";
            }
            $sql .= "UNIQUE KEY id (id)) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            $wpdb->query($sql);

            \update_option($tableName . '_version', static::$version);
        }
    }

    /**
     * Drops table in database
     *
     * @global $wpdb
     */
    public static function drop()
    {
        global $wpdb;
        $tableName = static::$tableName;

        $sql = "DROP TABLE IF EXISTS $tableName;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $wpdb->query($sql);
        \delete_option($tableName . '_version');
    }

    public static function get($field = null, $value = '*')
    {
        global $wpdb;

        $tableName = static::$tableName;

        $sql = "SELECT * FROM $tableName";
        if ($field !== null) {
            $sql .= " WHERE $field = '$value'";
        }
        $row = $wpdb->get_results($sql, OBJECT);

        return $row;
    }

    /**
     *
     * All fields, but ID has to be provided.
     *
     * @global type $wpdb
     * @param array $data
     * @return integer
     */
    public static function insert($data)
    {
        global $wpdb;
        $tableName = static::$tableName;
        $wpdb->insert($tableName, $data);
        return $wpdb->insert_id;
    }
    
    public static function update($data, $where)
    {
        global $wpdb;
        $tableName = static::$tableName;
        return $wpdb->update($tableName, $data, $where);
    }


    /**
     *
     * @global  $wpdb
     * @param integer $id
     * @return number of rows affected or false on error
     */
    public static function delete($id)
    {
        global $wpdb;
        return $wpdb->delete(static::$tableName, array('ID' => $id));
    }
}
