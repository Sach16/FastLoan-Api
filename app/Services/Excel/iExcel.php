<?php

namespace Whatsloan\Services\Excel;

use Illuminate\Support\Collection;

interface IExcel
{

    /**
     * Generate a excel file from view
     * @param Collection $sheets
     * @param $name
     * @return
     */
    public function template(Collection $sheets, $name);

    /**
     * Load the file for parsing
     * 
     * @param $file
     * @return mixed
     */
    public function load($file);
}