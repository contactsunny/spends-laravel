<?php
/**
 * Created by PhpStorm.
 * User: srinidhi
 * Date: 16/2/17
 * Time: 10:38 AM
 */

namespace App\Interfaces;


interface FolderRepoInterface
{
    public function getUserFolders($userId);
    public function saveUserFolder($userId, $folderData);
    public function updateUserFolder($folderId, $folderData);
    public function deleteUserFolder($folderId);
    public function checkUserFolderExists($userId, $folderId, $folder_name);
}