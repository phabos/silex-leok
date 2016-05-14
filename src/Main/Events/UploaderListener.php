<?php
namespace Main\Events;

use Symfony\Component\EventDispatcher\Event;
use Main\Lib\ImageManipulator;

class UploaderListener
{

    public function CropAndResizeAction( Event $event )
    {

        $uploadDir = self::getUploadDir( $event->getUploadDir() );
        error_log( 'Path : ' . $uploadDir . ' ' . `whoami` );
        if( ! is_dir( $uploadDir ) )
        {
            if ( mkdir( $uploadDir, 0775, true ) )
            {
                chmod( $uploadDir, 0775 );
            }else{
                return $event->setResponse( array( 'msg' => 'impossible to create upload folder' ) );
            }
        }

        if( move_uploaded_file( $event->getFileTmpName(), $uploadDir . '/' . $event->getFileName() ) )
        {
            if( preg_match( '(\.png|\.jpg|\.jpeg|\.gif)', $event->getFileName() ) )
            {

                self::cropAndResizePhoto( $event->getFileName(), $uploadDir );
            }

            return $event->setResponse( array( 'mediaUrl' => self::getUploadPath( $event->getUploadPath() ) . '/' . $event->getFileName(), 'msg' => 'Upload réussi' ) );
        }

        return $event->setResponse( array( 'mediaUrl' => '', 'msg' => 'Something went bad :(' ) );

    }

    private function cropAndResizePhoto( $photoPath, $uploadDir )
    {
        $manipulator = new ImageManipulator( $uploadDir . '/' . $photoPath );
        $min = min($manipulator->getWidth(), $manipulator->getHeight());
        $x1 = $y1 = 0;
        $x2 = $y2 = $min;
        $manipulator->crop($x1, $y1, $x2, $y2);
        $manipulator->resample(500, 500, true);
        $manipulator->save($uploadDir . '/' . 'R_500x500-' . $photoPath);
    }

    private function getUploadDir( $root )
    {
        // Si le dossier image n'existe pas on le crée format -> m-Y
        $currentDate = new \Datetime();
        $uploadDir = $root . $currentDate->format( 'm-Y' );
        return $uploadDir;
    }

    private function getUploadPath( $root )
    {
        // Si le dossier image n'existe pas on le crée format -> m-Y
        $currentDate = new \Datetime();
        $uploadPath = $root . $currentDate->format( 'm-Y' );
        return $uploadPath;
    }

}
