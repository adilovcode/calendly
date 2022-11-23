<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Mockery\MockInterface;

abstract class TestCase extends BaseTestCase {
    use CreatesApplication;

    /**
     * @param string $className
     * @return void
     */
    public function mockInstance(string $className): void {
        $this->app->instance($className, \Mockery::mock($className));
    }

    /**
     * @param string $className
     * @return MockInterface
     */
    public function mocked(string $className): MockInterface {
        return $this->app->make($className);
    }
}
