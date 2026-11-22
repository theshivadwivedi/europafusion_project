<?php

namespace Tests;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Str;

abstract class TestCase extends BaseTestCase
{
    /** Throw-away folder for the leads and admin accounts written by one test. */
    protected string $storageDir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->storageDir = storage_path('framework/testing/'.Str::random(16));

        config([
            'site.leads_path' => $this->storageDir.'/leads',
            'auth.providers.users.path' => $this->storageDir.'/admins.json',
        ]);
    }

    protected function tearDown(): void
    {
        (new Filesystem)->deleteDirectory($this->storageDir);

        parent::tearDown();
    }
}
