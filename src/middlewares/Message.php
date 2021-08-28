<?php

namespace src\middlewares;

class Message
{
  public static function get()
  {
    $message = $_GET['message'] ?? '';

    if ($message) {
      $message = self::getMessage($message);
    }

    return $message;
  }

  private static function getMessage($code)
  {
    switch ($code) {
      case 105:
        return 'Preencha seu e-mail e senha!';
        break;
      case 110:
        return 'E-mail e senha inválido!';
        break;
      case 115:
        return 'Convite inválido!';
        break;
      case 215:
        return 'Perfil alterado com sucesso!';
        break;

      case 905:
        return 'Preencha os campos obrigatórios!';
        break;

      case 510:
        return 'Transação adicionada com sucesso!';
        break;
      case 515:
        return 'Transação removida com sucesso!';
        break;
      case 520:
        return 'Transação alterada com sucesso!';
        break;
      case 530:
        return 'Transação marcado como pago!';
        break;
      case 540:
        return 'Transação marcado como não pago!';
        break;

      case 610:
        return 'Carteira adicionada com sucesso!';
        break;
      case 615:
        return 'Carteira removida com sucesso!';
        break;
      case 620:
        return 'Carteira alterada com sucesso!';
        break;
      case 620:
        return 'Você não pode remover sua última carteira!';
        break;

      case 650:
        return 'Ops! Você não tem permissão!';
        break;

      case 710:
        return 'Convite adicionada com sucesso!';
        break;
      case 715:
        return 'Convite removido com sucesso!';
        break;
      case 730:
        return 'Você não pode adicionar família ao grupo!';
        break;
      case 750:
        return 'Ops! Você não tem convites!';
        break;

      default:
        return 'Error inesperado';
        break;
    }
  }
}
