<?php

namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PocketReportExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $data;
    
    public function collection()
    {
        //
    }
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('reports.excel', [
            'data' => $this->data
        ]);
    }
}
