<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\SubmissionVersion;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

class FileDownloadController extends Controller
{
    public function downloadVersionFile(SubmissionVersion $version, string $fileType): StreamedResponse
    {
        $path = match ($fileType) {
            'logo' => $version->club_logo_path,
            'receipt' => $version->payment_receipt_path,
            'roster' => $version->players_roster_path,
            default => null,
        };

        abort_unless($path && Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->download($path);
    }

    public function downloadAll(Submission $submission): BinaryFileResponse
    {
        $submission->load(['versions' => fn ($q) => $q->orderBy('version_number')]);

        $zipName = "submission_{$submission->id}_".now()->format('Ymd_His').'.zip';
        $zipPath = storage_path("app/private/tmp/{$zipName}");

        if (! is_dir(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0755, true);
        }

        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($submission->versions as $version) {
            foreach ([
                'club_logo_path' => 'logo',
                'payment_receipt_path' => 'comprobante',
                'players_roster_path' => 'nomina',
            ] as $column => $label) {
                $path = $version->{$column};
                if (! $path || ! Storage::disk('local')->exists($path)) {
                    continue;
                }

                $extension = pathinfo($path, PATHINFO_EXTENSION);
                $entryName = "v{$version->version_number}/{$label}.{$extension}";
                $zip->addFromString($entryName, Storage::disk('local')->get($path));
            }
        }

        $zip->close();

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
