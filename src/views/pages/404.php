<?php $render('header'); ?>


<div class="pb-3">
  <div class="bg-black text-white">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 mb-5">
          <div class="row py-5 justify-content-center align-items-center">
            <div class="col-12 text-center">
              <a class="text-white" href="<?= $base; ?>">
                <i class="fas fa-4x fa-piggy-bank"></i>
              </a>
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
              <div class="card-body text-center">
                <h1><span class="fw-bold">Ops!</span></h1>
                <p>Página não encontrada!</p>
                <a class="text-decoration-none text-dark" href="<?= $base; ?>/">Voltar</a>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

</div>

<?php $render('footer'); ?>