<?php render('header'); ?>


<div class="pb-3">
  <?php render('nav', ["user" => $user]); ?>

  <div class="container" style="margin-top: -60px;">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="row">
          <div class="col-12 col-lg-4 mb-3">
            <div class="card border-0 bg-white text-dark">
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                  <p class="mb-0 fs-4">Família</p>
                  <i class="fs-4 fas fa-home"></i>
                </div>
                <div class="d-flex align-items-center">
                  <span class="fs-2"><?= $families; ?></span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-4 mb-3">
            <div class="card border-0 bg-white text-dark">
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                  <p class="mb-0 fs-4">Amigos</p>
                  <i class="fs-4 fas fa-user-friends"></i>
                </div>
                <div class="d-flex align-items-center">
                  <span class="fs-2"><?= $friends; ?></span>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-4 mb-3">
            <div class="card border-0 bg-dark text-white">
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                  <p class="mb-0 fs-4">Convites</p>
                  <i class="fs-4 fas fa-stream"></i>
                </div>
                <div class="d-flex align-items-center ">
                  <span class="fs-2"><?= $user->invitations; ?></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">

        <div class="row">
          <div class="col-12">

            <div class="row justify-content-between align-items-center">
              <div class="col-lg-3 mb-3 mb-lg-0">
                <a class="text-decoration-none text-dark" href="#" data-bs-toggle="modal" data-bs-target="#addInvite">Novo convite</a>
              </div>
            </div>

          </div>

          <div class="col-12">
            <?php render('message', ["message" => $message]); ?>
            <?php if ($invitations) :  ?>
              <div class="table-responsive">
                <table>
                  <thead>
                    <tr>
                      <th>Convite</th>
                      <th>Tipo</th>
                      <th></th>
                    </tr>
                  </thead>

                  <tbody>

                    <?php if ($boss) : ?>
                      <tr>
                        <td><?= $boss->name; ?></td>
                        <td>Administrador</td>
                        <td>
                        </td>
                      </tr>
                    <?php endif; ?>

                    <?php foreach ($invitations as $invitation) : ?>
                      <tr>
                        <td><?= $invitation->user->name ?? route('/') . "register?invite=" . $invitation->code; ?></td>
                        <td><?= $invitation->type == 'friend' ? 'Amigo' : 'Família'; ?></td>

                        <td>
                          <?php if ($invitation->type == 'family' || !isset($invitation->user) && $invitation->type == 'friend') : ?>
                            <a href="#" class="text-danger" onclick="dataRemove('<?= $invitation->id ?>')" data-bs-toggle="modal" data-bs-target="#removeTransaction"><i class="fas fa-trash"></i></a>
                          <?php endif; ?>
                        </td>

                      </tr>
                    <?php endforeach; ?>

                  </tbody>

                </table>
              </div>
            <?php else :  ?>
              <p class="text-center py-5">Nenhuma convite cadastrado!</p>
            <?php endif;  ?>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="addInvite" tabindex="-1" aria-labelledby="addInviteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addInviteLabel">Novo convite</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST">
        <div class="modal-body">
          <div class="mb-3">
            <label for="type">Tipo</label>
            <select name="type" id="type" class="form-control">
              <option value="friend">Amigo</option>
              <option value="family">Família</option>
            </select>
          </div>
        </div>
        <div class="row justify-content-center mb-3">
          <div class="col-6">
            <button type="submit" class="btn btn-dark w-100">Criar convite</button>
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
        <p class="text-center">Deseja remover esse convite ?</p>
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
  var id;
  var description;
  var amount;
  var date;
  var proof;
  var type;

  var removeTitle = document.querySelector('.remove_title');

  function dataRemove(removeId) {
    id = removeId;
  }

  function dataEdit(event) {
    id = event.target.parentNode.getAttribute('data-id');
    description = event.target.parentNode.getAttribute('data-description');
    amount = event.target.parentNode.getAttribute('data-amount');
    date = event.target.parentNode.getAttribute('data-date');
    type = event.target.parentNode.getAttribute('data-type');

    proof = event.target.parentNode.getAttribute('data-proof');

    modalEdit();
  }

  function remove() {
    window.location.href = "<?= route('/'); ?>dashboard/invite/delete/" + id;
  }

  function modalEdit() {
    document.querySelector("#edit_id").value = id;
    document.querySelector("#edit_description").value = description;

    if (type == 'expense') {
      amount = '-' + amount;
    }

    amount = parseFloat(amount).toLocaleString('pt-BR', {
      minimumFractionDigits: 2,
    })

    document.querySelector("#edit_amount").value = amount;

    document.querySelector("#edit_date").value = date;

    if (proof !== null) {
      document.querySelector("#edit_proof").src = proof;
    }
  }

  function clear() {
    id = 0;
    description = '';
    removeTitle.innerHTML = '';
  }
</script>

<?php render('footer'); ?>