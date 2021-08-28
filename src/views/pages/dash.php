<?php $render('header'); ?>


<div class="pb-3">

  <?php $render('nav', ["user" => $user]); ?>

  <div class="container" style="margin-top: -60px;">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="row">
          <div class="col-12 col-lg-4 mb-3">
            <div class="card border-0 bg-white text-dark">
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                  <p class="mb-0 fs-4">Receita</p>
                  <i class="fs-4 text-success fas fa-arrow-up"></i>
                </div>
                <div class="d-flex align-items-center text-success">
                  <span>R$ &nbsp;</span>
                  <span class="fs-2"><?= $incomes; ?></span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-4 mb-3">
            <div class="card border-0 bg-white text-dark">
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                  <p class="mb-0 fs-4">Despesa</p>
                  <i class="fs-4 text-danger fas fa-arrow-down"></i>
                </div>
                <div class="d-flex align-items-center text-danger">
                  <span>R$ &nbsp;</span>
                  <span class="fs-2"><?= $expenses; ?></span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-4 mb-3">
            <div class="card border-0 bg-dark text-white">
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                  <p class="mb-0 fs-4">Saldo</p>
                  <i class="fs-4 fas fa-dollar-sign"></i>
                </div>
                <div class="d-flex align-items-center ">
                  <span>R$ &nbsp;</span>
                  <span class="fs-2"><?= $total; ?></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <form method="GET">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">

          <div class="row">
            <div class="col-12">


              <div class="row justify-content-between align-items-center">
                <div class="col-lg-3 mb-3 mb-lg-0">
                  <a class="text-decoration-none text-dark" href="#" data-bs-toggle="modal" data-bs-target="#addTransaction">Nova transação</a>
                </div>
              </div>

              <div class="my-3 row justify-content-between align-items-center">
                <div class="col-lg-12">
                  <div class="row align-items-center">
                    <div class="col-lg-3 mb-lg-0 mb-2">
                      <input type="text" name="s" class="form-control" placeholder="Pesquisar" value="<?= $search ?? '' ?>">
                    </div>
                    <div class="col-lg-3 mb-lg-0 mb-2">
                      <select name="f" class="form-control">
                        <option value="all" <?= isset($filter) && $filter == 'all' ? 'selected' : '' ?>>Todos</option>
                        <option value="income" <?= isset($filter) && $filter == 'income' ? 'selected' : '' ?>>Receita</option>
                        <option value="expense" <?= isset($filter) && $filter == 'expense' ? 'selected' : '' ?>>Despesa</option>
                      </select>
                    </div>
                    <div class="col-lg-3 mb-lg-0 mb-2">
                      <input type="text" name="d" class="form-control" id="date_search" value="<?= $date ?? ''; ?>">
                    </div>
                    <div class="col-lg-3 mb-lg-0 mb-2">
                      <input type="submit" class="btn btn-dark w-100" value="Pesqusiar">
                    </div>
                  </div>
                </div>
              </div>

            </div>

            <div class="col-12">

              <?php $render('message', ["message" => $message]); ?>

              <?php if ($transactions) :  ?>
                <div class="table-responsive">
                  <table>
                    <thead>
                      <tr>
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Data</th>
                        <th></th>
                      </tr>
                    </thead>

                    <tbody>

                      <?php foreach ($transactions as $transaction) : ?>
                        <tr>
                          <td><?= ucfirst($transaction['description']); ?></td>
                          <td class="<?= $transaction["type"] == 'expense' ? 'text-danger' : 'text-success'; ?>">
                            R$ <?= number_format($transaction['amount'], 2, ',', '.'); ?>
                          </td>
                          <td class="text-muted">
                            <?= (new DateTime($transaction['date']))->format('d/m/Y \á\s H:i'); ?>
                          </td>
                          <td>
                            <a href="#" class="text-dark me-2" onclick="dataEdit('<?= $transaction['id']; ?>','<?= ucfirst($transaction['description']); ?>', '<?= $transaction['amount']; ?>', '<?= (new DateTime($transaction['date']))->format('Y-m-d');; ?>', '<?= $transaction['type']; ?>', '<?= $transaction['wallet_id']; ?>', '<?= $transaction['paid']; ?>')" data-bs-toggle="modal" data-bs-target="#viewTransaction"><i class="fas fa-expand"></i></a>

                            <a href="#" class="text-danger" onclick="dataRemove('<?= $transaction['id']; ?>', '<?= $transaction['description'] ?>')" data-bs-toggle="modal" data-bs-target="#removeTransaction"><i class="fas fa-trash"></i></a>
                          </td>
                        </tr>
                      <?php endforeach; ?>

                    </tbody>

                  </table>
                </div>
              <?php else :  ?>
                <p class="text-center py-5">Nenhuma transação cadastrada!</p>
              <?php endif;  ?>
            </div>
          </div>

        </div>
      </div>
    </div>
</div>
</form>


<!-- <div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="row">
        <div class="col-12">
          <nav class="d-flex justify-content-center">
            <ul class="pagination">
              <li class="page-item active" aria-current="page">
                <span class="page-link">1</span>
              </li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div> -->


