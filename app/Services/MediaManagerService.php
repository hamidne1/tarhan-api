<?php

namespace App\Services;

use App\Exceptions\DirectoryNotExists;
use App\Exceptions\FileExistsException;
use App\Exceptions\FileNotExistsException;
use App\Services\Media\MediaBaseService;
use App\Services\Media\Utils;
use App\Tools\Base64Helper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MediaManagerService extends MediaBaseService {

    use Base64Helper, Utils;

    /**
     * showing all file and directories in given folders
     *
     * @param $passedFolders
     * @return array
     */
    public function index()
    {
        $directory = new \DirectoryIterator($this->path);
        $files = [];

        foreach ($directory as $file) {
            $fileInfo = [];

            # filter . and ..
            if ($file->isDot()) continue;

            # filter by extensions
            if ($this->excludeExtension->contains(
                $file->getExtension()
            )) continue;

            # filter by folders
            if ($this->excludeDirectory->contains(
                $file->getBasename()
            )) continue;

            # filter by folders
            if ($this->excludeFile->contains(
                $file->getBasename()
            )) continue;

            $fileInfo['name'] = trim($file->getFilename());
            $fileInfo['type'] = $file->getType();
            $fileInfo["url"] = '';
            $fileInfo['directory'] = explode(
                'junk/', ($file->getPathname())
            )[1];
            $fileInfo['size'] = "";
            $fileInfo['time'] = $time = Carbon::createFromTimestamp($file->getMTime());
            $fileInfo['time_human'] = $time = Carbon::createFromTimestamp($file->getMTime())->diffForHumans();
            if (!$file->isDir()) {
                $fileInfo['url'] = Storage::disk('junk')->url($fileInfo['directory']);
                $fileInfo['size'] = $this->bytesDiffForHuman($file->getSize());
            }

            $files[] = $fileInfo;
        }

        return $files;
    }


    /**
     * @throws \Illuminate\Validation\ValidationException
     * @throws FileExistsException
     * @throws \Exception
     */
    public function create()
    {
        $validated = Validator::validate($this->request->all(), [
            'name' => 'required'
        ]);

        $name = $this->cleanName($validated['name'], true);

        if (file_exists($this->path . $name)) {
            throw new FileExistsException;
        }

        try {
            Storage::drive('junk')->createDir(
                $path = $this->request->get('directory', '') . DIRECTORY_SEPARATOR . $name
            );

            return [
                'name' => $name,
                'type' => 'dir',
                'url' => '',
                'directory' => $path,
                'size' => 0,
                'time' => Carbon::now(),
                'time_human' => Carbon::now()->diffForHumans()
            ];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * delete a resource form storage
     *
     * @return bool
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function delete()
    {
        $validated = Validator::validate($this->request->all(), [
            'name' => 'required'
        ]);

        $objPath = Storage::drive('junk')->path(
                $this->request->get('directory', '')
            ) . DIRECTORY_SEPARATOR . $validated['name'];


        try {
            if (is_dir($objPath))
                return $this->deleteRecursively(
                    rtrim($objPath, '/')
                );


            if (!file_exists($objPath)) {
                throw new FileNotExistsException;
            }

            unlink($objPath);
            return true;

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * upload the files into
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws DirectoryNotExists
     */
    public function upload()
    {
        $validated = Validator::validate($this->request->all(), [
            'image' => 'required|array',
            'image.name' => 'required|string',
            'image.data' => 'required|string'
        ]);
        $name = $this->cleanName($validated['image']['name']);

        if (!is_dir(
            Storage::drive('junk')->path($dir = $this->request->get('directory'))
        )) throw new  DirectoryNotExists;

        $image = [];

        try {
            $image['name'] = $name;
            $image['file'] = $this->createFileFromBase64($validated['image']['data']);

        } catch (\App\Exceptions\InvalidBase64Data $e) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                "image" => 'کد نامعتبر'
            ]);
        }

        Validator::make(compact('image'), [
            'image.file' => 'required|file|mimes:jpeg,jpg,png,gif'
        ])->validate();

        Storage::drive('junk')->putFileAs($this->request->get('directory'), $image['file'], $image['name']);

        return [
            'name' => trim($image['name']),
            'type' => 'file',
            'directory' => $dir . DIRECTORY_SEPARATOR . $image['name'],
            'size' => $this->bytesDiffForHuman($image['file']->getSize()),
            'time' => Carbon::now(),
            'time_human' => Carbon::now()->diffForHumans(),
            'url' => \Storage::drive('junk')->url($dir . "/" . $image['name']),
        ];
    }

}
