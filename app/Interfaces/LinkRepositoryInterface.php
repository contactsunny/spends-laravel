<?php
/**
 * Created by PhpStorm.
 * User: srinidhi
 * Date: 20/2/17
 * Time: 10:52 AM
 */

namespace App\Interfaces;


interface LinkRepositoryInterface
{
    public function deleteLinksInFolder($folderId);
    public function getLinksInFolder($folderId);
    public function saveLink($linkData);
    public function getLink($linkId);
    public function updateLink($linkId, $linkData);
    public function deleteLink($linkId);
    public function changeLinkFolder($linkId, $newFolderId);

}