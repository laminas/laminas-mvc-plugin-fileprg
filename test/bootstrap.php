<?php

declare(strict_types=1);

if (! is_dir(__DIR__ . '/../vendor')) {
    return;
}

(function (): void {
    // phpcs:disable Generic.Files.LineLength.TooLong
    $renameUploadClassFile = file_get_contents(__DIR__ . '/../vendor/laminas/laminas-filter/src/File/RenameUpload.php');
    $renameUploadClassFile = preg_replace('/^(use function move_uploaded_file;)$/m', '// $1', $renameUploadClassFile);
    file_put_contents(__DIR__ . '/../polyfill/Filter/File/RenameUpload.php', $renameUploadClassFile);

    $uploadFileClassFile = file_get_contents(__DIR__ . '/../vendor/laminas/laminas-validator/src/File/UploadFile.php');
    $uploadFileClassFile = preg_replace('/^(use function is_uploaded_file;)$/m', '// $1', $uploadFileClassFile);
    file_put_contents(__DIR__ . '/../polyfill/Validator/File/UploadFile.php', $uploadFileClassFile);
    // phpcs:enable Generic.Files.LineLength.TooLong
})();

require_once __DIR__ . '/../vendor/autoload.php';
