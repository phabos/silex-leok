<?php

namespace Main\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class AdminController
{

	public function indexAction(Request $request, Application $app){
		return $app['twig']->render('admin/index.html', array());
  }
}
