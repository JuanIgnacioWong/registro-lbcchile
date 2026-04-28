<?php

namespace App\Livewire\Admin;

use App\Models\Submission;
use Livewire\Component;

class SubmissionTable extends Component
{
    public string $search = '';

    public function render()
    {
        $submissions = Submission::query()
            ->with(['season', 'division', 'club'])
            ->withCount('versions')
            ->when($this->search !== '', function ($query) {
                $query->whereHas('club', fn ($q) => $q->where('name', 'like', '%'.$this->search.'%'));
            })
            ->latest()
            ->limit(10)
            ->get();

        return view('livewire.admin.submission-table', [
            'submissions' => $submissions,
        ]);
    }
}
