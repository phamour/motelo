<?php

class CRUD
{
    // Check and dispatch for C & U
    private static function preStore(PDO $db, $values)
    {
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

        return array(
            'fields' => $fields,
            'values' => $values
        );
    }

    // C
    public static function insert(PDO $db, $tablename, array $values)
    {
        $data = self::preStore($db, $values);
        if (!$data) {
            return false;
        }

        // construct
        $sql = "INSERT INTO " . $tablename . 
            '(' . implode(',', $data['fields']) . ') ' . 
            'VALUES (' . implode(',', $data['values']) . ')';
        
        if ($db->exec($sql) === 1) {
            return $db->lastInsertId();
        } else {
            return false;
        }
    }

    // R
    public static function raw($db, $args)
    {
        $args['limit'] = 1;
        $result = self::select($db, $args);
        if (empty($result)) {
            return false;
        } else {
            return $result[0];
        }
    }

    // R
    public static function select($db, $args)
    {
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

    // U
    public static function update(PDO $db, $tablename, $id, array $values)
    {
        $data = self::preStore($db, $values);
        if (!$data) {
            return false;
        }

        $sql = 'UPDATE ' . $tablename . ' ' . 
            'SET ' . implode(',', array_map(function($k, $v) {
                    return $k . '=' . $v;
                }, $data['fields'], $data['values'])) . ' ' . 
            'WHERE id=' . $id;

        if ($db->exec($sql) === 1) {
            return $id;
        } else {
            return false;
        }
    }

    // D
    // NOTE: do hard delete for safety reasons
    // TODO: make a choice for user to soft or hard delete an element
    public static function delete(PDO $db, $tablename, $id)
    {
        $sql = 'DELETE FROM ' . $tablename . ' ' .
            'WHERE id=' . $id;

        return $db->exec($sql) === 1;
    }
}

// END /lib/CRUD.class.php
