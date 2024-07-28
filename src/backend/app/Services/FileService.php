<?php

namespace App\Services;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreCommentRequest;
class FileService
{
    public function storePhotoFileFromRequest(StoreCommentRequest $request): null|string
    {   
        $photo_file = null;
        if ($request->has("photo_file"))
        {
            $photo_file = $this->storeFilePhoto($request->photo_file);
        }
        return $photo_file;
    }

    public function storeTXTFileFromRequest(StoreCommentRequest $request): null|string
    {   
        $txt_file = null;
        if($request->has('txt_file')){
            $txt_file = $this->storeFileTxt($request->txt_file);
        }
        return $txt_file;
    }

    public function storeFilePhoto(string $photo_file_path): null|string
    {
        Storage:put($photo_file_path, 'public/comments/photo');
        return $photo_file_path;
    }

    public function storeFileTxt(string $txt_file_path): null|string
    {
        $fileContent = file_get_contents(storage_path($txt_file_path));
        $txt_file = null;
        if (app(TagClosedCheckService::class)->checkTags($fileContent))
        {
            if (app(TagClosedCheckService::class)->checkString($fileContent)){
                Storage:put($txt_file_path, 'public/comments/txt');
                $txt_file = $txt_file_path;
            }
            else{
                abort(403);  
            }
        }
        return $txt_file;
    }

    public function checkFileExists(string $fileName): bool
    {
        return file_exists($fileName);
    }

    public function deleteFile(string $fileName): void
    {
        if($this->checkFileExists($fileName)){
            Storage::delete($fileName);
        }
    }

    
}