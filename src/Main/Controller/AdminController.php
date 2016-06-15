<?php

namespace Main\Controller;

use Silex\Application;
use Main\Events\UploadEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController
{

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
                $app['repository.options']->updateSettings( $request->getContent(), 'settings' );
				return $app->json( array() );
			}

			if($request->getMethod() == 'GET')
    		{
                $_settings = $app['repository.options']->getSettings( 'settings' );
    			$settings = @json_decode( $_settings['value'], true );
                return $app->json( $settings );
    		}
		}

		return $app['twig']->render( 'admin/backbone.settings.tpl.html', array() );

	}

    /*** Weather ***/
    public function weatherAction(Application $app)
    {
        $dbWeather = $app['repository.options']->getSettings( 'weather' );
        if( isset( $dbWeather['value'] ) ) {
            $w = @json_decode( $dbWeather['value'] );
            if( isset( $w->expire ) && $w->expire > date( 'Y-m-d' ) ) {
                // API Call
                return new Response($this->getWeatherApi( $app ), 200);
            } else {
                return new Response($dbWeather['value'], 200);
            }
        }

        return new Response($this->getWeatherApi( $app ), 200);

    }

    /*** Get current weather from api ***/
    private function getWeatherApi( $app )
    {
        $client = new \GuzzleHttp\Client();
        $apiWeather = $client->request('GET', 'http://api.openweathermap.org/data/2.5/weather',
            [
                'query' => [
                    'q' => 'Paris,fr',
                    'appid' => $app['weather.app']
                ]
            ]
        );
        // Surcharge la réponse
        $res = @json_decode( $apiWeather->getBody() );
        $res->expire = date( 'Y-m-d' );

        // Store res
        $app['repository.options']->updateSettings( $res, 'weather' );

        return @json_encode( $res );
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
                if( !empty( $request->query->get('count') ) )
                {
                    $count = $app['repository.article']->countArticles();
                    return $app->json( $count );
                }

                if( !empty( $request->query->get('search') ) )
                {
                    $articles = $app['repository.article']->searchArticles( $request->query->get('search') );
                    return $app->json( $articles );
                }

                $offset = (int) $request->query->get('offset') * $app['sql.limit'];
                $articles = $app['repository.article']->getArticles( $app['sql.limit'], $offset );
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
                $event = new UploadEvent();
                $event->setFileTmpName( $_FILES['myFile']['tmp_name'] );
                $event->setFileName( $_FILES['myFile']['name'] );
                $event->setUploadDir( $app['upload.path'] );
                $event->setUploadPath( $app['webroot.path'] );
                $res = $app['notifyService']->dispatch( UploadEvent::FILE_UPLOAD, $event );
                return $app->json( $res->getResponse() );
            }
        }

        throw new \Exception("You cant load this action without post", 1);
    }

}
