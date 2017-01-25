<?php

namespace Whatsloan\Services\Zipper;

interface IZip
{

    /**
     * Zip all files in path and return
     * 
     * @param $path
     * @param $filename
     * @return mixed
     */
    public function archive($path, $filename);
}