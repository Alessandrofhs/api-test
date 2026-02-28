<?php

namespace App\Http\Controllers;

use App\Jobs\GeneratePocketReportJob;
use App\Models\UserPocket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function create(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:INCOME,EXPENSE',
            'date' => 'required|date_format:Y-m-d',
        ]);

        $pocket = UserPocket::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $reportId = $pocket->id . '-' . now()->timestamp;

        GeneratePocketReportJob::dispatch(
            $reportId,
            $pocket->id,
            Auth::id(),
            $request->type,
            $request->date
        );

        return response()->json([
            'status' => 200,
            'error' => false,
            'message' => 'Report sedang dibuat. Silahkan check berkala pada link berikut.',
            'data' => [
                'link' => url('/reports/' . $reportId)
            ]
        ]);
    }
    public function download(string $id): StreamedResponse
    {
        $files = collect(Storage::files('reports'));

        // Cek apakah file persis ada (pocket_id + timestamp)
        $exact = $files->first(fn($f) => basename($f) === $id . '.xlsx');
        if ($exact) {
            return Storage::download(
                $exact,
                basename($exact),
                ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
            );
        }

        // Kalau nggak ada, anggap $id cuma pocket_id → ambil file terakhir pocket itu
        $latest = $files->filter(fn($f) => str_starts_with(basename($f), $id . '-'))
            ->sortByDesc(fn($f) => Storage::lastModified($f))
            ->first();

        if (!$latest) abort(404, 'Report tidak ditemukan.');

        return Storage::download(
            $latest,
            basename($latest),
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        );
    }
}
