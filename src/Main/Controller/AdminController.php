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
				return $app->json( array( 'success' => 'true', 'msg' => 'Settings mis à jour !' ) );
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
                return $app->json( $article );
            }

            if($request->getMethod() == 'PUT')
            {
                $id = $request->attributes->get('id');
                $datas = @json_decode( $request->getContent(), true );
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
                if( move_uploaded_file($_FILES['myFile']['tmp_name'], $app['upload.path'] . $_FILES['myFile']['name']) )
                    return $app->json( array( 'imageUrl' => $app['webroot.path'] . $_FILES['myFile']['name'], 'msg' => 'Upload réussi' ) );
                else
                    return $app->json( array( 'imageUrl' => '', 'msg' => 'Something went bad :(' ) );
                die();
            }
        }

        throw new \Exception("You cant load this action without post", 1);
    }

}
