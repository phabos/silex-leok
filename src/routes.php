<?php

  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\HttpFoundation\Request;
  use Silex\Application;

	$app->get('/', 'Main\Controller\ArticlesController::indexAction')
	    ->bind('homepage');

  $app->get('/api/article.json/', 'Main\Controller\ArticlesController::articlesListAction')
    	->bind('articleslist');

  $app->get('/login', 'Main\Controller\UsersController::loginAction')
      ->bind('userlogin');

  $app->get('/admin', 'Main\Controller\AdminController::indexAction')
      ->bind('adminindex');

  $app->get('/admin/articles', 'Main\Controller\AdminController::articlesAction')
      ->bind('adminarticles');

  $app->match('/admin/article/{id}', 'Main\Controller\AdminController::getArticleAction')
      ->convert('id', function ($id) use ($app) { if((int) $id){ return (int) $id; }});

  $app->match('/admin/settings', 'Main\Controller\AdminController::settingsAction')
      ->bind('adminsettings');

  $app->match('/admin/send-images', 'Main\Controller\AdminController::sendImagesAction')
      ->bind('adminsendimages');

	$app->error(function (\Exception $e, $code) use ($app) {
		if ($app['debug']) {
			return;
		}

		// 404.html, or 40x.html, or 4xx.html, or error.html
		$templates = array(
			'errors/error.html',
		);
		return new Response($app['twig']->resolveTemplate($templates)->render(array('base_url' => $app['base.url'] , 'code' => $code, 'message' => $e->getMessage())), $code);
	});
