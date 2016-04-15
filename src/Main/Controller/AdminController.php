<?php

namespace Main\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Main\Repository\OptionsRepository;

class AdminController
{

	public function indexAction(Request $request, Application $app)
	{
		return $app['twig']->render('admin/index.html', array());
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

		throw new \Exception("You cant load this action without xhr", 1);

	}

    /*** Articles page ***/
    public function articlesAction(Request $request, Application $app)
    {
        if($request->isXmlHttpRequest())
        {
            if($request->getMethod() == 'GET')
            {
                $articles = $app['repository.article']->getArticles( 15 );
                return $app->json( $articles );
            }
        }

        throw new \Exception("You cant load this action without xhr", 1);

    }

    /*** Article Edit ***/
    public function getArticleAction(Request $request, Application $app)
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

        throw new \Exception("You cant load this action without xhr", 1);
    }

    /*** Send Images method ***/
    public function sendImagesAction(Request $request, Application $app)
    {
        if($request->getMethod() == 'POST')
        {
            if (isset($_FILES['myFile'])) {
                // Si le dossier image n'existe pas on le crée format -> m-Y
                $currentDate = new \Datetime();
                $uploadDir = $app['upload.path'] . $currentDate->format( 'm-Y' );
                if( ! is_dir( $uploadDir ) ) {
                  if ( mkdir( $uploadDir, 0775, true ) ) {
                    chmod( $uploadDir, 0775 );
                  }else{
                    return $app->json( array( 'msg' => 'impossible to create upload folder' ) );
                  }
                }

                if( move_uploaded_file($_FILES['myFile']['tmp_name'], $uploadDir . '/' . $_FILES['myFile']['name']) )
                    return $app->json( array( 'imageUrl' => $app['webroot.path'] . $currentDate->format( 'm-Y' ) . '/' . $_FILES['myFile']['name'], 'msg' => 'Upload réussi' ) );
                else
                    return $app->json( array( 'imageUrl' => '', 'msg' => 'Something went bad :(' ) );
                die();
            }
        }

        throw new \Exception("You cant load this action without post", 1);
    }

}
