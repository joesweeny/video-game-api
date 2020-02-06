<?php

namespace App;

use Laravel\Lumen\Testing\TestCase;

class IntegrationTestCase extends TestCase
{
    /**
     * @inheritDoc
     */
    public function createApplication()
    {
        return $this->app;
    }
}
