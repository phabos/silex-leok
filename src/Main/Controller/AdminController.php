<?php

namespace Main\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

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
			print_r($request->getContent());
			die();
		}

		throw new Exception("You cant load this action without xhr", 1);

	}

}
