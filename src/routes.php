<?php

  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\HttpFoundation\Request;
  use Silex\Application;

	$app->get('/', 'Main\Controller\ArticlesController::indexAction')
	    ->bind('homepage');

  $app->get('/api/article.json/', 'Main\Controller\ArticlesController::articlesListAction')
    	->bind('articleslist');

	$app->error(function (\Exception $e, $code) use ($app) {
		/*if ($app['debug']) {
			return;
		}*/

		// 404.html, or 40x.html, or 4xx.html, or error.html
		$templates = array(
			'errors/error.html',
		);
		return new Response($app['twig']->resolveTemplate($templates)->render(array('base_url' => $app['base.url'] , 'code' => $code, 'message' => $e->getMessage())), $code);
	});
