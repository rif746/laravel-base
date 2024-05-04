<?php

namespace App\Livewire\Module\Trait;

use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Features\SupportFileUploads\WithFileUploads;

trait FileUpload
{
    use WithFileUploads;

    protected function loadFiles(&$source, &$target)
    {
        if ($source instanceof \Illuminate\Database\Eloquent\Collection || $source instanceof \Illuminate\Support\Collection) {
            foreach ($source as $value) {
                $target[$value->id]["id"] = $value->id;
                $target[$value->id]["fileName"] = $value->file_name;
                $target[$value->id]["fileSize"] = 0;
                $target[$value->id]["progress"] = 100;
                $target[$value->id]["fileRef"] = $value->file_path;
            }

            $source = new \Illuminate\Support\Collection();
        } elseif ($source != null) {
            $target[0]["fileName"] = '';
            $target[0]["fileSize"] = 0;
            $target[0]["progress"] = 100;
            $target[0]["fileRef"] = $source;
        }
    }

    protected function getSingleFile($source, &$target)
    {
        foreach ($source as $value) {
            if ($value["fileRef"] instanceof TemporaryUploadedFile) {
                $target = $value["fileRef"];
                return;
            }
        }
    }

    protected function getFiles($source, &$target)
    {
        foreach ($source as $value) {
            if ($value["fileRef"] instanceof TemporaryUploadedFile) {
                $target[] = $value["fileRef"];
            }
        }
    }

    protected function removeFromList($id, &$target)
    {
        unset($target[$id]);
        $target = array_values($target);
    }

    protected function checkToDelete($source, $wantToDelete)
    {
        foreach ($source as $src) {
            if ($src['fileRef'] instanceof TemporaryUploadedFile) {
                deleteFile($wantToDelete);
            }
        }
    }

    protected function getFileMeta($source, $id)
    {
        $ref = array_column($source, 'fileRef', 'id');
        return (object)['file_path' => $ref[$id], 'id' => $id];
    }

    protected function fileChanged($source): bool
    {
        foreach ($source as $value) {
            if ($value["fileRef"] instanceof TemporaryUploadedFile) {
                return true;
            }
        }
        return false;
    }
}
