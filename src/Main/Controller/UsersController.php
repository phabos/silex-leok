<?php

namespace Main\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class UsersController
{

	public function loginAction(Request $request, Application $app)
  {
    return $app['twig']->render('login.html', array(
	      'error'         => $app['security.last_error']($request),
	      'last_username' => $app['session']->get('_security.last_username'),
    ));
  }

}
