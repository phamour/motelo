<?php

class CRUD {

    public static function insert(PDO $db, $tablename, array $values) {
        if (empty($values)) {
            return false;
        }

        // prepare
        $fields = array_keys($values);
        $values = array_values($values);

        // treat values
        foreach ($values as $i => $value) {
            $values[$i] = $db->quote((string) $value);
        }

        // construct
        $sql = "INSERT INTO " . $tablename . 
            '(' . implode(',', $fields) . ') ' . 
            'VALUES (' . implode(',', $values) . ')';
        
        if ($db->exec($sql) === 1) {
            return $db->lastInsertId();
        } else {
            return false;
        }
    }

    public static function select($db, $args) {
        $default = array(
            'table'    => '',
            'distinct' => false,
            'fields'   => '*',
            'where'    => '1',
            'groupby'  => '1',
            'orderby'  => '1',
            'limit'    => 30,
            'offset'   => 0
        );
        $args = array_merge($default, $args);

        $args['distinct'] = $args['distinct'] ? 'DISTINCT ' : '';

        $sql = 'SELECT ' . $args['distinct'] . $args['fields'] . ' ' . 
            'FROM '      . $args['table']    . ' ' . 
            'WHERE '     . $args['where']    . ' ' . 
            'GROUP BY '  . $args['groupby']  . ' ' . 
            'ORDER BY '  . $args['orderby']  . ' ' . 
            'LIMIT '     . $args['limit']    . ' ' . 
            'OFFSET '    . $args['offset'];

        $statement = $db->query($sql);
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        return $statement->fetchAll();
    }
}

// END /lib/CRUD.class.php
