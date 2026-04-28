<?php

namespace App\Services;

use App\Models\Submission;
use App\Models\SubmissionVersion;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class SubmissionService
{
    public function createOrGetSubmission(
        int $seasonId,
        int $divisionId,
        int $clubId,
        string $responsibleName,
        string $phone,
        string $email,
    ): Submission {
        return DB::transaction(function () use ($seasonId, $divisionId, $clubId, $responsibleName, $phone, $email) {
            $submission = Submission::query()->firstOrCreate(
                [
                    'season_id' => $seasonId,
                    'division_id' => $divisionId,
                    'club_id' => $clubId,
                ],
                [
                    'responsible_name' => $responsibleName,
                    'phone' => $phone,
                    'email' => $email,
                    'payment_status' => 'pending',
                    'max_allowed_submissions' => 2,
                ]
            );

            $submission->update([
                'responsible_name' => $responsibleName,
                'phone' => $phone,
                'email' => $email,
            ]);

            return $submission->fresh();
        });
    }

    public function createVersion(
        Submission $submission,
        ?UploadedFile $clubLogo,
        ?UploadedFile $paymentReceipt,
        ?UploadedFile $playersRoster,
        ?string $observations,
    ): SubmissionVersion {
        return DB::transaction(function () use ($submission, $clubLogo, $paymentReceipt, $playersRoster, $observations) {
            $submission->loadCount('versions');

            if ($submission->versions_count >= $submission->max_allowed_submissions) {
                throw ValidationException::withMessages([
                    'submission' => 'El club ya alcanzó el máximo de envíos permitidos para esta temporada/división.',
                ]);
            }

            $versionNumber = $submission->versions_count + 1;
            $latestVersion = $submission->versions()->latest('version_number')->first();

            $baseFolder = "submissions/{$submission->id}/v{$versionNumber}";

            $clubLogoPath = $clubLogo
                ? $clubLogo->storeAs($baseFolder, 'club_logo.'.$clubLogo->extension(), 'local')
                : $latestVersion?->club_logo_path;

            $paymentReceiptPath = $paymentReceipt
                ? $paymentReceipt->storeAs($baseFolder, 'payment_receipt.'.$paymentReceipt->extension(), 'local')
                : $latestVersion?->payment_receipt_path;

            $playersRosterPath = $playersRoster
                ? $playersRoster->storeAs($baseFolder, 'players_roster.'.$playersRoster->extension(), 'local')
                : $latestVersion?->players_roster_path;

            $version = $submission->versions()->create([
                'version_number' => $versionNumber,
                'club_logo_path' => $clubLogoPath,
                'payment_receipt_path' => $paymentReceiptPath,
                'players_roster_path' => $playersRosterPath,
                'observations' => $observations,
                'status' => 'received',
            ]);

            $submission->update([
                'active_version' => $submission->active_version ?: $versionNumber,
                'payment_status' => $paymentReceiptPath ? 'in_review' : $submission->payment_status,
            ]);

            return $version;
        });
    }

    public function markAccepted(SubmissionVersion $version): void
    {
        DB::transaction(function () use ($version) {
            $submission = $version->submission()->firstOrFail();

            $submission->versions()
                ->where('id', '!=', $version->id)
                ->where('status', 'accepted')
                ->update(['status' => 'replaced']);

            $version->update(['status' => 'accepted']);

            $submission->update(['active_version' => $version->version_number]);
        });
    }

    public function markRejected(SubmissionVersion $version): void
    {
        $version->update(['status' => 'rejected']);
    }

    public function deleteVersion(SubmissionVersion $version): void
    {
        DB::transaction(function () use ($version) {
            foreach (['club_logo_path', 'payment_receipt_path', 'players_roster_path'] as $column) {
                $path = $version->{$column};

                if ($path && Storage::disk('local')->exists($path)) {
                    Storage::disk('local')->delete($path);
                }
            }

            $submission = $version->submission()->firstOrFail();

            $version->delete();

            $activeVersionExists = $submission->versions()->where('version_number', $submission->active_version)->exists();
            if (! $activeVersionExists) {
                $latest = $submission->versions()->latest('version_number')->first();
                $submission->update(['active_version' => $latest?->version_number]);
            }
        });
    }
}
