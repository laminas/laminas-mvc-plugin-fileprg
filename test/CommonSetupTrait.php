<?php
/**
 * @link      http://github.com/zendframework/zend-mvc-plugin-fileprg for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Mvc\Plugin\FilePrg;

use Zend\Form\Element\Collection;
use Zend\Form\Form;
use Zend\Http\Request;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Plugin\FilePrg\FilePostRedirectGet;
use Zend\Router\Http\Literal as LiteralRoute;
use Zend\Router\Http\Segment as SegmentRoute;
use Zend\Router\RouteMatch;
use Zend\Router\SimpleRouteStack;

trait CommonSetupTrait
{
    public $form;
    public $controller;
    public $event;
    public $plugin;
    public $request;
    public $response;
    public $collection;

    public function setUp()
    {
        $this->form = new Form();

        $this->collection = new Collection('links', [
                'count' => 1,
                'allow_add' => true,
                'target_element' => [
                    'type' => TestAsset\LinksFieldset::class,
                ],
        ]);

        $router = new SimpleRouteStack;
        $router->addRoute('home', LiteralRoute::factory([
            'route'    => '/',
            'defaults' => [
                'controller' => TestAsset\SampleController::class,
            ]
        ]));

        $router->addRoute('sub', SegmentRoute::factory([
            'route' => '/foo/:param',
            'defaults' => [
                'param' => 1
            ]
        ]));

        $router->addRoute('ctl', SegmentRoute::factory([
            'route' => '/ctl/:controller',
            'defaults' => [
                '__NAMESPACE__' => 'ZendTest\Mvc\Controller\TestAsset',
            ]
        ]));

        $this->controller = new TestAsset\SampleController();
        $this->request    = new Request();
        $this->event      = new MvcEvent();
        $this->routeMatch = new RouteMatch(['controller' => 'controller-sample', 'action' => 'postPage']);

        $this->event->setRequest($this->request);
        $this->event->setRouteMatch($this->routeMatch);
        $this->event->setRouter($router);

        $this->controller->setEvent($this->event);

        $this->plugin = new FilePostRedirectGet();
        $this->plugin->setController($this->controller);
    }
}
