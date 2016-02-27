<?php

	namespace Main\Controller;

	use Silex\Application;
	use Symfony\Component\HttpFoundation\Request;

  class ArticlesController
	{

	    public function indexAction( Application $app )
	    {
	    	return $app['twig']->render( 'main.html', array( 'test '=> 'Hello' ) );
	    }

	}
