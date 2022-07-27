<?php

namespace app\controllers;

use core\Controller;
use app\middlewares\Message;
use app\middlewares\UserMiddleware;
use app\models\Invitation;
use app\models\User;
use app\models\Wallet;

class HomeController extends Controller
{

    public function index()
    {
        $message = Message::get();

        if (UserMiddleware::auth()) {
            return route()->redirect('/dashboard');
        }

        $this->render('login', [
            'message' => $message
        ]);
    }

    public function login()
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $pass = filter_input(INPUT_POST, 'password');

        if (!$email && !$pass) {
            return route()->redirect('/?message=105');
        }

        if (UserMiddleware::login($email, $pass)) {
            return route()->redirect('/dashboard');
        }
        return route()->redirect('/?message=110');
    }

    public function register()
    {
        $message = Message::get();

        if (UserMiddleware::auth()) {
            return route()->redirect('/dashboard');
        }

        $invite = filter_input(INPUT_GET, 'invite') ?? '';

        if (!$invite && session()->has(['old', 'invite'])) {
            $invite = old('invite');
        }

        $this->render('register', [
            "invite" => $invite,
            "message" => $message
        ]);
    }

    public function create()
    {
        $name = filter_input(INPUT_POST, 'name');
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');
        $invite = filter_input(INPUT_POST, 'invite');

        $invitation = (new Invitation())
            ->select()
            ->where('code', $invite)
            ->first();


        if (!$invitation) {
            return route()->redirect('/register?message=115');
        }

        if ($invitation->user_id) {
            return route()->redirect('/register?message=115');
        }

        (new User())
            ->insert([
                "name" => $name,
                "email" => $email,
                "password" => password_hash($password, PASSWORD_DEFAULT),
                "invitations" => 5
            ])
            ->execute();


        $user = (new User())
            ->select()
            ->where('email', $email)
            ->first();

        if ($invitation->type == 'family') {
            $tenant_id = $invitation->tenant_id;
        } else {
            $tenant_id = $user->id;
            (new Wallet())
                ->insert([
                    "tenant_id" => $tenant_id,
                    "name" => 'Carteira',
                    "amount" => 0
                ])
                ->execute();
        }

        (new User())
            ->update([
                "tenant_id" => $tenant_id
            ])
            ->where('email', $email)
            ->execute();

        (new Invitation())
            ->update([
                "user_id" => $user->id
            ])
            ->where('code', $invite)
            ->execute();

        if (UserMiddleware::login($email, $password)) {
            return route()->redirect('/dashboard');
        }
    }

    public function logout()
    {
        session_destroy();
        return route()->redirect('/');
    }
}
