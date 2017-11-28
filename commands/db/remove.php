<?php

$sql = <<<SQL
DROP TABLE IF EXISTS "jdi_task";
DROP TABLE IF EXISTS "jdi_task_run";
SQL
;

foreach (explode(';', $sql) as $query) {
    $query = trim($query);
    
    if ($query) {
        printf("Выполняем запрос\n%s\n\n", $query);
        Db()->query($query);
    }
}

$blobs = Config()->get('blob.folder');

printf("Очищаем папку %s\n\n", $blobs);

foreach (glob(ExpandPath("$blobs/*/*/*")) as $file) {
    if (strpos($file, ROOT_DIR) === false) {
        throw new Exception(printf("Попытка удалить файл из директории вне приложения: `%s'", $file));
    }
    
    unlink($file);
}

foreach (glob(ExpandPath("$blobs/*/*")) as $file) {
    @rmdir($file);
}

foreach (glob(ExpandPath("$blobs/*")) as $file) {
    @rmdir($file);
}
