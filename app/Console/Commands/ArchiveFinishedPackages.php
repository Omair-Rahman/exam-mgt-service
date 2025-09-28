<?php

namespace App\Console\Commands;

use App\Models\InactivePackage;
use App\Models\Package;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ArchiveFinishedPackages extends Command
{
    protected $signature = 'packages:archive-finished';
    protected $description = 'Move finished packages (ends_at <= now) to inactive_packages';

    public function handle(): int
    {
        $now = now();
        $finished = Package::where('ends_at', '<=', $now)->get();

        foreach ($finished as $pkg) {
            DB::transaction(function () use ($pkg, $now) {
                InactivePackage::create([
                    'name'              => $pkg->name,
                    'exam_time_minutes' => $pkg->exam_time_minutes,
                    'question_number'   => $pkg->question_number,
                    'pass_mark_percent' => $pkg->pass_mark_percent,
                    'exam_instructions' => $pkg->exam_instructions,
                    'starts_at'         => $pkg->starts_at,
                    'ends_at'           => $pkg->ends_at,
                    'archived_at'       => $now,
                ]);
                $pkg->delete();
            });
        }

        $this->info("Archived {$finished->count()} package(s).");
        return self::SUCCESS;
    }
}

