<?php
/**
 * Created by PhpStorm.
 * User: srinidhi
 * Date: 20/2/17
 * Time: 10:49 AM
 */

namespace App\Services;

use App\Core\CustomResponse;
use App\Core\UuidGenerator;
use App\Interfaces\LinkRepositoryInterface;
use App\Interfaces\FolderRepoInterface;

class LinkService
{
    private $linkRepo;
    private $folderRepo;

    function __construct(LinkRepositoryInterface $linkRepository, 
        FolderRepoInterface $folderRepo) {
        $this->linkRepo = $linkRepository;
        $this->folderRepo = $folderRepo;
    }

    public function deleteLinksInFolder($folderId) {
        $result = $this->linkRepo->deleteLinksInFolder($folderId);
        return $result;
    }

    /**
     * Function to save a new link into a folder.
     * 
     * @param  [type] $linkData [description]
     * @param  [type] $folderId [description]
     * @return [type]           [description]
     */
    public function saveLink($linkData, $folderId) {
        
        /**
         * Generating a new UUID for the link.
         */
        $linkData['id'] = UuidGenerator::getNewUuid();

        /**
         * There are two ways of specifying into which folder the link has to be put.
         * Either with the folder_id parameter by providing the ID of the folder,
         * or by specifying a new folder_name, in which case, a new folder
         * will be created and the link will be saved to that folder.
         * @var [type]
         */
        if(isset($linkData['folder_name'])) {
            if($folderId == 0 && $linkData['folder_name'] != '') {
                $newFolderId = UuidGenerator::getNewUuid();
                $folder = $this->folderRepo->saveUserFolder($linkData['user_id'], [
                    'folder_name' => $linkData['folder_name'],
                    'id' => $newFolderId,
                ]);

                /**
                 * We're unsetting the folder_name and the user_id
                 * fields from the $linkData model as the Link model doesn't
                 * have those two fields. Saving the link with these fields
                 * will throw an error.
                 * @var [type]
                 */
                $folderId = $newFolderId;
                unset($linkData['folder_name']);
                unset($linkData['user_id']);
            }

        }

        if(isset($linkData['folder_name'])) {
            unset($linkData['folder_name']);
        }

        /**
         * If a folder_id is provided, we're unsetting the other
         * unwated fields from the link model.
         */
        if(isset($linkData['folder_name'])) {
            unset($linkData['folder_name']);
        }

        if(isset($linkData['user_id'])) {
            unset($linkData['user_id']);
        }

        $linkData['folder_id'] = $folderId;

        /**
         * Checking if the link provided is a valid URL, otherwise,
         * an error response is returned with a suggestion.
         */
        if(!filter_var($linkData['link_url'], FILTER_VALIDATE_URL)) {
            throw new \Exception('Invalid URL! Try with http:// or https://', 422);
        }

        try {
            /**
             * Checking if link name is set. If set,
             * there are two possibilities:
             * 1. It could either be an empty string, or
             * 2. There could be a name.
             *
             * If it's an empty string, we'll get the name from the URL.
             */
            if(isset($linkData['link_name'])) {
                /**
                 * If the name set but it's an empty string,
                 * we'll get the name from the link.
                 */
                if($linkData['link_name'] == '') {
                    $linkData['link_name'] = $this->getTitleFromUrl($linkData['link_url']);
                }
            } else {
                /**
                 * If the name is not set at all,
                 * we'll get it from the link.
                 */
                $linkData['link_name'] = $this->getTitleFromUrl($linkData['link_url']);
            }
        } catch(\Exception $e) {
            $linkData['link_name'] = $linkData['link_url'];
        }

        /**
         * If there's any single quote (') in the link name,
         * we're replacing that with double quotes.
         */
        $linkData['link_name'] = str_replace('\'', '"', $linkData['link_name']);

        /**
         * Saving the link.
         * @var [type]
         */
        $result = $this->linkRepo->saveLink($linkData);
        return $result;
    }

    public function getLinksInFolder($folderId) {
        $links = $this->linkRepo->getLinksInFolder($folderId);
        return $links;
    }

    public function getLink($linkId) {

    }

    public function updateLink($linkId, $linkData) {

        /**
         * Checking if the link provided is a valid URL, otherwise,
         * an error response is returned with a suggestion.
         */
        if(!filter_var($linkData['link_url'], FILTER_VALIDATE_URL)) {
            throw new \Exception('Invalid URL! Try with http:// or https://', 422);
        }

        try {
            /**
             * Checking if link name is set. If set,
             * there are two possibilities:
             * 1. It could either be an empty string, or
             * 2. There could be a name.
             *
             * If it's an empty string, we'll get the name from the URL.
             */
            if(isset($linkData['link_name'])) {
                /**
                 * If the name set but it's an empty string,
                 * we'll get the name from the link.
                 */
                if($linkData['link_name'] == '') {
                    $linkData['link_name'] = $this->getTitleFromUrl($linkData['link_url']);
                }
            } else {
                /**
                 * If the name is not set at all,
                 * we'll get it from the link.
                 */
                $linkData['link_name'] = $this->getTitleFromUrl($linkData['link_url']);
            }
        } catch(\Exception $e) {
            $linkData['link_name'] = $linkData['link_url'];
        }

        /**
         * If there's any single quote (') in the link name,
         * we're replacing that with double quotes.
         */
        $linkData['link_name'] = str_replace('\'', '"', $linkData['link_name']);

        $result = $this->linkRepo->updateLink($linkId, $linkData);
        return $result;
    }

    public function deleteLink($linkId) {
        $result = $this->linkRepo->deleteLink($linkId);
        return $result;
    }

    public function changeLinkFolder($linkId, $newFolderId) {
        $result = $this->linkRepo->changeLinkFolder($linkId, $newFolderId);
        return $result;
    }

    /**
     * Function to get the Title of an HTML page using the link.
     * This function is used to get the name of a link when it's
     * not provided in the input.
     * 
     * @param  [type] $url [description]
     * @return [type]      [description]
     */
    public function getTitleFromUrl($url) {

        $doc = new \DOMDocument();
        @$doc->loadHTMLFile($url);
        $xpath = new \DOMXPath($doc);
        $title = $xpath->query('//title')->item(0)->nodeValue;
        return $title;
    }

}