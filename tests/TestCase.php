<?php

namespace ImLiam\UniqueGmailAddress\Tests;

use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        require_once __DIR__ . '/database/migrations/create_emails_table.php';
        (new \CreateEmailsTable())->up();
    }
}
