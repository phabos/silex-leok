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

	$app->register(new Silex\Provider\SecurityServiceProvider());
	$app->register(new Silex\Provider\RememberMeServiceProvider());

	$app->register(new Silex\Provider\SessionServiceProvider());

	$app->before(function (Symfony\Component\HttpFoundation\Request $request, Silex\Application $app) {
    $app['twig']->addGlobal( 'current_page_name', $request->get( "_route" ) );
    $app['twig']->addGlobal( 'base_url', $app['base.url'] );
	});

	$app->register(new Silex\Provider\DoctrineServiceProvider());

	/***************** REPO ********************/

	$app['repository.options'] = $app->share(function ($app) {
		return new Main\Repository\OptionsRepository($app['db']);
	});
