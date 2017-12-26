<?php

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';
    protected $eloquentModelMock, $jsonResponseStructure;
    protected $authToken = '$2y$10$1rmEA5P6OuPLeBzSRchFFuIBbBTWhhWBYgJRVPeOHMRrI5hnQY5u';
    protected $headers;

    function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->headers = $headers = [
            'Authorization' => $this->authToken,
        ];
    }

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    public function setUp() {
        parent::setUp();
        Artisan::call('migrate');
        Artisan::call('db:seed', ['--class' => 'TestDatabaseSeeder', '--database' => 'testing']);

//        $this->eloquentModelMock = Mockery::mock(Illuminate\Database\Eloquent\Model::class);
//        $this->eloquentModelMock->shouldReceive('jsonSerialize')->zeroOrMoreTimes()
//            ->withAnyArgs()->andReturn($this->jsonResponseStructure);
//        $this->eloquentModelMock->shouldReceive('save')->zeroOrMoreTimes()
//            ->withAnyArgs()->andReturn($this->eloquentModelMock);
    }

    public function tearDown() {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}