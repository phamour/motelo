<?php

class FormActions {

    public static function create($app, $type) {
        $ok = true;
        $values = array();
        switch ($type) {
            default:
            case TYPE_SOLUTION:
                $ok = self::createSolution($app, $values);
                break;
            case TYPE_INSTANCE:
                $ok = self::createInstance($app, $values);
                break;
            case TYPE_MODEL:
                $ok = self::createModel($app, $values);
                break;
        }

        if (!$ok) {
            Utils::yflash('error', 'Failed to upload the instance file.');
            return false;
        }

        // insert
        if (CRUD::insert($app->db, $type, $values) == false) {
            Utils::yflash('error', 'Failed to create ' . $type . '.');
            return false;
        } else {
            Utils::yflash('success', ucfirst($type) . ' created successfully.');
            return true;
        }
    }

    private static function createSolution($app, &$values) {
        // prepare
        $values = array(
            'model_id'    => $app->request->post('solution_model_id'),
            'instance_id' => $app->request->post('solution_instance_id'),
            'created_at'  => date('Y-m-d H:i:s')
        );
        $values['has_solution'] = $app->request->post('solution_no_solution') ? 0 : 1;
        if ($values['has_solution'] === 1) {
            $values['z'] = $app->request->post('solution_z');
            $values['t'] = $app->request->post('solution_t');
        }

        if (!Uploader::upload('solution', 'solution_file', $_FILES)) {
            return false;
        } else {
            $values['filename'] = preg_replace('/(\.sol)|(\.txt)/', '', $_FILES['solution_file']['name']);
            return true;
        }
    }

    private static function createInstance($app, &$values) {
        // prepare
        $values = array(
            'nb_nodes'   => $app->request->post('instance_nb_nodes'),
            'nb_edges'   => $app->request->post('instance_nb_edges'),
            'created_at' => date('Y-m-d H:i:s')
        );
        if ($app->request->post('instance_blockage_o') !== '') {
            $values['blockage_o'] = $app->request->post('instance_blockage_o');
        }
        if ($app->request->post('instance_blockage_d') !== '') {
            $values['blockage_d'] = $app->request->post('instance_blockage_d');
        }

        if (!Uploader::upload('instance', 'instance_file', $_FILES)) {
            return false;
        } else {
            $values['filename'] = preg_replace('/(\.dat)|(\.txt)/', '', $_FILES['instance_file']['name']);
            return true;
        }
    }

    private static function createModel($app, &$values) {
        // prepare
        $values = array(
            'label'      => $app->request->post('model_label'),
            'created_at' => date('Y-m-d H:i:s')
        );
        if (Uploader::upload('model', 'model_file', $_FILES)) {
            $values['filename'] = preg_replace('/(\.mod)|(\.txt)/', '', $_FILES['model_file']['name']);
        }
        return true;
    }
}

// END /lib/FormActions.class.php
