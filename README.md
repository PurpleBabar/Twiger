# Twiger
A dead simple framework for intwigation

Twiger is a standalone implementation of twig to allow you to integrate your website and make it 100% ready to import in symfony.

## Install

To install Twiger just open a terminal and install via composer with : *php composer create-project purplebabar/twiger nameOfYourProject*. Your server will have to point in your folder, the app.php file will do the rest ;).

## Config

In the app/config/config.yml, you can configure the folder where your assets are stored, just fill it with 
```yaml
assets: nameOfYourFolder
```

## Functions

Two functions are added to the dafualts functions of twig:

  ### assets(path)
    The assets function return the path to the assets folder, just add the end of the path. (ex.: yourassets folder is named *assets*, the functino will return you */assets/*
  ### path(routeName, args)
    The path function return the patter of the route filled with the args you gae in args.

## Templates

Your templates must be located in the *src/templates* directory

## Routing

You have different choice regarding the routing. You can either define a controller or a template for direct rendering.

  ### template
  ```yaml
  second:
    pattern: /foo
    template: bar
  ```
  This syntax will render automatically the bar.html.twig template located in your template directory when /foo will be triggered on your server
  
  ### controller
  ```yaml
  home:
    pattern: /foo/{bar}
    controller: IndexController::foo
  ```
  This syntax will call the foo function of your IndexController, located in the *src/control* of your project.The parameter bar will be transmitted to the function.

## Controllers
 Your controllers must be defined as follow :
```php
<?php

namespace control;

require_once __DIR__."/../../vendor/autoload.php";

use twiger\Twiger as Twiger;

class IndexController extends Twiger{

	public function foo($bar){

		return $this->render('bar.html.twig', array('bar' => $bar));
	}
}
```


That's it, you're ready to use Twiger, make it Roar ;)
