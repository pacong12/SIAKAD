<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Siswa;
use App\Guru;

class SyncUserImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:sync-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi foto profil dari tabel guru dan siswa ke tabel users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Memulai sinkronisasi image user dari tabel guru dan siswa...');
        
        // Sync image untuk user guru
        $guruCount = 0;
        $guruUsers = User::where('role', 'guru')->get();
        $this->output->progressStart(count($guruUsers));
        
        foreach ($guruUsers as $user) {
            $guru = Guru::where('user_id', $user->id)->first();
            if ($guru && $guru->image) {
                $user->image = $guru->image;
                $user->save();
                $guruCount++;
            }
            $this->output->progressAdvance();
        }
        
        $this->output->progressFinish();
        
        // Sync image untuk user siswa
        $siswaCount = 0;
        $siswaUsers = User::where('role', 'siswa')->get();
        $this->output->progressStart(count($siswaUsers));
        
        foreach ($siswaUsers as $user) {
            $siswa = Siswa::where('user_id', $user->id)->first();
            if ($siswa && $siswa->image) {
                $user->image = $siswa->image;
                $user->save();
                $siswaCount++;
            }
            $this->output->progressAdvance();
        }
        
        $this->output->progressFinish();
        
        $this->info("Sinkronisasi selesai! {$guruCount} image guru dan {$siswaCount} image siswa telah diperbarui.");
        return 0;
    }
}
