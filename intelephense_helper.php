<?php

namespace Illuminate\Contracts\Auth;

interface Authenticatable
{
    public function update();
    public function delete();
    public function save();
    public function sendEmailVerificationNotification();
}
