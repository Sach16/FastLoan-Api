<?php

namespace Whatsloan\Services\Excel;

use Illuminate\Support\Collection;

class ExcelAdaptor implements IExcel
{
    /**
     * @var
     */
    private $excel;

    /**
     * ExcelAdaptor constructor.
     */
    public function __construct()
    {
        $this->excel = app('excel');
    }

    /**
     * Generate a excel file from view
     *
     * @param Collection $views
     * @param $name
     */
    public function template(Collection $views, $name)
    {
        $this->excel->create($name, function($excel) use($views) {
            $views->each(function($view) use ($excel){
                $excel->sheet(key($view), function($sheet) use($view) {
                    $sheet->loadView(value($view[key($view)]))->with(strtolower(key($view)), $view['data']);
                    $sheet->setColumnFormat($view['format']);
                });
            });
        })->download('xlsx');
    }

    /**
     * Load the file for parsing
     *
     * @param $file
     * @return mixed
     */
    public function load($file)
    {
        $data = $this->excel->load($file, function($reader) {
            // $reader->ignoreEmpty();
            $reader->take(100);
        });
        return collect($data->get()->toArray());
    }
}