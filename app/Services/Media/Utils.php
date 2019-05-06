<?php

namespace App\Services\Media;


use Illuminate\Support\Str;

trait Utils {

    protected $folderChars = '\_\-';
    protected $fileChars = '.\_\-\'\s\(\)\,';

    protected function cleanName($text, $folder = false)
    {
        $pattern = $this->filePattern($folder ? $this->folderChars : $this->fileChars);
        $text    = preg_replace($pattern, '', $text);

        return $text ?: Str::random(10);
    }

    protected function filePattern($item)
    {
        return '/(script.*?\/script)|[^(' . $item . ')a-zA-Z0-9]+/ius';
    }

}