<div class="modal fade" id="addTransaction" tabindex="-1" aria-labelledby="addTransactionLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addTransactionLabel">Nova transação</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST">
        <div class="modal-body">
          <div class="mb-3">
            <label for="description">Descrição</label>
            <input type="text" name="description" id="description" class="form-control">
          </div>
          <div class="mb-3">
            <label for="amount">Valor</label>
            <input type="text" name="amount" id="amount" class="form-control">
            <div class="form-text text-center">
              <small>Use o sinal - (negativo) para despesas e, (vírgula) para casas decimais</small>
            </div>
          </div>
          <div class="mb-3">
            <label for="wallet">Carteira</label>
            <select class="form-control" name="wallet" id="wallet">
              <?php foreach ($wallets as $wallet) :  ?>
                <option value="<?= $wallet['id']; ?>"><?= $wallet['name']; ?></option>
              <?php endforeach;  ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="date">Data</label>
            <input type="date" name="date" id="date" class="form-control" value="<?= date('Y-m-d'); ?>">
          </div>
        </div>
        <div class="row justify-content-center mb-3">
          <div class="col-6">
            <button type="submit" class="btn btn-dark w-100">Adicionar</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="viewTransaction" tabindex="-1" aria-labelledby="viewTransactionLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewTransactionLabel">Transação</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editWallet" method="POST">
        <div class="modal-body">
          <input type="hidden" name="edit_id" id="edit_id">
          <div class="mb-3">
            <label for="edit_description">Descrição</label>
            <input type="text" name="edit_description" id="edit_description" class="form-control">
          </div>
          <div class="mb-3">
            <label for="edit_amount">Valor</label>
            <input type="text" name="edit_amount" id="edit_amount" class="form-control">
            <div class="form-text text-center">
              <small>Use o sinal - (negativo) para despesas e, (vírgula) para casas decimais</small>
            </div>
          </div>
          <div class="mb-3">
            <label for="edit_wallet">Carteira</label>
            <select class="form-control" name="edit_wallet" id="edit_wallet">
              <?php foreach ($wallets as $wallet) :  ?>
                <option id="<?= $wallet['id']; ?>" value="<?= $wallet['id']; ?>"><?= $wallet['name']; ?></option>
              <?php endforeach;  ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="edit_date">Data</label>
            <input type="date" name="edit_date" id="edit_date" class="form-control">
          </div>
        </div>
        <div class="row justify-content-center px-3 mb-3">
          <div class="col-6">
            <button type="submit" class="btn btn-dark w-100">Alterar</button>
          </div>
          <div class="col-6">
            <button id="paidTrue" onclick="paidButton(true)" type="button" class="btn btn-success w-100">Pago</button>
            <button id="paidFalse" onclick="paidButton(false)" type="button" class="btn btn-danger w-100">Não Pago</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="removeTransaction" tabindex="-1" aria-labelledby="removeTransactionLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
        <p class="text-center">Deseja remover <span class="remove_title"></span>?</p>
        <div class="row">
          <div class="col-6">
            <button type="button" class="btn btn-success w-100" data-bs-dismiss="modal">Nao</button>
          </div>
          <div class="col-6">
            <button type="button" class="btn btn-danger w-100" onclick="remove()">Sim</button>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<script>
  var id, description, amount, date, type, walletId, paid;
  var removeTitle = document.querySelector('.remove_title');
  var editWallets = Array.from(document.querySelector("#edit_wallet").children);
  var paidTrue = document.querySelector('#paidTrue');
  var paidFalse = document.querySelector('#paidFalse');

  paidTrue.style.display = 'none';
  paidFalse.style.display = 'none';

  function dataRemove(removeId, removeDescription) {
    id = removeId;
    description = removeDescription;
    removeTitle.innerHTML = description;
  }

  function dataEdit(editId, editDescription, editAmount, editDate, editType, editWallet, editPaid) {
    id = editId;
    description = editDescription;
    amount = editAmount;
    date = editDate;
    type = editType;
    walletId = editWallet;
    paid = editPaid;

    let form = document.querySelector("#editWallet");

    form.action = "<?= $base; ?>/dashboard/edit/" + id + "/" + window.location.search;


    if (editPaid == 1) {
      paidFalse.style.display = 'block';
      paidTrue.style.display = 'none';
    } else {
      paidFalse.style.display = 'none';
      paidTrue.style.display = 'block';
    }

    editWallets.forEach(wallet => {
      wallet.removeAttribute('selected');
    })

    modalEdit();
  }


  function modalEdit() {
    document.querySelector("#edit_id").value = id;
    document.querySelector("#edit_description").value = description;



    editWallets.forEach(wallet => {
      if (wallet.id == walletId) {
        wallet.setAttribute('selected', true);
      }
    })

    if (type == 'expense') {
      amount = '-' + amount;
    }

    amount = parseFloat(amount).toLocaleString('pt-BR', {
      minimumFractionDigits: 2,
    })

    document.querySelector("#edit_amount").value = amount;
    document.querySelector("#edit_date").value = date;
  }

  function paidButton(bool) {
    window.location.href = "<?= $base; ?>/dashboard/paid/" + id + "/" + bool + "/" + window.location.search;
  }

  function remove() {
    window.location.href = "<?= $base; ?>/dashboard/delete/" + id + "/" + window.location.search;
  }
</script>

<script src="https://unpkg.com/imask"></script>

<script>
  var element = document.getElementById('date_search');
  var maskOptions = {
    mask: '00/0000'
  };
  var mask = IMask(element, maskOptions);
</script>

<?php $render('footer'); ?>