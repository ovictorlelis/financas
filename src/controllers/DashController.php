<?php

namespace src\controllers;

use \core\Controller;
use src\middlewares\Message;
use src\middlewares\UserMiddleware;
use src\models\Invitation;
use src\models\Transaction;
use src\models\User;
use src\models\Wallet;

class DashController extends Controller
{
    public $user;

    public function __construct()
    {
        if (!UserMiddleware::auth()) {
            $this->redirect('/');
        }

        $this->user = (object) UserMiddleware::auth();
    }

    public function index()
    {
        $message = Message::get();

        $search = filter_input(INPUT_GET, 's', FILTER_SANITIZE_STRING) ?? '';
        $filter = filter_input(INPUT_GET, 'f', FILTER_SANITIZE_STRING) ?? 'all';
        $date = filter_input(INPUT_GET, 'd', FILTER_SANITIZE_STRING) ?? date('m') . '/' . date('Y');

        $filter = $filter == 'all' ? "%%" : $filter;

        $dateSearch = explode("/", $date);
        $dateSearch = $dateSearch[1] . '-' . $dateSearch[0];

        $transaction = new Transaction();
        $transactions = $transaction
            ->select()
            ->where('tenant_id', $this->user->tenant_id)
            ->where('description', 'LIKE', "%{$search}%")
            ->where('type', 'LIKE', "{$filter}")
            ->where('date', 'LIKE', "%{$dateSearch}%")
            ->orderBy(['date', 'id'], 'desc')
            ->get();

        $incomes = $transaction
            ->select()
            ->where('tenant_id', $this->user->tenant_id)
            ->where('type', 'LIKE', "income")
            ->where('date', 'LIKE', "%{$dateSearch}%")
            ->get();

        $amountIncome = 0;
        foreach ($incomes as $income) {
            $amountIncome += $income['amount'];
        }

        $expenses = $transaction
            ->select()
            ->where('tenant_id', $this->user->tenant_id)
            ->where('type', 'LIKE', "expense")
            ->where('date', 'LIKE', "%{$dateSearch}%")
            ->get();;

        $amountExpense = 0;
        foreach ($expenses as $expense) {
            $amountExpense += $expense['amount'];
        }

        $wallets = (new Wallet())
            ->select()
            ->where('tenant_id', $this->user->tenant_id)
            ->get();

        $total = 0;

        foreach ($wallets as $wallet) {
            $total += (float) $wallet['amount'];
        }

        $this->render('dash', [
            "user" => $this->user,
            "transactions" => $transactions,
            "wallets" => $wallets,
            "incomes" => number_format($amountIncome, 2, ',', '.'),
            "expenses" => number_format($amountExpense, 2, ',', '.'),
            "total" => number_format($total, 2, ',', '.'),
            "search" => $search,
            "filter" => $filter,
            "date" => $date,
            "message" => $message
        ]);
    }

    public function invite()
    {
        $message = Message::get();

        $familiesItems = (new Invitation())
            ->select()
            ->where('tenant_id', $this->user->tenant_id)
            ->where('type', 'family')
            ->get();

        $familyBoss = array();
        if ($this->user->id != $this->user->tenant_id) {
            $familyBoss = (new User())
                ->select()
                ->where("id", $this->user->tenant_id)
                ->first();
        }

        $friendsItems = (new Invitation())
            ->select()
            ->where('tenant_id', $this->user->id)
            ->where('type', 'friend')
            ->get();

        $invitations = array_merge($familiesItems, $friendsItems);


        foreach ($invitations as $key => $invitation) {
            if ($invitation['user_id']) {
                $user = (new User())->select()->where('id', $invitation['user_id'])->first();
                $invitations[$key]["user"] = [
                    "name" => $user['id'] == $this->user->id ? 'Você' : $user['name'],
                    "email" => $user['email'],
                ];
            }
        }

        $friends = (new Invitation())
            ->select()
            ->where('tenant_id', $this->user->id)
            ->where('type', 'friend')
            ->get();

        $families = (new Invitation())
            ->select()
            ->where('tenant_id', $this->user->tenant_id)
            ->where('type', 'family')
            ->get();


        $this->render('invite', [
            "user" => $this->user,
            "invitations" => $invitations,
            "friends" => count($friends),
            "families" => count($families),
            "boss" => $familyBoss,
            "message" => $message
        ]);
    }

