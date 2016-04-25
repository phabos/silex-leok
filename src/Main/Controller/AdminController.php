<?php

namespace Main\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Main\Repository\OptionsRepository;
use Main\Lib\ImageManipulator;

class AdminController
{

    protected static $articleLimit = 15;

	public function indexAction(Request $request, Application $app)
	{
		return $app['twig']->render('admin/backbone.home.tpl.html', array());
    }

    /*** Settings page ***/
	public function settingsAction(Request $request, Application $app)
	{
		if($request->isXmlHttpRequest())
		{
    		if($request->getMethod() == 'POST')
    		{
                $app['repository.options']->updateSettings( $request->getContent() );
				return $app->json( array() );
			}

			if($request->getMethod() == 'GET')
    		{
                $_settings = $app['repository.options']->getSettings();
    			$settings = @json_decode( $_settings['value'], true );
                return $app->json( $settings );
    		}
		}

		return $app['twig']->render( 'admin/backbone.settings.tpl.html', array() );

	}

    /*** delete article ***/
    public function deleteArticleAction(Request $request, Application $app)
    {
        $id = $request->attributes->get('id');
        $articles = $app['repository.article']->delete( $id );
        return $app->redirect($app['url_generator']->generate('adminarticles'));

    }

    /*** Articles page ***/
    public function articlesAction(Request $request, Application $app)
    {
        if($request->isXmlHttpRequest())
        {
            if($request->getMethod() == 'GET')
            {
                $offset = (int) $request->query->get('offset') * self::$articleLimit;
                $articles = $app['repository.article']->getArticles( self::$articleLimit, $offset );
                return $app->json( $articles );
            }
        }

        return $app['twig']->render( 'admin/backbone.article.tpl.html', array() );

    }

    /*** Article Edd ***/
    public function addArticleAction(Request $request, Application $app)
    {
        $articleId = $app['repository.article']->create();
        return $app->redirect($app['url_generator']->generate('adminarticlesedit', ['id' => $articleId]));
    }

    /*** Article Edit ***/
    public function editArticleAction(Request $request, Application $app)
    {
        if($request->isXmlHttpRequest())
        {
            if($request->getMethod() == 'GET')
            {
                $id = $request->attributes->get('id');
                $article = $app['repository.article']->read( $id );
                $article['gallery'] = @json_decode( $article['gallery'] );
                return $app->json( $article );
            }

            if($request->getMethod() == 'PUT')
            {
                $id = $request->attributes->get('id');
                $datas = @json_decode( $request->getContent(), true );
                $datas['gallery'] = @json_encode( $datas['gallery'] );
                $app['repository.article']->update( $datas, $id );
                return $app->json();
            }
        }

        return $app['twig']->render( 'admin/backbone.articleedit.tpl.html', array( 'id' => $request->attributes->get('id') ) );
    }

    /*** Send Images method ***/
    public function sendMediasAction(Request $request, Application $app)
    {
        if($request->getMethod() == 'POST')
        {
            if (isset($_FILES['myFile']))
            {
                $uploadDir = $this->getUploadDir( $app['upload.path'] );
                if( ! is_dir( $uploadDir ) )
                {
                  if ( mkdir( $uploadDir, 0775, true ) )
                  {
                    chmod( $uploadDir, 0775 );
                  }else{
                    return $app->json( array( 'msg' => 'impossible to create upload folder' ) );
                  }
                }

                if( move_uploaded_file( $_FILES['myFile']['tmp_name'], $uploadDir . '/' . $_FILES['myFile']['name'] ) )
                {
                    $this->cropAndResizePhoto( $_FILES['myFile']['name'], $uploadDir );
                    return $app->json( array( 'mediaUrl' => $this->getUploadPath( $app['webroot.path'] ) . '/' . $_FILES['myFile']['name'], 'msg' => 'Upload réussi' ) );
                }else{
                    return $app->json( array( 'mediaUrl' => '', 'msg' => 'Something went bad :(' ) );
                }
                die();
            }
        }

        throw new \Exception("You cant load this action without post", 1);
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
