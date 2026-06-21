<?php
$info = [
    'sapi' => php_sapi_name(),
    'php_ini' => php_ini_loaded_file(),
    'upload_max_filesize' => ini_get('upload_max_filesize'),
    'post_max_size' => ini_get('post_max_size'),
    'memory_limit' => ini_get('memory_limit'),
    'max_execution_time' => ini_get('max_execution_time'),
    'upload_tmp_dir' => ini_get('upload_tmp_dir') ?: sys_get_temp_dir(),
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $info['files'] = $_FILES;
}

header('Content-Type: application/json');
echo json_encode($info, JSON_PRETTY_PRINT);
