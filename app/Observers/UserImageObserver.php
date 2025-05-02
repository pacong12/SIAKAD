<?php

namespace App\Observers;

use App\Guru;
use App\Siswa;
use App\User;

class UserImageObserver
{
    /**
     * Handle the guru "updated" event.
     *
     * @param  \App\Guru  $guru
     * @return void
     */
    public function updated(Guru $guru)
    {
        // Jika image guru diupdate, update juga image user
        if ($guru->isDirty('image') && $guru->user_id) {
            $user = User::find($guru->user_id);
            if ($user) {
                $user->image = $guru->image;
                $user->save();
            }
        }
    }

    /**
     * Handle the siswa "updated" event.
     *
     * @param  \App\Siswa  $siswa
     * @return void
     */
    public function updatedSiswa(Siswa $siswa)
    {
        // Jika image siswa diupdate, update juga image user
        if ($siswa->isDirty('image') && $siswa->user_id) {
            $user = User::find($siswa->user_id);
            if ($user) {
                $user->image = $siswa->image;
                $user->save();
            }
        }
    }
}