    public function createInvite()
    {
        $type = filter_input(INPUT_POST, 'type', FILTER_DEFAULT);
        $code = md5(rand(0, 555) . $this->user->email . rand(0, 555) . date('YmdHis'));

        if ($this->user->invitations < 1) {
            $this->redirect("/dashboard/invite?message=750");
        }

        if ($type != 'family' && $type != 'friend') {
            $this->redirect("/dashboard/invite?message=650");
        }

        if ($type == 'family' && $this->user->id != $this->user->tenant_id) {
            $this->redirect("/dashboard/invite?message=730");
        }

        (new User())
            ->update([
                'invitations' => $this->user->invitations - 1
            ])
            ->where('id', $this->user->id)
            ->execute();

        (new Invitation)
            ->insert([
                "tenant_id" => $this->user->id,
                "type" => $type,
                "code" => $code
            ])
            ->execute();

        $this->redirect("/dashboard/invite?message=710");
    }

    public function deleteInvite($params)
    {
        $id = filter_var($params['id'], FILTER_VALIDATE_INT);

        $invite = (new Invitation)
            ->select()
            ->where('id', $id)
            ->where('tenant_id', $this->user->id)
            ->first();

        if ($id != $invite['id']) {
            $this->redirect("/dashboard/invite?message=650");
        }


        if (!$invite['user_id']) {
            (new User())
                ->update([
                    'invitations' => $this->user->invitations + 1
                ])
                ->where('id', $this->user->id)
                ->execute();


            (new Invitation())
                ->delete()
                ->where('id', $id)
                ->execute();

            $this->redirect("/dashboard/invite?message=715");
        }

        if ($invite['type'] == 'family' && $invite['user_id']) {

            (new Invitation())
                ->update([
                    "type" => 'friend'
                ])
                ->where('id', $id)
                ->where('tenant_id', $this->user->id)
                ->execute();

            (new User())
                ->update([
                    "tenant_id" => $invite['user_id']
                ])
                ->where("id", $invite['user_id'])
                ->execute();

            (new Wallet())
                ->insert([
                    "tenant_id" => $invite['user_id'],
                    "name" => 'Carteira',
                    "amount" => 0
                ])
                ->execute();

            $this->redirect("/dashboard/invite?message=715");
        }
        $this->redirect("/dashboard/invite?message=715");
    }

    public function wallet()
    {
        $message = Message::get();
        $wallets = (new Wallet())
            ->select()
            ->where('tenant_id', $this->user->tenant_id)
            ->get();
        $this->render('wallet', [
            "user" => $this->user,
            "wallets" => $wallets,
            "message" => $message
        ]);
    }

    public function editWallet($params)
    {
        $id = filter_input(INPUT_POST, 'edit_id', FILTER_VALIDATE_INT);
        $name = filter_input(INPUT_POST, 'edit_name', FILTER_SANITIZE_STRING);
        $amount = filter_input(INPUT_POST, 'edit_amount', FILTER_SANITIZE_STRING);

        if (!$id || !$name && $id != $params['id']) {
            $this->redirect('/dashboard/wallet?message=905');
        }

        $wallet = (new Wallet())
            ->select()
            ->where('id', $params['id'])
            ->where('tenant_id', $this->user->tenant_id)
            ->first();

        if (!$wallet) {
            $this->redirect('/dashboard/wallet?message=650');
        }

        $amount = $this->getAmount($amount);

        $wallet = (new Wallet())
            ->update([
                "name" => $name,
                "amount" => $amount
            ])
            ->where("id", $params["id"])
            ->execute();

        $this->redirect('/dashboard/wallet?message=620');
    }

    public function createWallet()
    {
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $amount = filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_STRING);

        if (!$name) {
            $this->redirect('/dashboard/wallet?message=905');
        }

        $type = $this->getType($amount);
        $amount = $this->getAmount($amount);

        $wallet = new Wallet();
        $wallet->insert([
            "tenant_id" => $this->user->tenant_id,
            "name" => $name,
            "amount" => $type == 'expense' ? '-' . $amount : $amount
        ])->execute();

