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

	public function settingsAction(Request $request, Application $app)
	{
		if($request->isXmlHttpRequest())
		{
    		if($request->getMethod() == 'POST')
    		{
    			sleep(1);
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

		throw new Exception("You cant load this action without xhr", 1);

	}

}
