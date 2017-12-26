<?php
/**
 * Created by PhpStorm.
 * User: srinidhi
 * Date: 15/2/17
 * Time: 2:57 PM
 */

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FolderControllerTest extends TestCase {

    private $params, $folderRepoMock;

    function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->jsonResponseStructure = ['id', 'folder_name', 'user_id', 'created_at', 'updated_at'];

        parent::__construct($name, $data, $dataName);
    }

    public function setUp() {
        parent::setUp();

        $this->params = [
            'folder_name' => 'Test Folder',
            'user_id' => '$2y$10$1rmEA5P6OuPLeBzSRchFFuIBbBTWhhWBYgJRVPeOHMRrI5hnQY5u',
            'id' => '$2y$10$1rmEA5P6OuPLeBzSRchFFuIBbBTWhhWBYgJRVPeOHMRrI5hnQY5u',
        ];

//        $this->folderRepoMock = Mockery::mock(\App\Interfaces\FolderRepoInterface::class);
//        $this->app->instance(\App\Interfaces\FolderRepoInterface::class, $this->folderRepoMock);

//        $this->folderRepoMock->shouldReceive('getUserFolders')->with('1')
//            ->zeroOrMoreTimes()->andReturn([]);

//        $this->folderRepoMock->shouldReceive('saveUserFolder')
//            ->withAnyArgs()
//            ->zeroOrMoreTimes()
//            ->andReturn($this->eloquentModelMock);
    }

    public function tearDown() {
        Mockery::close();
        parent::tearDown();
    }

    // public function testFoldersListWithoutHeaderHasCode() {

    //     $this->get('/api/folder', [])
    //         ->seeJson([
    //             'status' => 0,
    //         ]);
    // }

    // public function testFoldersListHasCode() {

    //     $this->get('/api/folder', $this->headers)
    //         ->seeJson([
    //             'status' => 1,
    //         ]);
    // }

    // public function testSaveFolderWithInvalidInputHasCode() {

    //     unset($this->params['folder_name']);

    //     $this->post('/api/folder', $this->params, $this->headers)
    //         ->seeJson([
    //             'status' => 0,
    //         ]);
    // }

    // public function testSaveFolderWithInvalidUserIdHasCode() {

    //     $this->params['user_id'] = 'SampleUserId';

    //     $this->post('/api/folder', $this->params, $this->headers)
    //         ->seeJson([
    //             'status' => 0,
    //         ]);
    // }

    // public function testSaveFolderWithValidInputHasCode() {

    //     $this->post('/api/folder', $this->params, $this->headers)
    //         ->seeJson([
    //             'status' => 1,
    //         ]);
    // }

    // public function testSaveFolderWithValidInputHasStructure() {

    //     $this->post('/api/folder', $this->params, $this->headers)
    //         ->seeJsonStructure([
    //             'status',
    //             'data' => [
    //                 'folder' => [
    //                     'id', 'folder_name', 'user_id', 'created_at', 'updated_at'
    //                 ]
    //             ]
    //         ]);
    // }

    // public function testSaveFolderWithValidInputHasUserId() {

    //     $this->post('/api/folder', $this->params, $this->headers);
    //     $response = json_decode($this->response->getContent(), true);

    //     $this->assertEquals($this->params['user_id'], $response['data']['folder']['user_id']);
    // }
}