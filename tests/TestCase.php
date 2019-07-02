<?php

namespace Tests;

use WS\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function signIn($user = null)
    {
        $this->be($user ?? factory(User::class)->create());
        return $this;
    }
}
