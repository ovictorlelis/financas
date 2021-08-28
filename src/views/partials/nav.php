<div class="bg-black text-white">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 mb-5">
        <div class="row py-5 justify-content-center align-items-center">
          <div class="col-6 text-start">
            <a class="text-white" href="<?= $base; ?>/dashboard">
              <i class="fas fa-4x fa-piggy-bank"></i>
            </a>
          </div>
          <div class="col-6 text-end">
            <div class="btn-group">
              <button type="button" class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Olá, <?= $user->name ?>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a href="<?= $base; ?>/dashboard" class="dropdown-item">Dashboard</a></li>
                <li><a href="<?= $base; ?>/dashboard/invite" class="dropdown-item">Convites</a></li>
                <li><a href="<?= $base; ?>/dashboard/wallet" class="dropdown-item">Carteira</a></li>
                <li><a href="<?= $base; ?>/dashboard/profile" class="dropdown-item">Perfil</a></li>
                <li><a href="<?= $base; ?>/logout" class="dropdown-item">Sair</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>