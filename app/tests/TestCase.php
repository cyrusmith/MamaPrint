<?php

use Illuminate\Support\Facades\Artisan;

class TestCase extends Illuminate\Foundation\Testing\TestCase
{

    protected $useDb = false;

    /**
     * Creates the application.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
        $unitTesting = true;
        $testEnvironment = 'testing';
        return require __DIR__ . '/../../bootstrap/start.php';
    }

    public function setUp()
    {
        parent::setUp(); // Don't forget this!

        $this->prepareForTests();

    }

    public function tearDown()
    {
        parent::tearDown(); // Don't forget this!
        Mockery::close();
        if ($this->useDb) {
            Artisan::call('migrate:reset');
        }
    }

    private function prepareForTests()
    {

        if ($this->useDb) {
            Artisan::call('migrate');
            Artisan::call('db:seed');
        }
        //DatabaseSeeder::seedRoles();
        Mail::pretend(true);
    }

}