        $this->redirect('/dashboard/wallet?message=610');
    }

    public function deleteWallet($id)
    {
        $wallet = (new Wallet())->select()->where('id', $id)->first();
        $userWallets = (new Wallet())
            ->select()
            ->where('tenant_id', $this->user->tenant_id)
            ->get();

        if (count($userWallets) <= 1) {
            $this->redirect('/dashboard/wallet?message=630');
        }

        if ($wallet['tenant_id'] != $this->user->tenant_id) {
            $this->redirect('/dashboard/wallet?message=525');
        }

        $wallet = (new Wallet())->delete()->where('id', $id)->execute();
        $this->redirect('/dashboard/wallet?message=615');
    }

    public function profile()
    {
        $message = Message::get();
        $this->render('profile', [
            "user" => $this->user,
            "message" => $message
        ]);
    }

    public function editProfile()
    {
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        if (!$name || !$email) {
            $this->redirect("/dashboard/profile?message=905");
        }

        (new User())
            ->update([
                "name" => $name,
                "email" => $email
            ])
            ->where('id', $this->user->id)
            ->execute();

        if ($password) {
            (new User())
                ->update([
                    "password" => password_hash($password, PASSWORD_DEFAULT)
                ])
                ->where('id', $this->user->id)
                ->execute();
        }

        $this->redirect("/dashboard/profile?message=215");
    }

    public function create()
    {
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        $amount = filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_STRING);
        $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
        $wallet = filter_input(INPUT_POST, 'wallet', FILTER_VALIDATE_INT);

        $url = $this->getFilters();


        if (!$description || !$amount || !$date) {
            $this->redirect("/dashboard{$url}&message=905");
        }

        $userWallet = (new Wallet())
            ->select()
            ->where('tenant_id', $this->user->id)
            ->where('id', $wallet)
            ->first();

        if (!$userWallet) {
            $this->redirect("/dashboard{$url}&message=910");
        }



        $type = $this->getType($amount);
        $amount = $this->getAmount($amount);

        if ($type == 'income') {
            $balance = $userWallet['amount'] + $amount;
        } else {
            $balance = $userWallet['amount'] - $amount;
        }

        $transaction = new Transaction();
        $transaction
            ->insert([
                "tenant_id" => $this->user->id,
                "description" => $description,
                "amount" => $amount,
                "type" => $type,
                "wallet_id" => $wallet,
                "date" => $date,
            ])
            ->execute();

        $this->redirect("/dashboard{$url}&message=510");
    }

    public function delete($id)
    {

        $url = $this->getFilters();

        $transaction = (new Transaction())->select()->where('id', $id)->first();

        if ($transaction['tenant_id'] != $this->user->tenant_id) {
            $this->redirect("/dashboard{$url}&message=525");
        }

        $walletId = $transaction['wallet_id'];

        $currentWallet = (new Wallet())->select()->where('id', $walletId)->first();


        if ($transaction['type'] == 'income') {
            $currentBalance = $currentWallet['amount'] - $transaction['amount'];
        } else {
            $currentBalance = $currentWallet['amount'] + $transaction['amount'];
        }

        $transactionPaid = (new Transaction())
            ->select()
            ->where('id', $id)
            ->where('paid', 1)
            ->first();

        $transaction = (new Transaction())
            ->delete()
            ->where('id', $id)
            ->execute();

        if ($transactionPaid) {
            $updateWallet = new Wallet();
            $updateWallet
                ->update([
                    "amount" => $currentBalance
                ])
                ->where('id', $walletId)
                ->execute();
        }

        $this->redirect("/dashboard{$url}&message=515");
    }

    public function edit($params)
    {
        $url = $this->getFilters();

        $id = filter_var($params['id'], FILTER_VALIDATE_INT);
        $edit_description = filter_input(INPUT_POST, 'edit_description', FILTER_SANITIZE_STRING);
        $edit_amount = filter_input(INPUT_POST, 'edit_amount', FILTER_SANITIZE_STRING);
        $edit_wallet = filter_input(INPUT_POST, 'edit_wallet', FILTER_SANITIZE_STRING);
        $edit_date = filter_input(INPUT_POST, 'edit_date', FILTER_SANITIZE_STRING);

        $transaction = (new Transaction())
            ->select()
            ->where('id', $id)
            ->where('tenant_id', $this->user->tenant_id)
            ->first();

        if (!$transaction) {
            $this->redirect("/dashboard{$url}&message=650");
        }


        $amount = $this->getAmount($edit_amount);

        $currentTransaction = (new Transaction())
            ->select()
            ->where("id", $id)
            ->where('tenant_id', $this->user->tenant_id)
            ->first();

        if ($currentTransaction['wallet_id'] != $edit_wallet && $currentTransaction['paid'] == 1) {
            $currentWallet = (new Wallet())
                ->select()
                ->where('id', $currentTransaction['wallet_id'])
                ->where('tenant_id', $this->user->tenant_id)
                ->first();

            $balance = $this->newBalance('expense', $currentTransaction['type'], $currentWallet['amount'], $currentTransaction['amount']);

            (new Wallet())
                ->update([
                    "amount" => $balance
                ])
                ->where('id', $currentTransaction['wallet_id'])
                ->execute();

            (new Wallet())
                ->update([
                    "amount" => $amount
                ])
                ->where('id', $edit_wallet)
                ->execute();
        }

        (new Transaction())
            ->update([
                "description" => $edit_description,
                "amount" => $amount,
                "wallet_id" => $edit_wallet,
                "date" => $edit_date,
            ])
            ->where("id", $id)
            ->execute();

        $this->redirect("/dashboard{$url}&message=520");
    }

    public function paid($params)
    {
        $id = filter_var($params['id'], FILTER_VALIDATE_INT);
        $bool = filter_var($params['bool'], FILTER_VALIDATE_BOOLEAN);

        $url = $this->getFilters();

        $transaction = (new Transaction())
            ->select()
            ->where('id', $id)
            ->where('tenant_id', $this->user->tenant_id)
            ->first();

        if (!$transaction) {
            $this->redirect("/dashboard{$url}?message=650");
        }

        $walletId = $transaction['wallet_id'];
        $transactionAmount = $transaction['amount'];
        $transactionType = $transaction['type'];

        $wallet = (new Wallet())
            ->select()
            ->where('id', $walletId)
            ->first();

        if ($bool == 1) {
            $type = 'income';
            $transaction = (new Transaction())
                ->update([
                    'paid' => 1
                ])
                ->where('id', $id)
                ->execute();
        } else {
            $type = 'expense';
            $transaction = (new Transaction())
                ->update([
                    'paid' => 0
                ])
                ->where('id', $id)
                ->execute();
        }

        $balance = $this->newBalance($type, $transactionType, $wallet['amount'], $transactionAmount);

        $this->updateWallet($balance, $walletId);
        $bool = $bool == 1 ? 530 : 540;
        $this->redirect("/dashboard{$url}&message={$bool}");
    }

    private function newBalance($type, $transactionType,  $currentAmount, $amount)
    {
        if ($transactionType == 'income') {
            if ($type == 'income') {
                $balance =  $currentAmount + $amount;
            } else {
                $balance =  $currentAmount - $amount;
            }
        } else {
            if ($type == 'income') {
                $balance =  $currentAmount - $amount;
            } else {
                $balance =  $amount + $currentAmount;
            }
        }

        return $balance;
    }

    private function getBalance($type, $currentAmount, $amount)
    {
        $type = $this->getType($amount);
        $amount = $this->getAmount($amount);


        if ($type == 'income') {
            $balance = $currentAmount + $amount;
        } else {
            $balance = $currentAmount - $amount;
        }

        return $balance;
    }

    private function updateWallet($balance, $walletId)
    {
        $updateWallet = new Wallet();
        $updateWallet
            ->update([
                "amount" => $balance
            ])
            ->where('id', $walletId)
            ->execute();
    }

    private function getType($amount)
    {
        return strpos($amount, '-') !== false ? 'expense' : 'income';
    }

    private function getAmount($amount)
    {
        $amount = str_replace('-', '', $amount);
        $amount = str_replace(".", "", $amount);
        $amount = str_replace(",", ".", $amount);
        if (!is_numeric($amount)) {
            $amount = 0;
        }
        return $amount;
    }

    private function getFilters()
    {
        $search = filter_input(INPUT_GET, 's', FILTER_SANITIZE_STRING) ?? '';
        $filter = filter_input(INPUT_GET, 'f', FILTER_SANITIZE_STRING) ?? 'all';
        $date = filter_input(INPUT_GET, 'd', FILTER_SANITIZE_STRING) ?? date('m') . '/' . date('Y');

        return "?s={$search}&f={$filter}&d={$date}";
    }
}
