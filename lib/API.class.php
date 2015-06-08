<?php

class API {

    public static function getOne() {}

    public static function getList(PDO $db, $type, $json = true, $all = false) {
        // prepare
        $args = array();
        switch ($type) {
            default:
            case CREATION_TYPE_SOLUTION:
                $args = self::getSolutionsArgs();
                break;
            case CREATION_TYPE_INSTANCE:
                $args = self::getInstancesArgs();
                break;
            case CREATION_TYPE_MODEL:
                $args = self::getModelsArgs();
                break;
        }
        if ($all) unset($args['where']);

        // query
        $results = CRUD::select($db, $args);
        if ($json) $results = json_encode($results);

        return $results;
    }

    private static function getModelsArgs() {
        return array(
            'table'  => 'model',
            'fields' => 'id, label, filename, created_at',
            'where'  => 'status=1'
        );
    }

    private static function getInstancessArgs() {
        return array(
            'table'  => 'instance',
            'fields' => 'id, nb_nodes, nb_edges, blockage_o, blockage_d, filename, created_at',
            'where'  => 'status=1'
        );
    }

    private static function getSolutionsArgs() {
        return array(
            'table' => 'solution',
            'fields' => 'id, model_id, instance_id, has_solution, z, t, filename, created_at',
            'where' => 'status=1'
        );
    }
}

// END /lib/API.class.php
