<div class="container-fluid my-4">
  <div class="card shadow border-0">
    <div class="card-header bg-orfeo text-white d-flex align-items-center gap-2">
      <i class="fa fa-search fs-5"></i>
      <h5 class="fw-semibold">
        Búsqueda de Radicados
      </h5>
    </div>

    <div class="card-body">
      <form name="form_busq_rad"
        action="<?= $pagina_actual ?>?<?= session_name() . '=' . session_id() ?>&estado_sal=<?= $estado_sal ?>&tpAnulacion=<?= $tpAnulacion ?>&estado_sal_max=<?= $estado_sal_max ?>&pagina_sig=<?= $pagina_sig ?>&dep_sel=<?= $dep_sel ?>&nomcarpeta=<?= $nomcarpeta ?>"
        method="POST">

        <!-- Tipo de búsqueda -->
        <div class="row g-3 mb-3">
          <div class="col-md-4">
            <label class="form-label fw-semibold">Tipo de Envío</label>
            <select name="tipoEnvio" class="form-select">
              <option value="">Búsqueda normal</option>
              <option value="E-mail">Buscar por E-mail</option>
              <option value="Físico">Buscar por Físico</option>
            </select>
          </div>

          <!-- Radicado -->
          <div class="col-md-8">
            <label class="form-label fw-semibold">
              Radicado(s)
              <small class="text-muted">(Separados por coma)</small>
            </label>

            <div class="input-group">
              <span class="input-group-text">
                <i class="bi bi-search"></i>
              </span>
              <input type="text"
                name="busqRadicados"
                class="form-control"
                placeholder="Ej: 202400123, 202400456"
                value="<?= $busqRadicados ?>">
            </div>

            <div class="form-check mt-2">
              <input class="form-check-input"
                type="checkbox"
                name="busqueda_avanzada"
                value="1"
                id="busquedaAvanzada">
              <label class="form-check-label" for="busquedaAvanzada">
                Activar búsqueda avanzada
              </label>
            </div>
          </div>
        </div>

        <!-- Selector de Usuario -->
        <?php if (isset($selectorUsuariosDependencia)): ?>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Usuario de Dependencia</label>

              <select name="usuarioDependencia" id="usuarioDependencia" class="form-select">
                <option value="">Seleccionar usuario</option>
                <?php foreach ($usuarios as $usuario): ?>
                  <option value="<?= $usuario['USUA_CODI'] ?>"
                    <?= $usuario['USUA_CODI'] == $usuarioDependencia ? 'selected' : '' ?>>
                    <?= $usuario['USUA_NOMB'] ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        <?php endif; ?>

        <!-- Campos ocultos -->
        <input type="hidden" name="usuaCodiEnvio" value="<?= $usuaCodiEnvio ?>">
        <input type="hidden" name="estado_sal" value="<?= $estado_sal ?>">
        <input type="hidden" name="porEnviar" value="<?= $porEnviar ?>">

        <!-- Botón -->
        <div class="d-flex justify-content-end mt-4">
          <button type="submit" name="Buscar" class="btn btn-success px-4">
            <i class="bi bi-search me-1"></i> Buscar
          </button>
        </div>

      </form>
    </div>
  </div>
</div>