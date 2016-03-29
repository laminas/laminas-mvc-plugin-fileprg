<?php
/**
 * @link      http://github.com/zendframework/zend-mvc-plugin-fileprg for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Mvc\Plugin\FilePrg;

use PHPUnit_Framework_TestCase as TestCase;
use Zend\Form\Form;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Router\Exception\RuntimeException;
use Zend\Router\SimpleRouteStack;
use Zend\Stdlib\Parameters;

class FilePostRedirectGetTest extends TestCase
{
    use CommonSetupTrait;

    public function testReturnsFalseOnIntialGet()
    {
        $result    = $this->controller->dispatch($this->request, $this->response);

        $plugin = $this->plugin;
        $this->assertFalse($plugin($this->form, 'home'));
    }

    public function testRedirectsToUrlOnPost()
    {
        $this->request->setMethod('POST');
        $this->request->setPost(new Parameters([
            'postval1' => 'value'
        ]));

        $this->controller->dispatch($this->request, $this->response);

        $plugin = $this->plugin;
        $prgResultUrl = $plugin($this->form, '/test/getPage', true);

        $this->assertInstanceOf(Response::class, $prgResultUrl);
        $this->assertTrue($prgResultUrl->getHeaders()->has('Location'));
        $this->assertEquals('/test/getPage', $prgResultUrl->getHeaders()->get('Location')->getUri());
        $this->assertEquals(303, $prgResultUrl->getStatusCode());
    }

    public function testRedirectsToRouteOnPost()
    {
        $this->request->setMethod('POST');
        $this->request->setPost(new Parameters([
            'postval1' => 'value1'
        ]));

        $this->controller->dispatch($this->request, $this->response);

        $plugin = $this->plugin;
        $prgResultRoute = $plugin($this->form, 'home');

        $this->assertInstanceOf(Response::class, $prgResultRoute);
        $this->assertTrue($prgResultRoute->getHeaders()->has('Location'));
        $this->assertEquals('/', $prgResultRoute->getHeaders()->get('Location')->getUri());
        $this->assertEquals(303, $prgResultRoute->getStatusCode());
    }

    public function testThrowsExceptionOnRouteWithoutRouter()
    {
        $controller = $this->controller;
        $controller = $controller->getEvent()->setRouter(new SimpleRouteStack);

        $this->request->setMethod('POST');
        $this->request->setPost(new Parameters([
            'postval1' => 'value'
        ]));

        $this->controller->dispatch($this->request, $this->response);
        $plugin = $this->plugin;

        $this->setExpectedException(RuntimeException::class);
        $prgResultRoute = $plugin($this->form, 'some/route');
    }

    public function testNullRouteUsesMatchedRouteName()
    {
        $this->controller->getEvent()->getRouteMatch()->setMatchedRouteName('home');

        $this->request->setMethod('POST');
        $this->request->setPost(new Parameters([
            'postval1' => 'value1'
        ]));

        $this->controller->dispatch($this->request, $this->response);

        $plugin = $this->plugin;
        $prgResultRoute = $plugin($this->form);

        $this->assertInstanceOf(Response::class, $prgResultRoute);
        $this->assertTrue($prgResultRoute->getHeaders()->has('Location'));
        $this->assertEquals('/', $prgResultRoute->getHeaders()->get('Location')->getUri());
        $this->assertEquals(303, $prgResultRoute->getStatusCode());
    }

    public function testReuseMatchedParameters()
    {
        $this->controller->getEvent()->getRouteMatch()->setMatchedRouteName('sub');

        $this->request->setMethod('POST');
        $this->request->setPost(new Parameters([
            'postval1' => 'value1'
        ]));

        $this->controller->dispatch($this->request, $this->response);

        $plugin = $this->plugin;
        $prgResultRoute = $plugin($this->form);

        $this->assertInstanceOf(Response::class, $prgResultRoute);
        $this->assertTrue($prgResultRoute->getHeaders()->has('Location'));
        $this->assertEquals('/foo/1', $prgResultRoute->getHeaders()->get('Location')->getUri());
        $this->assertEquals(303, $prgResultRoute->getStatusCode());
    }

    public function testReturnsPostOnRedirectGet()
    {
        // Do POST
        $params = [
            'postval1' => 'value'
        ];
        $this->request->setMethod('POST');
        $this->request->setPost(new Parameters($params));

        $this->form->add([
            'name' => 'postval1'
        ]);

        $this->controller->dispatch($this->request, $this->response);

        $plugin = $this->plugin;
        $prgResultUrl = $plugin($this->form, '/test/getPage', true);

        $this->assertInstanceOf(Response::class, $prgResultUrl);
        $this->assertTrue($prgResultUrl->getHeaders()->has('Location'));
        $this->assertEquals('/test/getPage', $prgResultUrl->getHeaders()->get('Location')->getUri());
        $this->assertEquals(303, $prgResultUrl->getStatusCode());

        // Do GET
        $this->request = new Request();
        $this->controller->dispatch($this->request, $this->response);
        $prgResult = $plugin($this->form, '/test/getPage', true);

        $this->assertEquals($params, $prgResult);
        $this->assertEquals($params['postval1'], $this->form->get('postval1')->getValue());

        // Do GET again to make sure data is empty
        $this->request = new Request();
        $this->controller->dispatch($this->request, $this->response);
        $prgResult = $plugin($this->form, '/test/getPage', true);

        $this->assertFalse($prgResult);
    }

    public function testAppliesFormErrorsOnPostRedirectGet()
    {
        // Do POST
        $params = [];
        $this->request->setMethod('POST');
        $this->request->setPost(new Parameters($params));

        $this->form->add([
            'name' => 'postval1'
        ]);
        $inputFilter = new InputFilter();
        $inputFilter->add([
            'name'     => 'postval1',
            'required' => true,
        ]);
        $this->form->setInputFilter($inputFilter);

        $this->controller->dispatch($this->request, $this->response);

        $plugin = $this->plugin;
        $prgResultUrl = $plugin($this->form, '/test/getPage', true);

        $this->assertInstanceOf(Response::class, $prgResultUrl);
        $this->assertTrue($prgResultUrl->getHeaders()->has('Location'));
        $this->assertEquals('/test/getPage', $prgResultUrl->getHeaders()->get('Location')->getUri());
        $this->assertEquals(303, $prgResultUrl->getStatusCode());

        // Do GET
        $this->request = new Request();
        $this->controller->dispatch($this->request, $this->response);
        $prgResult = $plugin($this->form, '/test/getPage', true);
        $messages  = $this->form->getMessages();

        $this->assertEquals($params, $prgResult);
        $this->assertNotEmpty($messages['postval1']['isEmpty']);
    }

    public function testReuseMatchedParametersWithSegmentController()
    {
        $expects = '/ctl/sample';
        $this->request->setMethod('POST');
        $this->request->setUri($expects);
        $this->request->setPost(new Parameters([
            'postval1' => 'value1'
        ]));

        $routeMatch = $this->event->getRouter()->match($this->request);
        $this->event->setRouteMatch($routeMatch);

        $moduleRouteListener = new ModuleRouteListener;
        $moduleRouteListener->onRoute($this->event);

        $this->controller->dispatch($this->request, $this->response);

        $plugin = $this->plugin;
        $prgResultRoute = $plugin($this->form);

        $this->assertInstanceOf(Response::class, $prgResultRoute);
        $this->assertTrue($prgResultRoute->getHeaders()->has('Location'));
        $this->assertEquals(
            $expects,
            $prgResultRoute->getHeaders()->get('Location')->getUri(),
            'redirect to the same url'
        );
        $this->assertEquals(303, $prgResultRoute->getStatusCode());
    }

    public function testCollectionInputFilterIsInitializedBeforePluginRetrievesIt()
    {
        $fieldset = new TestAsset\InputFilterProviderFieldset();
        $collectionSpec = [
            'name' => 'test_collection',
            'type' => 'collection',
            'options' => [
                'target_element' => $fieldset
            ],
        ];

        $form = new Form();
        $form->add($collectionSpec);

        $postData = [
            'test_collection' => [
                [
                    'test_field' => 'foo'
                ],
                [
                    'test_field' => 'bar'
                ]
            ]
        ];

        // test POST
        $request = new Request();
        $request->setMethod('POST');
        $request->setPost(new Parameters($postData));
        $this->controller->dispatch($request, $this->response);

        $plugin = $this->plugin;
        $plugin($form, '/someurl', true);

        $data = $form->getData();

        $this->assertArrayHasKey(0, $data['test_collection']);
        $this->assertArrayHasKey(1, $data['test_collection']);

        $this->assertSame('FOO', $data['test_collection'][0]['test_field']);
        $this->assertSame('BAR', $data['test_collection'][1]['test_field']);

        // now test GET with a brand new form instance
        $form = new Form();
        $form->add($collectionSpec);

        $request = new Request();
        $this->controller->dispatch($request, $this->response);

        $plugin($form, '/someurl', true);

        $data = $form->getData();

        $this->assertArrayHasKey(0, $data['test_collection']);
        $this->assertArrayHasKey(1, $data['test_collection']);

        $this->assertSame('FOO', $data['test_collection'][0]['test_field']);
        $this->assertSame('BAR', $data['test_collection'][1]['test_field']);
    }
}
