<?php

	if($app['debug']){
		$app->register(new Silex\Provider\MonologServiceProvider(), $app['monolog_config']);
	}

	$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

	$app->register(new Silex\Provider\TwigServiceProvider(), array(
		'twig.options' => array(
			'cache' => isset($app['twig.options.cache']) ? $app['twig.options.cache'] : false,
			'strict_variables' => true,
		),
		'twig.path' => $app['twig.path']
	));

	$app->before(function (Symfony\Component\HttpFoundation\Request $request, Silex\Application $app) {
    $app['twig']->addGlobal( 'current_page_name', $request->get( "_route" ) );
    $app['twig']->addGlobal( 'base_url', $app['base.url'] );
	});

	/***************** REPO ********************/

	/*$app['repository.content'] = $app->share(function ($app) {
		return new TfJass\Repository\ContentRepository($app['db']);
	});*/
