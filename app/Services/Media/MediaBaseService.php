<?php


namespace App\Services\Media;


use App\Exceptions\DirectoryNotExists;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MediaBaseService {

    /**
     * the home path of file manager
     *
     * @var string
     */
    protected $home;

    /**
     * provide the full path
     *
     * @var string
     */
    protected $path;

    /**
     * file manager exclude files
     *
     * @var \Illuminate\Support\Collection
     */
    protected $excludeFile;

    /**
     * file manager exclude folders
     *
     * @var \Illuminate\Support\Collection
     */
    protected $excludeDirectory;

    /**
     * file manager exclude extensions
     *
     * @var \Illuminate\Support\Collection
     */
    protected $excludeExtension;

    /**
     * @var Request
     */
    protected $request;

    /**
     * Controller constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->home = public_path('junk');

        $this->excludeFile = Collection::make(
            config('media.excludeFile')
        );
        $this->excludeDirectory = Collection::make(
            config('media.excludeDirectory')
        );
        $this->excludeExtension = Collection::make(
            config('media.excludeExtension'));

        $this->request = $request;

        $this->path = empty(
        $directory = trim($this->request->get('directory', ''))
        )
            ? $this->home . DIRECTORY_SEPARATOR
            : $this->home . DIRECTORY_SEPARATOR . $directory . DIRECTORY_SEPARATOR;
    }

    /**
     * remove folder recursively
     *
     * @param $path
     * @return bool
     * @throws DirectoryNotExists
     */
    protected function deleteRecursively($path)
    {
        # only directory can delete recursively
        if (!is_dir($path))
            throw new DirectoryNotExists;

        # scan object of directory
        $exec = scandir($path);
        $objects = array_splice($exec, 2);

        # delete all object of directory
        foreach ($objects as $object) {
            $newPath = $path . DIRECTORY_SEPARATOR . $object;
            is_dir($newPath)
                ? $this->deleteRecursively($newPath)
                : unlink($newPath);
        }

        # delete directory folder
        rmdir($path);
        return true;
    }

    /**
     * prepare name of the file or folder
     *
     * @param $objName
     * @return string|string[]|null
     */
    protected function prepareName($objName)
    {
        if (!config('media.prepareName'))
            return $objName;

        $filters = [
            '=', '+', '%', '*', '<', '>',
            '[', '{', ']', '}', '(', ')',
            '!', '@', '#', '$', '&', '?',
            '~', '`', '^', '"', "'",
            ';', ':', ',',
            '/', '\\', '|',
        ];
        $replacedFilters = trim(
            str_replace(
                $filters, '-', strip_tags($objName)
            )
        );

        $preparedName = preg_replace('/\s+/', '-', $replacedFilters);

        return $this->renameExistsFile(
            pathinfo($preparedName, PATHINFO_FILENAME),
            pathinfo($preparedName, PATHINFO_EXTENSION)
        );


    }


    /**
     * rename of file or folder if exists
     *
     * @param $name
     * @param $extension
     * @return string
     */
    protected function renameExistsFile($name, $extension)
    {
        if (!file_exists($this->path . $name . $extension))
            return $name . $extension;

        $i = 1;
        while (file_exists("{$this->path}{$name}-{$i}.{$extension}")) {
            $i++;
        }

        return "{$this->path}{$name}-{$i}.{$extension}";
    }

    /**
     * change byte to human readable
     *
     * @param $bytes
     * @return string
     */
    protected function bytesDiffForHuman($bytes)
    {
        for ($i = 0; ($bytes >= 1024 && $i < 5); $i++)
            $bytes /= 1024;

        return round($bytes, 2) . [' B', ' KB', ' MB', ' GB', ' TB', ' PB'][$i];
    }

}