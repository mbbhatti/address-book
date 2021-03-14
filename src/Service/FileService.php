<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Filesystem\Filesystem;

class FileService
{
    /**
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * @var string
     */
    private $path;

    /**
     * FileService constructor.
     * @param string $path
     * @param Filesystem $fileSystem
     */
    public function __construct(string $path, Filesystem $fileSystem)
    {
        $this->path = $path;
        $this->fileSystem = $fileSystem;
    }

    /**
     * @param object $file
     * @return string
     */
    public function upload(object $file): string
    {
        $fileName = time() . '_' . $file->getClientOriginalName();
        $fileExt = $file->getClientOriginalExtension();
        $extensions = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];
        if (in_array($fileExt, $extensions) === false) {
            throw new FileException('Allowed JPG|JPEG|PNG|GIF|BMP extension file.');
        }

        try {
            $file->move($this->path, $fileName);
        } catch (FileException $e){
            throw new FileException('File not uploaded.');
        }

        return $fileName;
    }

    /**
     * @param string $file
     */
    public function remove(string $file)
    {
        $filePath = $this->path . '/' . $file;
        if ($this->fileSystem->exists($filePath)) {
            $this->fileSystem->remove([$filePath]);
        }
    }
}

