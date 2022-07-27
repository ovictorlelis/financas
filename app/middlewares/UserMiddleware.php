<?php

namespace app\middlewares;

use app\models\User;

class UserMiddleware
{
  public static function auth()
  {
    $token = $_SESSION['token'] ?? '';

    $user = new User();
    $user = $user->select()->where('token', $token)->first();

    if (!$user) {
      return false;
    }

    return $user;
  }

  public static function login($email, $pass)
  {
    $user = new User();
    $user = $user->select()->where('email', $email)->first();

    if ($user && password_verify($pass, $user->password)) {

      $token = self::token($email, $user->password);

      $_SESSION['token'] = $token;
      $user = new User();
      $user->update(['token' => $token])->where(['email' => $email])->execute();

      return true;
    }

    return false;
  }

  private static function token($email, $pass)
  {
    return md5(
      rand(0, 999) .
        $email .
        rand(0, 555) .
        $pass .
        date('Y-m-d H:i:s') .
        rand(0, 777)
    );
  }
}
