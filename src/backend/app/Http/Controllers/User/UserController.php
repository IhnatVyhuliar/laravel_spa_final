<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public static function changeUserName(User $user, string $userNameToSanitize): string
    {
        $cleanedUserName = ucfirst(htmlspecialchars($userNameToSanitize));
        $user->name = $cleanedUserName;
        $user->update();
        return $cleanedUserName;
    }
}
