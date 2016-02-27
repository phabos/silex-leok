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

      public function articlesListAction( Application $app )
      {
        $content = file_get_contents( __DIR__.'/../bouchon.js' );
        echo $content;
        die();
        //return $app->json( $content );
      }

	}
