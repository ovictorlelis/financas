<?php render('header'); ?>


<div class="pb-3">
  <?php render('nav', ["user" => $user]); ?>
  <div class="container" style="margin-top: -60px;">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="row justify-content-center">
          <div class="col-12 col-lg-4 mb-3">
            <div class="card border-0 text-dark">
              <div class="card-body">
                <h1 class="text-center">Perfil</h1>
                <?php render('message', ["message" => $message]); ?>
                <form method="POST">
                  <div class="row mt-3">
                    <div class="col-12">
                      <div class="mb-3">
                        <label for="name">Nome</label>
                        <input type="text" name="name" id="name" class="form-control border" value="<?= $user->name; ?>">
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="mb-3">
                        <label for="email">E-mail</label>
                        <input type="text" name="email" id="email" class="form-control border" value="<?= $user->email; ?>">
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="mb-3">
                        <label for="password">Alterar senha</label>
                        <input type="password" name="password" id="password" class="form-control border">
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="mb-3">
                        <input type="submit" class="btn btn-dark w-100" value="Salvar">
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

</div>

<?php render('footer'); ?>