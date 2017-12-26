<?php

namespace App\Http\Controllers\Api;

use App\Core\CustomResponse;
use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller {

    public function index(Request $request) {

        $users = User::get();
        return CustomResponse::success([
            'users' => $users,
        ]);
    }

    public function show(Request $request, $userId) {

        $user = User::find($userId);

        if($user) {
            return CustomResponse::success([
                'user' => $user,
            ]);
        } else {
            return CustomResponse::error([
                'message' => 'User not found!'
            ], 404);
        }
    }

    public function update(Request $request, $userId) {

        $input = $request->all();

        if(isset($input['email'])) {
            unset($input['email']);
        }

        $user = User::find($userId);
        $result = $user->update($input);

        if($result) {
            return CustomResponse::success([
                'user' => $user,
            ]);
        } else {
            return CustomResponse::error([
                'message' => 'Unable to update user. Try again later.',
            ]);
        }
    }

    public function destroy(Request $request, $userId) {

        $user = User::find($userId);
        $result = $user->delete();

        if($result) {
            return CustomResponse::success([
                'message' => 'User, folders, and links deleted successfully!',
            ]);
        } else {
            return CustomResponse::error([
                'message' => 'Error deleting user. Try again later.',
            ]);
        }
    }

}