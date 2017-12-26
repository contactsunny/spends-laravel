<?php
/**
 * Created by PhpStorm.
 * User: srinidhi
 * Date: 20/2/17
 * Time: 10:55 AM
 */

namespace App\Repositories;

use App\Interfaces\LinkRepositoryInterface;
use App\Models\Link;

class LinkRepository implements LinkRepositoryInterface
{

    /**
     * Function to delete all links in a folder identified by $folderId.
     * 
     * @param  [type] $folderId [description]
     * @return [type]           [description]
     */
    public function deleteLinksInFolder($folderId) {
        $result = Link::where('folder_id', $folderId)->delete();
        return $result;
    }

    /**
     * Function to get the list of all links in a folder.
     * 
     * @param  [type] $folderId [description]
     * @return [type]           [description]
     */
    public function getLinksInFolder($folderId) {
        $links = Link::with('folder')->where('folder_id', $folderId)->get();
        return $links;
    }

    /**
     * Function to create a new link in a folder.
     * 
     * @param  [type] $linkData [description]
     * @return [type]           [description]
     */
    public function saveLink($linkData) {
        $link = new Link($linkData);
        $result = $link->save();

        if($result) {
            $result = $link;
        }

        return $result;
    }

    public function getLink($linkId)
    {
        // TODO: Implement getLink() method.
    }

    public function updateLink($linkId, $linkData){

        $result = false;
        $link = Link::find($linkId);

        if($link) {
            $result = $link->update($linkData);
        }

        if($result) {
            $result = $link;
        }

        return $result;
    }

    public function deleteLink($linkId) {
        $result = Link::find($linkId)->delete();
        return $result;
    }

    public function changeLinkFolder($linkId, $newFolderId) {
        $result = Link::find($linkId)->update([
            'folder_id' => $newFolderId,
        ]);
        return $result;
    }
}