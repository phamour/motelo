<?php

class Uploader {

    public static function upload($type, $name, $file) {
        if (empty($file) || !isset($file[$name]) || $file[$name]['error'] !== 0) {
            return false;
        }

        $path = STORAGE_DIR . $type . '/' . $file[$name]['name'];

        return !file_exists($path) && move_uploaded_file($file[$name]['tmp_name'], $path);
    }

}

// END /lib/Uploader.class.php
