<?php

namespace App\Controllers\Upload;

use Slim\Psr7\UploadedFile;

class UploadController
{
    protected $path = __DIR__ . "/../../../public_html/upload/image";
    protected $upload;
    protected $directory;

    public function __construct($upload, $directory)
    {
        $this->upload = $upload;
        $this->directory = $directory;
    }

    public function upload()
    {
        $directory = $this->path . DIRECTORY_SEPARATOR . $this->directory;
        $uploadedFile = $this->upload;

        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $filename = UploadController::moveUploadedFile($directory, $uploadedFile);
        }

        return $filename;
    }

    public function moveUploadedFile($directory, UploadedFile $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename  = bin2hex(random_bytes(8));
        $filename  = sprintf('%s.%0.8s', $basename, $extension);

        $data = $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }
}