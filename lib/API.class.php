<?php

class API {

    private static function preGet($type, &$options) {
        $default = array(
            'fields' => '*',
            'where'  => 'status=1',
            'json'   => true
        );
        $options = array_merge($default, $options);

        // prepare
        return self::getArgs($type, $options);
    }

    private static function getArgs($type, $options) {
        return array(
            'table'  => $type,
            'fields' => is_array($options['fields']) ? implode(',', $options['fields']) : $options['fields'],
            'where'  => $options['where']
        );
    }

    public static function getOne(PDO $db, $type, $options = array()) {
        $args = self::preGet($type, $options);

        // query
        $result = CRUD::raw($db, $args);
        if ($options['json']) $result= json_encode($result);

        return $result;
    }

    public static function getList(PDO $db, $type, $options = array()) {
        $args = self::preGet($type, $options);

        // query
        $results = CRUD::select($db, $args);
        if ($options['json']) $results = json_encode($results);

        return $results;
    }

}

// END /lib/API.class.php
