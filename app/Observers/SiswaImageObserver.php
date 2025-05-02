<?php

namespace App\Observers;

use App\Siswa;
use App\User;

class SiswaImageObserver
{
    /**
     * Handle the siswa "updated" event.
     *
     * @param  \App\Siswa  $siswa
     * @return void
     */
    public function updated(Siswa $siswa)
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
