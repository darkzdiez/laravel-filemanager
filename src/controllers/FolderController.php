<?php namespace Unisharp\Laravelfilemanager\controllers;

use Illuminate\Support\Facades\File;

/**
 * Class FolderController
 * @package Unisharp\Laravelfilemanager\controllers
 */
class FolderController extends LfmController
{
    /**
     * Get list of folders as json to populate treeview
     *
     * @return mixed
     */
    public function getFolders()
    {
        $user_path     = parent::getRootFolderPath('user');
        $lfm_user_path = parent::getFileName($user_path);
        $user_folders  = parent::getDirectories($user_path);

        $share_path     = parent::getRootFolderPath('share');
        $lfm_share_path = parent::getFileName($share_path);
        $shared_folders = parent::getDirectories($share_path);

        return view('laravel-filemanager::tree')
            ->with('allow_multi_user', parent::allowMultiUser())
            ->with('user_dir', $lfm_user_path['long'])
            ->with('dirs', $user_folders)
            ->with('share_dir', $lfm_share_path['long'])
            ->with('shares', $shared_folders);
    }


    /**
     * Add a new folder
     *
     * @return mixed
     */
    public function getAddfolder()
    {
        $folder_name = trim(request('name'));

        $path = parent::getCurrentPath($folder_name);

        if (empty($folder_name)) {
            return $this->error('folder-name');
        } elseif (File::exists($path)) {
            return $this->error('folder-exist');
        } elseif (config('lfm.alphanumeric_directory') && preg_match('/[^\w-]/i', $folder_name)) {
            return $this->error('folder-alnum');
        } else {
            $this->createFolderByPath($path);
            return 'OK';
        }
    }
}
