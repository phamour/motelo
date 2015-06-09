<?php

class FormActions {

    public static function create($app, $type) {
        $ok = true;
        $values = array();
        switch ($type) {
            default:
            case TYPE_SOLUTION:
                $ok = self::storeSolution('create', $app, $values);
                break;
            case TYPE_INSTANCE:
                $ok = self::storeInstance('create', $app, $values);
                break;
            case TYPE_MODEL:
                $ok = self::storeModel('create', $app, $values);
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

    public static function update($app, $type, $id) {
        $values = array();
        switch ($type) {
            default:
            case TYPE_SOLUTION:
                self::storeSolution('update', $app, $values);
                break;
            case TYPE_INSTANCE:
                self::storeInstance('update', $app, $values);
                break;
            case TYPE_MODEL:
                self::storeModel('update', $app, $values);
                break;
        }

        // update
        if (CRUD::update($app->db, $type, $id, $values) == false) {
            Utils::yflash('error', 'Failed to update ' . $type . '.');
            return false;
        } else {
            Utils::yflash('success', ucfirst($type) . ' updated successfully.');
            return true;
        }
    }

    public static function delete($app, $type, $id) {
        if (CRUD::softDelete($app->db, $type, $id) == false) {
            Utils::yflash('error', 'Failed to delete ' . $type . '.');
            return false;
        } else {
            Utils::yflash('success', ucfirst($type) . ' deleted successfully.');
            return true;
        }
    }

    private static function storeSolution($type, $app, &$values) {
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

        if ($type === 'update') {
            unset($values['created_at']);
            return true;
        }

        if (!Uploader::upload('solution', 'solution_file', $_FILES)) {
            return false;
        } else {
            $values['filename'] = preg_replace('/(\.sol)|(\.txt)/', '', $_FILES['solution_file']['name']);
            return true;
        }
    }

    private static function storeInstance($type, $app, &$values) {
        // prepare
        $values = array(
            'nb_nodes'   => $app->request->post('instance_nb_nodes'),
            'nb_edges'   => $app->request->post('instance_nb_edges'),
            'created_at' => date('Y-m-d H:i:s')
        );
        $label = 'v' . $values['nb_nodes'] . '_e' . $values['nb_edges'];
        if ($app->request->post('instance_blockage_o') !== '') {
            $values['blockage_o'] = $app->request->post('instance_blockage_o');
            $label .= '_o' . $values['blockage_o'];
        }
        if ($app->request->post('instance_blockage_d') !== '') {
            $values['blockage_d'] = $app->request->post('instance_blockage_d');
            $label .= '_d' . $values['blockage_d'];
        }
        $values['label'] = $label;

        if ($type === 'update') {
            unset($values['created_at']);
            return true;
        }

        if (!Uploader::upload('instance', 'instance_file', $_FILES)) {
            return false;
        } else {
            $values['filename'] = preg_replace('/(\.dat)|(\.txt)/', '', $_FILES['instance_file']['name']);
            return true;
        }
    }

    private static function storeModel($type, $app, &$values) {
        // prepare
        $values = array(
            'label'      => $app->request->post('model_label'),
            'created_at' => date('Y-m-d H:i:s')
        );

        if ($type === 'update') {
            unset($values['created_at']);
            return true;
        }

        if (Uploader::upload('model', 'model_file', $_FILES)) {
            $values['filename'] = preg_replace('/(\.mod)|(\.txt)/', '', $_FILES['model_file']['name']);
        }
        return true;
    }
}

// END /lib/FormActions.class.php
