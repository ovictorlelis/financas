<?php

namespace src\controllers;

use \core\Controller;
use src\middlewares\Message;
use src\middlewares\UserMiddleware;
use src\models\Invitation;
use src\models\User;
use src\models\Wallet;

class HomeController extends Controller
{

    public function index()
    {
        $message = Message::get();

        if (UserMiddleware::auth()) {
            $this->redirect('/dashboard');
        }

        $this->render('login', [
            'message' => $message
        ]);
    }

    public function login()
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $pass = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        if (!$email && !$pass) {
            $this->redirect('/?message=105');
        }

        if (UserMiddleware::login($email, $pass)) {
            $this->redirect('/dashboard');
        }
        $this->redirect('/?message=110');
    }

    public function register()
    {
        $message = Message::get();
        $invite = filter_input(INPUT_GET, 'invite', FILTER_SANITIZE_STRING) ?? '';
        $this->render('register', [
            "invite" => $invite,
            "message" => $message
        ]);
    }

    public function create()
    {
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $invite = filter_input(INPUT_POST, 'invite', FILTER_SANITIZE_STRING);

        $invitation = (new Invitation())
            ->select()
            ->where('code', $invite)
            ->first();


        if (!$invitation) {
            $this->redirect('/register?message=115');
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

        if ($invitation['type'] == 'family') {
            $tenant_id = $invitation['tenant_id'];
        } else {
            $tenant_id = $user['id'];
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
                "user_id" => $user['id']
            ])
            ->where('code', $invite)
            ->execute();

        if (UserMiddleware::login($email, $password)) {
            $this->redirect('/dashboard');
        }
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('/');
    }
}
