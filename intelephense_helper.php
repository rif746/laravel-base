<?php

namespace Illuminate\Contracts\Auth;

interface Authenticatable
{
    public function update();
    public function delete();
    public function save();
    public function hasVerifiedEmail();
    public function sendEmailVerificationNotification();
}


namespace Illuminate\Contracts\Filesystem;

interface Filesystem
{
    public function temporaryUrl($path, $option);
}
