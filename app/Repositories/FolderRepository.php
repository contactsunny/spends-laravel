<?php
/**
 * Created by PhpStorm.
 * User: srinidhi
 * Date: 16/2/17
 * Time: 10:23 AM
 */

namespace App\Repositories;

use App\Interfaces\FolderRepoInterface;
use App\Models\Folder;

class FolderRepository implements FolderRepoInterface {

    public function getUserFolders($userId) {
        $folders = Folder::where('user_id', $userId)->get();
        return $folders;
    }

    public function saveUserFolder($userId, $folderData) {
        $folderData['user_id'] = $userId;

        $folder = new Folder($folderData);
        $result = $folder->save();

        if($result) {
            return $folder;
        } else {
            return $result;
        }
    }

    public function updateUserFolder($folderId, $folderData) {

        $folder = Folder::find($folderId);

        if($folder) {
            return $folder->update($folderData);
        }

        return false;
    }

    public function deleteUserFolder($folderId) {

        if(Folder::find($folderId)->delete()) {
            return true;
        }

        return false;
    }

    public function checkUserFolderExists($userId, $folderId, $folderName) {

        $existingFolder = Folder::where('id', '!=', $folderId)
                                ->where('user_id', $userId)
                                ->where('folder_name', $folderName)
                                ->first();

        if($existingFolder) {
            return true;
        }

        return false;
    }
}