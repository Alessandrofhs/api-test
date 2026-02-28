<?php

namespace App\Jobs;

use App\Exports\PocketReportExport;
use App\Models\Expense;
use App\Models\Income;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class GeneratePocketReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $reportId,
        public int $pocketId,
        public int $userId,
        public string $type,
        public string $date
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->type === 'INCOME') {
            $data = Income::where('user_id', $this->userId)
                ->where('pocket_id', $this->pocketId)
                ->whereDate('created_at', $this->date)
                ->get();
        } else {
            $data = Expense::where('user_id', $this->userId)
                ->where('pocket_id', $this->pocketId)
                ->whereDate('created_at', $this->date)
                ->get();
        }

        if (!Storage::exists('reports')) {
            Storage::makeDirectory('reports');
        }

        $filePath = 'reports/' . $this->reportId . '.xlsx';
        Excel::store(new PocketReportExport($data), $filePath, 'local');
    }
}
