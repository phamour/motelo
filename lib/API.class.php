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

    private static function getSolutionArgs() {
        return array(
            'table' => 'solution AS s INNER JOIN model AS m, instance AS i',
            'fields' => implode(',', array(
                's.*',
                'm.label AS model_label',
                'm.filename AS model_filename',
                'i.label AS instance_label',
                'i.filename AS instance_filename'
            )),
            'where' => 's.model_id=m.id AND s.instance_id=i.id AND s.status=1'
        );
    }

    public static function getOne(PDO $db, $type, $options = array()) {
        $args = self::preGet($type, $options);

        // query
        $result = CRUD::raw($db, $args);
        if ($options['json']) $result= json_encode($result);

        return $result;
    }

    public static function getOneById(PDO $db, $type, $id, $options = array()) {
        if (!isset($options['where']) || $options['where'] === '') {
            $options['where'] = 'id=' . $id;
        } else {
            $options['where'] .= ' AND id=' . $id;
        }
        return self::getOne($db, $type, $options);
    }

    public static function getOneSolution(PDO $db, $id, $options = array()) {
        self::preGet('solution', $options);
        $args = self::getSolutionArgs();
        $args['where'] .= ' AND s.id=' . $id;

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

    public static function getSolutionList(PDO $db, $options = array()) {
        self::preGet('solution', $options);
        $args = self::getSolutionArgs();

        // query
        $result = CRUD::select($db, $args);
        if ($options['json']) $result= json_encode($result);

        return $result;
    }

    public static function getFileContent($type, $filename) {
        $ext = '.txt';
        switch ($type) {
            default:
            case TYPE_SOLUTION:
                $ext = '.sol';
                break;
            case TYPE_INSTANCE:
                $ext = '.dat';
                break;
            case TYPE_MODEL:
                $ext = '.mod';
                break;
        }
        $path = STORAGE_DIR . $type . '/' . $filename . $ext;
        return file_get_contents($path);
    }

    private static function removeComments($source) {
        if (!$source) {
            return '{}';
        }

        // replace block comments
        $source = preg_replace('/(\/\*((([^*]+)|\*(?!\/))*)\*\/)/', '', $source);

        // replace inline comments
        $source = preg_replace('/(\/\/.*[\n\r])/', '', $source);

        return $source;
    }

    public static function dat2json($source) {
        if (!$source) {
            return '{}';
        }

        // remove comments
        $source = self::removeComments($source);

        // keep the content in form
        $content = $source;

        // strip white-spaces and line-ends
        $source = preg_replace('/[\s\n\r]+/', '', $source);

        // format to json key-value pairs
        $source = preg_replace('/([^=]+)=([^;]+);/', '"${1}":$2,', $source);

        // remove last comma
        if (substr($source, -1) == ',') {
            $source = substr($source, 0, strlen($source) - 1);
        }

        // parse arcs
        $source = preg_replace('/"arcs":{([<>0-9,]+)}/', '"arcs":[${1}]', $source);
        $source = preg_replace('/<([0-9]+,[0-9]+,[0-9.]+)>/', '[${1}]', $source);

        $data = json_decode('{' . $source . '}', true);
        $data['content'] = $content;

        // encap to json object
        return json_encode($data);
    }

    public static function sol2json($source) {
        if (!$source) {
            return '{}';
        }

        // remove comments
        $source = self::removeComments($source);

        // parse obj value and time
        $matches = array();
        preg_match('/z\s*=\s*(-?[0-9.]+)/', $source, $matches);
        $z = intval($matches[1]);
        preg_match('/time\s*=\s*([0-9.]+)/', $source, $matches);
        $t = floatval($matches[1]);
        
        return json_encode(array(
            'z'       => $z,
            't'       => $t,
            'f'       => self::parseSol($source, 'f', 4),
            'y'       => self::parseSol($source, 'y', 4),
            'x'       => self::parseSol($source, 'x'),
            'content' => $source
        ));
    }

    private static function parseSol($source, $key, $dimension = 2) {
        // construct RegEx pattern
        $pattern = '/' . $key;
        for ($i = 0; $i < $dimension; $i++) {
            $pattern .= '\[([0-9]+)\]';
        }
        $pattern .= '\s*=\s*(-?[0-9]+)/';

        // matches holder
        $matches = array();
        // result holder
        $result = array();

        // match
        preg_match_all($pattern, $source, $matches);

        // parse match result
        foreach ($matches[0] as $j => $value) {
            if ($matches[$dimension + 1][$j] != '0') {
                $tmp = array();
                for ($i = 0; $i <= $dimension; $i++) {
                    $tmp[] = intval($matches[$i + 1][$j]);
                }
                $result[] = $tmp;
            }
        }
        return $result;
    }

}

// END /lib/API.class.php
