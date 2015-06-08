<?php

class API {

    public static function getOne() {}

    public static function getList(PDO $db, $type, $options = array()) {
        $default = array(
            'fields' => '*',
            'where'  => 'status=1',
            'json'   => true
        );
        $options = array_merge($default, $options);

        // prepare
        $args = self::getArgs($type, $options);

        // query
        $results = CRUD::select($db, $args);
        if ($options['json']) $results = json_encode($results);

        return $results;
    }

    private static function getArgs($type, $options) {
        return array(
            'table'  => $type,
            'fields' => is_array($options['fields']) ? implode(',', $options['fields']) : $options['fields'],
            'where'  => $options['where']
        );
    }
}

// END /lib/API.class.php
