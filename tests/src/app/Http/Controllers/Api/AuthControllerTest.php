<?php

//use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;  
use Illuminate\Foundation\Testing\DatabaseMigrations;  
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthControllerTest extends TestCase {

    private $authTokenMock;

    public function setUp() {
        parent::setUp();

//        $this->createApplication();

//        $this->authTokenMock = Mockery::mock(App\Models\AuthToken::class);
//        $this->app->instance(App\Models\AuthToken::class, $this->authTokenMock);
    }
//
    public function tearDown() {
        Mockery::close();
        parent::tearDown();
    }

//     public function testRegistrationValidationWorksWithInvalidEmail() {

// 	    $registrationCredentials = [
// 	        'email' => 'sunny.3mysore-gmail.com',
//             'password' => 'testing123',
//             'name' => 'Testing',
//         ];

// 	    $this->post('api/register', $registrationCredentials)
//             ->seeJson([
//                 'status' => 0
//             ]);
//     }

//     public function testRegistrationValidationWorksWithDuplicateEmail() {

//         $registrationCredentials = [
//             'email' => 'sunny.3mysore@gmail.com',
//             'password' => 'testing123',
//             'name' => 'Testing',
//         ];

//         $this->post('api/register', $registrationCredentials)
//             ->seeJson([
//                 'status' => 0
//             ]);
//     }

//     public function testRegistrationValidationWorksInvalidPassword() {

//         $registrationCredentials = [
//             'email' => 'sunny123@gmail.com',
//             'password' => '123',
//             'name' => 'Testing',
//         ];

//         $this->post('api/register', $registrationCredentials)
//             ->seeJson([
//                 'status' => 0
//             ]);
//     }

//     public function testRegistrationValidationWorksInvalidName() {

//         $registrationCredentials = [
//             'email' => 'sunny123@gmail.com',
//             'password' => 'testing123',
//         ];

//         $this->post('api/register', $registrationCredentials)
//             ->seeJson([
//                 'status' => 0
//             ]);
//     }

//     public function testRegistrationValidationFailsHasStructure() {

//         $registrationCredentials = [
//             'email' => 'sunny123@gmail.com',
//             'password' => '123',
//         ];

//         $this->post('api/register', $registrationCredentials)
//             ->seeJsonStructure([
//                 'status',
//                 'error' => [
//                     'message'
//                 ]
//             ]);
//     }

//     public function testRegistrationWithValidCredentialsHasStatus() {

//         $registrationCredentials = [
//             'email' => 'sunny1@gmail.com',
//             'password' => 'testing123',
//             'name' => 'Testing'
//         ];

//         $this->post('api/register', $registrationCredentials)
//             ->seeJson([
//                 'status' => 1,
//             ]);
//     }

//     public function testRegistrationWithValidCredentialsHasStructure() {

//         $registrationCredentials = [
//             'email' => 'sunny2@gmail.com',
//             'password' => 'testing123',
//             'name' => 'Testing'
//         ];

//         $this->post('api/register', $registrationCredentials)
//             ->seeJsonStructure([
//                 'status',
//                 'data' => [
//                     'user' => [
//                         'name', 'email', 'created_at', 'id', 'user_group'
//                     ]
//                 ]
//             ]);
//     }

//     public function testRegistrationWithValidCredentialsHasStatusCode() {

//         $registrationCredentials = [
//             'email' => 'sunny3@gmail.com',
//             'password' => 'testing123',
//             'name' => 'Testing'
//         ];

//         $this->post('api/register', $registrationCredentials)
//             ->assertResponseStatus(200);
//     }

//     public function testRegistrationWithInvalidCredentialsHasStatusCode() {

//         $registrationCredentials = [
//             'email' => 'sunny3gmail.com',
//             'password' => 'testing123',
//             'name' => 'Testing'
//         ];

//         $this->post('api/register', $registrationCredentials)
//             ->assertResponseStatus(422);
//     }

//     public function testLoginWithInvalidCredentialsStructure() {

// 	    $loginCredentials = [
//             'email' => 'sunny.3mysore@gmail.com1',
//             'password' => 'testing123'
//         ];

//         $this->post('/api/login', $loginCredentials)
//             ->seeJsonStructure([
//                 'status',
//                 'error' => [
//                     'message',
//                 ]
//             ]);
//     }

//     public function testLoginWithInvalidCredentialsHasStatus() {

//         $loginCredentials = [
//             'email' => 'sunny.3mysore@gmail.com1',
//             'password' => 'testing123'
//         ];

//         $this->post('/api/login', $loginCredentials)
//             ->seeJson([
//                 'status' => 0,
//             ]);
//     }

//     public function testLoginCredentialsValidationWorksWithInvalidEmail() {

// 	    $loginCredentials = [
// 	        'email' => 'sunny.3mysore-gmail.com',
//             'password' => 'testing123'
//         ];

//         $this->post('/api/login', $loginCredentials)
//             ->seeJson([
//                 'status' => 0,
//             ]);
//     }

//     public function testLoginCredentialsValidationWorksWithInvalidPassword() {

//         $loginCredentials = [
//             'email' => 'sunny.3mysore@gmail.com',
//             'password' => '123'
//         ];

//         $this->post('/api/login', $loginCredentials)
//             ->seeJson([
//                 'status' => 0,
//             ]);
//     }

//     public function testLoginWithValidCredentialsHasStatusKey() {

//         $loginCredentials = [
//             'email' => 'sunny.3mysore@gmail.com',
//             'password' => 'testing123'
//         ];

//         $this->post('/api/login', $loginCredentials)
//             ->seeJson([
//                 'status' => 1,
//             ]);
//     }

//     public function testLoginWithValidCredentialsStructure() {

//         $loginCredentials = [
//             'email' => 'sunny.3mysore@gmail.com',
//             'password' => 'testing123'
//         ];

//         $this->post('/api/login', $loginCredentials)
//             ->seeJsonStructure([
//                 'status',
//                 'data' => [
//                     'user' => [
//                         'name', 'email', 'user_group', 'id', 'created_at'
//                     ]
//                 ],
//             ]);
//     }

//     public function testLoginWithInvalidCredentialsHasStatusCode() {

//         $loginCredentials = [
//             'email' => 'sunny.3mysore-gmail.com',
//             'password' => 'testing123'
//         ];

//         $this->post('/api/login', $loginCredentials)
//             ->assertResponseStatus(422);
//     }

//     public function testLoginWithInvalidCredentialsHasStatusCodeOk() {

//         $loginCredentials = [
//             'email' => 'sunny.3mysore@gmail.com',
//             'password' => 'testing123'
//         ];

//         $this->post('/api/login', $loginCredentials)
//             ->assertResponseOk();
//     }

//     public function testLogoutWithInvalidAuthTokenHeaderHasStatus() {

// 	    $params = [];
// 	    $headers = [
// 	        'Authorization' => 'invalid_auth_token',
//         ];

// 	    $this->delete('/api/logout', $params, $headers)
//             ->seeJson([
//                 'status' => 0
//             ]);
//     }

//     public function testLogoutWithInvalidAuthTokenHeaderHasStatusCode() {

//         $params = [];
//         $headers = [
//             'Authorization' => 'invalid_auth_token',
//         ];

//         $this->delete('/api/logout', $params, $headers)
//             ->assertResponseStatus(401);
//     }

//     public function testLogoutWithInvalidAuthTokenHeaderHasStructure() {

//         $params = [];
//         $headers = [
//             'Authorization' => 'invalid_auth_token',
//         ];

//         $this->delete('/api/logout', $params, $headers)
//             ->seeJsonStructure([
//                 'status',
//                 'error' => [
//                     'message',
//                 ]
//             ]);
//     }

//     public function testLogoutWithValidAuthTokenHeaderHasStructure() {

//         $params = [];
//         $headers = [
//             'Authorization' => $this->authToken,
//         ];

// //        $this->authTokenMock->shouldReceive('invalidateAuthToken')
// //            ->once()
// //            ->with($this->authToken)
// //            ->andReturn(true);

//         $this->delete('/api/logout', $params, $headers)
//             ->seeJsonStructure([
//                 'status',
//                 'data' => [
//                     'message',
//                 ]
//             ]);
//     }

//     public function testLogoutWithValidAuthTokenHeaderHasStatus() {

//         $params = [];
//         $headers = [
//             'Authorization' => $this->authToken,
//         ];

// //        $this->authTokenMock->shouldReceive('invalidateAuthToken')
// //            ->once()
// //            ->with($this->authToken)
// //            ->andReturn(true);

//         $this->delete('/api/logout', $params, $headers)
//             ->seeJson([
//                 'status' => 1
//             ]);
//     }

//    public function testLogoutWithValidAuthTokenHeaderWithMockReturnsFalseHasStatus() {
//
//        $params = [];
//        $headers = [
//            'Authorization' => $this->authToken,
//        ];
//
////        $this->authTokenMock->shouldReceive('invalidateAuthToken')
////            ->once()
////            ->with($this->authToken)
////            ->andReturn(false);
//
//        $this->delete('/api/logout', $params, $headers)
//            ->seeJson([
//                'status' => 0
//            ]);
//    }

//    public function testLogoutWithInvalidAuthTokenHeaderWithMockHasStatus() {
//
//        $params = [];
//        $headers = [
//            'Authorization' => $this->authToken,
//        ];
//
//        $this->delete('/api/logout', $params, $headers)
//            ->seeJson([
//                'status' => 0
//            ]);
//    }

//    public function testLogoutWithInvalidAuthTokenHeaderWithMockAndExceptionHasStatus() {
//
//        $params = [];
//        $headers = [
//            'Authorization' => $this->authToken,
//        ];
//
//        $this->delete('/api/logout', $params, $headers)
//            ->seeJson([
//                'status' => 0
//            ]);
//    }

    // public function testLogoutWithoutAuthTokenHeaderHasStatus() {

    //     $params = [];
    //     $headers = [];

    //     $this->delete('/api/logout', $params, $headers)
    //         ->seeJson([
    //             'status' => 0
    //         ]);
    // }
}