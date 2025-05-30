<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateKegiatanStatus extends Command
{
    protected $signature = 'kegiatan:update-status';
    protected $description = 'Update status kegiatan setiap hari';

    public function handle()
    {
        $today = Carbon::today();

        // Update kegiatan jadi dilaksanakan jika tanggal = hari ini
        DB::table('kegiatan')
            ->whereDate('tanggal', '=', $today)
            ->where('status', 'dijadwalkan')
            ->update(['status' => 'dilaksanakan']);

        // Update kegiatan jadi selesai jika tanggal < hari ini
        DB::table('kegiatan')
            ->whereDate('tanggal', '<', $today)
            ->where('status', 'dilaksanakan')
            ->update(['status' => 'selesai']);

        $this->info('Status kegiatan berhasil diperbarui.');
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:update-kegiatan-status';

    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    // public function handle()
    // {
    //     //
    // }
}
