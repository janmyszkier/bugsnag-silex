<?php

namespace Bugsnag\Silex\Tests\Request;


use Bugsnag\Client;
use Bugsnag\Report;
use Silex\Application;
use GrahamCampbell\TestBenchCore\MockeryTrait;
use PHPUnit_Framework_TestCase as TestCase;
use Mockery;

use Bugsnag\Silex\AbstractServiceProvider;

class RequestStub {
    const MIDDLEWARE_HANDLER = "middleware_handler";
}

class AutoNotifyTest extends TestCase
{
    use MockeryTrait;

    public function testSilex1AutoNotify()
    {
        $phpVersion56 = version_compare(PHP_VERSION, "5.6", ">=");
        if ($phpVersion56) $this->markTestSkipped("Silex1 only tested in version 5.5.9");

        $Silex1ServiceProvider = "Bugsnag\Silex\Silex1ServiceProvider";

        # Create mocks
        $report = Mockery::namedMock(Report::class, RequestStub::class);
        $client = Mockery::mock(Client::class);
        $app = Mockery::mock(Application::class);

        # Create test objects
        $exception = new \Exception("Test");

        $app->shouldReceive('offsetSet')->with(Mockery::any(), Mockery::any())->andReturnUsing(
            function($key, $value) use ($app, $exception) {
                if ($key == 'bugsnag.notifier') {
                    $notifyFunc = call_user_func($value, $app);
                    call_user_func($notifyFunc, $exception);
                }
            }
        );
        $app->shouldReceive('share');
        $app->shouldReceive('before');
        $app->shouldReceive('offsetGet')->andReturnUsing(
            function($key) use ($client) {
                if ($key == 'bugsnag') {
                    return $client;
                }
            }
        );
        $report->shouldReceive('fromPHPThrowable')
            ->with('config', $exception, true, ['type' => 'unhandledExceptionMiddleware', 'attributes' => ['framework' => 'Silex']])
            ->once()
            ->andReturn($report);
        $client->shouldReceive('getConfig')->once()->andReturn('config');
        $client->shouldReceive('notify')->once()->with($report, Mockery::any());

        # Initiate test
        $serviceProvider = new $Silex1ServiceProvider;
        $serviceProvider->register($app);
    }

    public function testSilex2AutoNotify()
    {
        $phpVersion56 = version_compare(PHP_VERSION, "5.6", ">=");
        if (!$phpVersion56) $this->markTestSkipped("Silex2 not tested in version 5.5.9");

        $Silex2ServiceProvider = "Bugsnag\Silex\Silex2ServiceProvider";

        # Create mocks
        $report = Mockery::namedMock(Report::class, RequestStub::class);
        $client = Mockery::mock(Client::class);
        $app = Mockery::mock(Application::class);

        # Create test objects
        $exception = new \Exception("Test");

        $app->shouldReceive('offsetSet')->with(Mockery::any(), Mockery::any())->andReturnUsing(
            function($key, $value) use ($app, $exception) {
                if ($key == 'bugsnag.notifier') {
                    $notifyFunc = call_user_func($value, $app);
                    call_user_func($notifyFunc, $exception);
                }
            }
        );
        $app->shouldReceive('before');
        $app->shouldReceive('offsetGet')->andReturnUsing(
            function($key) use ($client) {
                if ($key == 'bugsnag') {
                    return $client;
                }
            }
        );
        $report->shouldReceive('fromPHPThrowable')
            ->with('config', $exception, true, ['type' => 'unhandledExceptionMiddleware', 'attributes' => ['framework' => 'Silex']])
            ->once()
            ->andReturn($report);
        $client->shouldReceive('getConfig')->once()->andReturn('config');
        $client->shouldReceive('notify')->once()->with($report, Mockery::any());

        # Initiate test
        $serviceProvider = new $Silex2ServiceProvider;
        $serviceProvider->register($app);
    }
    
}
