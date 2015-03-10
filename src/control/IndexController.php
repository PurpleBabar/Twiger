<?php

namespace control;

require_once __DIR__."/../../vendor/autoload.php";

use twiger\Twiger as Twiger;

class IndexController extends Twiger{

	public function home($name){

		return $this->render('home.html.twig', array('name' => $name));
	}
}