<?php render('header'); ?>


<div class="pb-3">
  <div class="bg-black text-white">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 mb-5">
          <div class="row py-5 justify-content-center align-items-center">
            <div class="col-12 text-center">
              <a class="text-white" href="<?= route('/'); ?>">
                <i class="fas fa-4x fa-piggy-bank"></i>
              </a>
              <p class="mb-0 mt-4 fs-2">Cadastre-se</p>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <div class="container" style="margin-top: -60px;">
    <div class="row justify-content-center">

      <div class="col-lg-8">
        <div class="row justify-content-center">
          <div class="col-12 col-lg-4 mb-3">
            <div class="card border-0 text-dark">
              <div class="card-body">
                <?php render('message', ["message" => $message]); ?>
                <form method="POST">
                  <div class="mb-3">
                    <label for="name">Nome</label>
                    <input type="text" name="name" id="name" class="form-control border" value="<?= old('name'); ?>">
                  </div>
                  <div class="mb-3">
                    <label for="email">E-mail</label>
                    <input type="text" name="email" id="email" class="form-control border" value="<?= old('email'); ?>">
                  </div>
                  <div class="mb-3">
                    <label for="password">Senha</label>
                    <input type="password" name="password" id="password" class="form-control border">
                  </div>
                  <div class="mb-3">
                    <label for="invite">Convite</label>
                    <input type="text" name="invite" id="invite" class="form-control border" value="<?= $invite ?>">
                  </div>
                  <input type="submit" value="Cadastrar" class="btn btn-dark w-100">

                  <p class="text-center mt-2 mb-0">
                    <a class="text-decoration-none text-muted" href="<?= route('/'); ?>">Já tem uma conta?</a>
                  </p>
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