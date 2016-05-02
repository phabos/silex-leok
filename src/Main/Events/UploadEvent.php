<?php
namespace Main\Events;

use Symfony\Component\EventDispatcher\Event;

class UploadEvent extends Event
{

    const FILE_UPLOAD = "main_upload.after_file_upload";
    /** @var string */
    protected $fileName = null;
    protected $fileTmpName = null;
    protected $uploadDir = null;
    protected $uploadPath = null;
    protected $res;

    public function setFileName( $fileName )
    {
        $this->fileName = $fileName;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function setUploadDir( $uploadDir )
    {
        $this->uploadDir = $uploadDir;
    }

    public function getUploadDir()
    {
        return $this->uploadDir;
    }

    public function setFileTmpName( $fileTmpName )
    {
        $this->fileTmpName = $fileTmpName;
    }

    public function getFileTmpName()
    {
        return $this->fileTmpName;
    }

    public function setUploadPath( $uploadPath )
    {
        $this->uploadPath = $uploadPath;
    }

    public function getUploadPath()
    {
        return $this->uploadPath;
    }

    public function setResponse( $res ) {
        $this->res = $res;
    }

    public function getResponse() {
        return $this->res;
    }

}