<?php render('header'); ?>


<div class="pb-3">
  <?php render('nav', ["user" => $user]); ?>

  <div class="container" style="margin-top: -60px;">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="row">

          <?php if ($wallets) :  ?>
            <?php foreach ($wallets as $wallet) : ?>
              <div class="col-12 col-lg-4 mb-3">
                <div class="card border-0 bg-white text-dark">

                  <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between mb-3">
                      <p class="mb-0 fs-4">
                        <?= ucfirst($wallet->name); ?>
                      </p>
                      <button type="button" onclick="dataRemove('<?= $wallet->id ?>', '<?= $wallet->name ?>')" class="btn-close btn-sm" data-bs-toggle="modal" data-bs-target="#removeWallet">
                      </button>
                    </div>

                    <div class="d-flex align-items-center">
                      <span>R$ &nbsp;</span>
                      <span class="fs-2"><?= number_format($wallet->amount, 2, ',', '.'); ?></span>
                      <a href="" onclick="dataEdit('<?= $wallet->id; ?>', '<?= $wallet->name; ?>', '<?= $wallet->amount; ?>', '<?= $wallet->display; ?>')" class="btn text-muted btn-sm" data-bs-toggle="modal" data-bs-target="#editWallet">
                        <i class="fas fa-pen"></i>
                      </a>
                    </div>

                  </div>

                </div>
              </div>
            <?php endforeach; ?>
          <?php endif;  ?>

        </div>
      </div>
    </div>
  </div>

  <?php render('message', ["message" => $message]); ?>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="row">
          <div class="col-12">
            <div class="row justify-content-between align-items-center">
              <div class="col-lg-3 mb-3 mb-lg-0">
                <a class="text-decoration-none text-dark" href="#" data-bs-toggle="modal" data-bs-target="#addWallet">Adicionar</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="addWallet" tabindex="-1" aria-labelledby="addWalletLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addWalletLabel">Nova carteira</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST">
        <div class="modal-body">
          <div class="mb-3">
            <label for="name">Nome</label>
            <input type="text" name="name" id="name" class="form-control">
          </div>
          <div class="mb-3">
            <label for="amount">Saldo inicial</label>
            <input type="text" name="amount" id="amount" class="form-control">
            <div class="form-text text-center">
              <small>Use o sinal - para negativo e, (vírgula) para casas decimais</small>
            </div>
          </div>
          <div class="mb-3">
            <label for="display">Somar saldo</label>
            <input type="checkbox" name="display" id="display" class="form-check-input d-block" checked>
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

<div class="modal fade" id="editWallet" tabindex="-1" aria-labelledby="editWalletLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editWalletLabel">Editar</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formEditWallet" method="POST">
        <div class="modal-body">
          <input type="hidden" name="edit_id" id="edit_id">
          <div class="mb-3">
            <label for="edit_name">Nome</label>
            <input type="text" name="edit_name" id="edit_name" class="form-control">
          </div>
          <div class="mb-3">
            <label for="edit_amount">Valor</label>
            <input type="text" name="edit_amount" id="edit_amount" class="form-control">
            <div class="form-text text-center">
              <small>Use o sinal - (negativo) para despesas e, (vírgula) para casas decimais</small>
            </div>
          </div>
          <div class="mb-3">
            <label for="edit_display">Somar saldo</label>
            <input type="checkbox" name="edit_display" id="edit_display" class="form-check-input d-block">
          </div>
        </div>
        <div class="row justify-content-center mb-3">
          <div class="col-6">
            <button type="submit" class="btn btn-dark w-100">Alterar</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="removeWallet" tabindex="-1" aria-labelledby="removeWalletLabel" aria-hidden="true">
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
  var id, name, amount;

  var removeTitle = document.querySelector('.remove_title');

  function dataRemove(removeId, title) {
    id = removeId;
    removeTitle.innerHTML = title;
  }

  function dataEdit(editId, editName, editAmount, editDisplay) {
    id = editId;
    name = editName;
    amount = editAmount;
    display = editDisplay;

    let form = document.querySelector("#formEditWallet");

    form.action = "<?= route('/'); ?>dashboard/wallet/edit/" + id + "/" + window.location.search;

    modalEdit();
  }

  function modalEdit() {
    document.querySelector("#edit_id").value = id;
    document.querySelector("#edit_name").value = name;
    document.querySelector("#edit_display").checked = display == 1 ? true : false;

    amount = parseFloat(amount).toLocaleString('pt-BR', {
      minimumFractionDigits: 2,
    })

    document.querySelector("#edit_amount").value = amount;
  }

  function remove() {
    window.location.href = "<?= route('/'); ?>dashboard/wallet/delete/" + id;
  }
</script>

<?php render('footer'); ?>