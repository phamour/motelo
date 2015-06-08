<?php

class Uploader {

    public static function upload($type, $name, $file) {
        if (empty($file) || !isset($file[$name]) || $file[$name]['error'] !== 0) {
            return false;
        }

        $path = STORAGE_DIR . $type . '/' . $file[$name]['name'];

        // unify ext
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
        $path = str_replace('.txt', $ext, $path);

        return !file_exists($path) && move_uploaded_file($file[$name]['tmp_name'], $path);
    }

}

// END /lib/Uploader.class.php
