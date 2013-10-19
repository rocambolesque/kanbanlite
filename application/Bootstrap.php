<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function _initRoutes()
    {
        $front = Zend_Controller_Front::getInstance();

        $router = $front->getRouter();

        $restRoute = new Zend_Rest_Route($front);
        $router->addRoute('default', $restRoute);

        $allCardsRoute = new Zend_Controller_Router_Route(
           'cards',
           array(
              'controller' => 'card',
              'action'     => 'index',
              'module'     => 'default'
           )
        );
        $router->addRoute('test', $allCardsRoute);

        $searchRoute = new Zend_Controller_Router_Route(
           'card/search',
           array(
              'controller' => 'card',
              'action'     => 'search'
           )
        );
        $router->addRoute('search', $searchRoute);
    }

    protected function _initAutoload()
    {
        $loader = new Zend_Loader_Autoloader_Resource(array(
            'basePath'  => APPLICATION_PATH,
            'namespace' => 'KanbanLite',
        ));

        $loader->addResourceType('model', 'models/', 'Model');
        $loader->addResourceType('controller', 'controllers/', 'Controller');

        return $loader;
    } 
}
