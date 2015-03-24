<?php

require __DIR__."/vendor/autoload.php";

use twiger\Router as Router;
use twiger\Twiger as Twiger;

//Including all Controllers
foreach (glob(__DIR__.'/src/control/*.php') as $filename){
	require $filename;
}

$routes = Spyc::YAMLLoad('app/config/routing.yml');

$constants = Spyc::YAMLLoad('app/config/constants.yml');

$config = Spyc::YAMLLoad('app/config/config.yml');

$requestUri = $_SERVER['REQUEST_URI'];

$router = new Router($routes);
$route = $router->handle($requestUri);


if (!is_null($route) && !isset($route['params']))
	$route['params'] = array();
if (!is_null($route) && !isset($route['route_params']))
	$route['route_params'] = array();

$constants['route'] = $route;

$assets = new Twig_SimpleFunction('assets', function ($path) {
	$config = Spyc::YAMLLoad('app/config/config.yml');
	if(isset($config['assets']))
		return '/'.$config['assets'].'/'.$path;
	else
		return '/'.$path;
});

$path = new Twig_SimpleFunction('path', function ($route, $args = null) {
	$routes = Spyc::YAMLLoad('app/config/routing.yml');
	if( isset($routes[$route]) )
		$route = $routes[$route];
	else
		throw new Twig_Error_Path('No route found for '.$route);

	$path = $route['pattern'];

	if (!empty($args))
		foreach ($args as $key => $value)
			$path = preg_replace('/{'.$key.'}/', $value, $path);

	return $path;
});

if (!is_null($route)){
	if (isset($route['controller'])) {
		
		$controlling = explode('::',$route['controller']);

		$controller = $controlling[0];
		$controller = 'control\\'.$controller;
		$controller = new $controller( array_merge($constants, $route['params']) );
		$controller->addFunctions(array($assets, $path));
		$method = $controlling[1];

		call_user_func_array(array($controller, $method), $route['route_params']);
		
	}elseif (isset($route['template'])) {

		$twiger = new Twiger( array_merge($constants, $route['params']) );
		$twiger->addFunctions(array($assets, $path));
		$twiger->render($route['template'].'.html.twig', $route['route_params'] );

	}
}else{
	$twiger = new Twiger();
	$twiger->addFunctions(array($assets, $path));
	$twiger->render('404.html.twig', array('route' => $requestUri));
	http_response_code(404);
}
