<?php
/**
 * Created by PhpStorm.
 * User: srinidhi
 * Date: 16/2/17
 * Time: 10:40 AM
 */

namespace App\Services;

use App\Interfaces\FolderRepoInterface;

class FolderService
{
    private $folderRepo;
    private $linkService;

    function __construct(FolderRepoInterface $folderRepo, LinkService $linkService) {
        $this->folderRepo = $folderRepo;
        $this->linkService = $linkService;
    }

    function getUserFolders($userId) {
        $folders = $this->folderRepo->getUserFolders($userId);
        return $folders;
    }

    function saveUserFolder($userId, $folderData) {
        $result = $this->folderRepo->saveUserFolder($userId, $folderData);
        return $result;
    }   

    public function checkUserFolderExists($userId, $folderId, $folderName) {
        $result = $this->folderRepo->checkUserFolderExists($userId, $folderId, $folderName);
        return $result;
    }

    public function updateUserFolder($folderId, $folderData) {
        $result = $this->folderRepo->updateUserFolder($folderId, $folderData);
        return $result;
    }

    public function deleteUserFolder($folderId) {

        $result = $this->linkService->deleteLinksInFolder($folderId);
        $result = $this->folderRepo->deleteUserFolder($folderId);
        return $result;
    }
}