<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait FileUpload {

    public function uploadFile(UploadedFile $file, string $directory = 'uploads') : string {

        try {
            $filename = 'educore_'.uniqid().'.'. $file->getClientOriginalExtension();
    
            // move the file to storage
            $file->storeAs($directory, $filename, 'public');
    
            return '/' . $directory. '/' . $filename;
        }catch(Exception $e) {
            throw $e;
        }
       
    }


    public function reuploadFileFromPath(string $oldPath, string $directory = 'uploads'): string
    {
        try {
            $filename = 'educore_' . uniqid() . '.' . pathinfo($oldPath, PATHINFO_EXTENSION);
            $newPath = $directory . '/' . $filename;

            // Đường dẫn vật lý đến file cũ
            $fullOldPath = public_path($oldPath);

            if (!file_exists($fullOldPath)) {
                throw new \Exception("File không tồn tại tại: $fullOldPath");
            }

            // Đọc nội dung file cũ
            $fileContent = file_get_contents($fullOldPath);

            // Lưu lại với tên mới trong disk 'public'
            Storage::disk('public')->put($newPath, $fileContent);

            // Trả về đường dẫn public
            return '/' . $newPath;
        }catch(Exception $e) {
            throw $e;
        }
    }


    public function deleteFile(?string $path) : bool {
        if(File::exists(public_path($path))) {
            File::delete(public_path($path));
            return true;
        }
        return false;
    }
}