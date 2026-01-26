
<html lang="es">
<head>
	<meta charset="utf-8">
	<title> ..:: <!--{$entidad}--> ::.</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="<!--{$entidad}-->">
	<meta name="author" content="Correlibre:Osmar Castillo oacastillol@gmail.com">
	<!--Si existe un favicon especifico para la entidad su nombre debe de ser asi <entidad>.favicon.png,
	     si no existe se toma el favicon por defecto-->
	<!-- Bootstrap core CSS -->
	<!-- font-awesome CSS -->
	<link href="./estilos/font-awesome.css" rel="stylesheet">
	<!-- Bootstrap core CSS -->
	<link href="themes/<!--{$tema}-->/css/estilo.css" rel="stylesheet">
	<!-- Bootstrap 5.3.5 -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

	<style>
        .navbar-custom {
            background-color: <!--{$colorFondo}-->;
        }

        body {
            overflow-y: hidden;
        }

        #imageLoad {
            background: url('imagenes/reload.gif') no-repeat center center;
            height: 256px;
            width: 256px;
            position: absolute;
            top: 50%;
            left: 50%;
            margin-top: -128px;
            margin-left: -128px;
        }
    </style>
	<style>
	 .panel-body { padding:0px; };
	 .panel-body table tr td { padding-left: 15px };
	 .panel-body .table {margin-bottom: 0px; };
	 .container.custom-container {
	     padding: 0 50px;
	 }
	</style>

	<!-- ChartJS -->
	<script type="text/javascript" src="./include/chartjs/Chart.min.js"> </script>
	<script type="text/javascript" src="./include/chartjs/Chart.bundle.js"></script>
	<script type="text/javascript" src="./include/chartjs/utils.js"></script>
</head>

<body>
	<!-- AMBIENTE -->
    <!--{if $ambiente != "PRODUCCION"}-->
    <div class="position-absolute top-0 start-50 translate-middle-x bg-danger text-white px-3 py-1 fw-bold">
        ..:: Ambiente de: <!--{$ambiente}--> ::..
    </div>
    <!--{/if}-->

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg" role="navigation">
        <div class="container-fluid">
			<!-- Logo -->
            <a class="navbar-brand d-flex align-items-center logo" onclick="location.reload()" href="#">
                <img src="<!--{$logoEntidad}-->" height="80" class="d-inline-block align-text-top" id="logo_superargo">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#orfeo-navbar-collapse-1" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<!-- Menú principal -->
            <div class="collapse navbar-collapse" id="orfeo-navbar-collapse-1">

                <!-- MENÚ IZQUIERDO -->
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<!--{if $menuAcciones == 1}-->
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="accionesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								<!--{$acciones.nombre}-->
							</a>
							<ul class="dropdown-menu" aria-labelledby="accionesDropdown">
								<!--{foreach from=$acciones.menu item=menu}-->
									<!--{if $menu.subMenu == 0}-->
										<li><a class="dropdown-item" href="<!--{$menu.url}-->" target="mainFrame"><!--{$menu.nombre}--></a></li>
									<!--{elseif $menu.subMenu == 1}-->
										<li class="dropdown-submenu">
											<a href="<!--{$menu.url}-->" class="menu_princ dropdown-item dropdown-toggle"><!--{$menu.nombre}--></a>
											<ul class="dropdown-menu">
												<!--{foreach from=$menu.sub item=item}-->
													<li>
														<a href=<!--{$item.url}--> target='mainFrame' class="dropdown-item vinculos"><!--{$item.nombre}--></a>
													</li>
												<!--{/foreach}-->
											</ul>
										</li>
									<!--{/if}-->
								<!--{/foreach}-->
							</ul>
						</li>
					<!--{/if}-->

					<!--{if $menuAdministracion == 1}-->
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="administracionDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								<!--{$administracion.nombre}--> <b class="caret"></b>
							</a>
							<ul class="dropdown-menu" aria-labelledby="administracionDropdown">
								<!--{foreach from=$administracion.menu item=menu}-->
									<!--{if $menu.subMenu == 0}-->
										<li><a class="dropdown-item" href="<!--{$menu.url}-->" target='mainFrame'><!--{$menu.nombre}--></a></li>
									<!--{elseif $menu.subMenu == 1}-->
										<li class="dropdown-submenu">
											<a href="<!--{$menu.url}-->" class="menu_princ dropdown-item dropdown-toggle"><!--{$menu.nombre}--></a>
											<ul class="dropdown-menu">
												<!--{foreach from=$menu.sub item=item}-->
												<li>
													<a href=<!--{$item.url}--> target='mainFrame' class="dropdown-item vinculos"><!--{$item.nombre}--></a>
												</li>
												<!--{/foreach}-->
											</ul>
										</li>
									<!--{/if}-->
								<!--{/foreach}-->
							</ul>
						</li>
					<!--{/if}-->
					
					<!--{if $menuRadicacion == 1}-->
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="radicacionDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								<!--{$radicacion.nombre}--> <b class="caret"></b>
							</a>
							<ul class="dropdown-menu" aria-labelledby="radicacionDropdown">
								<!--{foreach from=$radicacion.menu item=menu}-->
									<!--{if $menu.subMenu == 0}-->
										<li><a class="dropdown-item" href=<!--{$menu.url}--> target='mainFrame'><!--{$menu.nombre}--></a></li>
									<!--{elseif $menu.subMenu == 1}-->
										<li class="dropdown-submenu">
											<a href="<!--{$menu.url}-->" class="menu_princ dropdown-item dropdown-toggle"><!--{$menu.nombre}--></a>
											<ul class="dropdown-menu">
												<!--{foreach from=$menu.sub item=item}-->
												<li>
													<a href=<!--{$item.url}--> target='mainFrame' class="dropdown-item vinculos"><!--{$item.nombre}--></a>
												</li>
												<!--{/foreach}-->
											</ul>
										</li>
									<!--{/if}-->
								<!--{/foreach}-->
							</ul>
						</li>
					<!--{/if}-->
				</ul>

                <!-- ICONOS DERECHA -->
                <ul class="navbar-nav ms-auto align-items-center">

                    <!-- Versión -->
                    <li class="nav-item me-2">
                        <a class="nav-link small" id="copyversion" data-bs-toggle="tooltip"
                            data-version="<!--{$lastUpdate}-->-<!--{$lastCommit}-->" href="#">
                            <i class="fa fa-clipboard"></i>
                            <!--{$lastUpdate}-->-<!--{$lastCommit}-->
                        </a>
                    </li>

                    <!-- Buscador -->
                    <li class="nav-item"><a class="nav-link" href="busqueda/busquedaPiloto.php" target="mainFrame"><i class="fa fa-search fs-5"></i></a></li>

                    <!-- Mis Expedientes -->
                    <li class="nav-item"><a class="nav-link" href="expediente/MiExp.php" target="mainFrame"><i class="fa fa-folder-open-o fs-5"></i></a></li>

                    <!-- Estadísticas -->
                    <li class="nav-item"><a class="nav-link" href="estadisticas/vistaFormConsulta.php" target="mainFrame"><i class="fa fa-signal fs-5"></i></a></li>

                    <!-- OPCIONES -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <!--{$opciones.nombre}-->
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <!--{foreach from=$opciones.menu item=menu}-->
                            <li><a class="dropdown-item" href=<!--{$menu.url}--> target="mainFrame"><!--{$menu.nombre}--></a></li>
                            <!--{/foreach}-->
                        </ul>
                    </li>

                    <!-- USUARIO -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="fa fa-user"></i> <!--{$usuario.nombre}-->
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end" style="max-height: 300px; overflow-y: auto;">
                            <!--{foreach from=$usuario.menu item=menu key=val}-->
								<!-- Salir separador -->
								<!--{if $val == 'salir'}-->
									<li><hr class="dropdown-divider"></li>
								<!--{/if}-->

								<li>
									<a class="dropdown-item" href=<!--{$menu.url}--> >
										<!-- Íconos según acción -->
										<!--{if $val == 'salir'}--><i class="fa fa-power-off me-2"></i><!--{/if}-->
										<!--{if $val == 'cambioDeClave'}--><i class="fa fa-key me-2"></i><!--{/if}-->
										<!--{if $val == 'perfil'}--><i class="fa fa-user me-2"></i><!--{/if}-->
										<!--{$menu.nombre}-->
									</a>
								</li>
                            <!--{/foreach}-->
                        </ul>
                    </li>
                </ul>

            </div>
        </div>
    </nav>

    <div class="container-fluid mt-3">
        <div class="row">
			<div class="col-md-2 mb-2">
				<button class="btn btn-primary" id="toggleSidebarBtn">
					<i class="fa fa-bars"></i>
				</button>
			</div>
		</div>
        <div class="row">
            <!-- SIDEBAR -->
            <div class="col-md-2" id="sidebar">
                <div class="accordion accordion-flush" id="sidebarMenu">
                    <!-- CONSULTAS -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" data-bs-toggle="collapse"
                                data-bs-target="#menuConsultas">
                                <!--{$bandejas.menu.consultas.nombre}-->
                            </button>
                        </h2>
                        <div id="menuConsultas" class="accordion-collapse collapse">
                            <div class="accordion-body p-0">
                                <ul class="list-group list-group-flush">
                                    <!--{foreach from=$bandejas.menu.consultas.sub item=menu}-->
                                    <li class="list-group-item">
                                        <a href=<!--{$menu.url}--> target="mainFrame" class="text-decoration-none">
                                            <!--{$menu.nombre}-->
                                        </a>
                                    </li>
                                    <!--{/foreach}-->
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- BANDEJAS -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" data-bs-toggle="collapse"
                                data-bs-target="#menuBandejas">
                                <!--{$bandejas.nombre}-->
                            </button>
                        </h2>

                        <div id="menuBandejas" class="accordion-collapse collapse show">
                            <div class="accordion-body p-0" style="height: 50vh; overflow-y: auto;">
                                <ul class="list-group list-group-flush">
                                    <!--{foreach from=$bandejas.menu item=menu key=n}-->
                                    <!--{if $n neq 'personales' && $n neq 'consultas'}-->
                                    <li class="list-group-item">
                                        <a href=<!--{$menu.url}--> target="mainFrame" class="text-decoration-none">
                                            <!--{$menu.nombre}-->
                                        </a>
                                    </li>
                                    <!--{/if}-->
                                    <!--{/foreach}-->
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- PERSONALES -->
                    <!--{if $bandejas.menu.personales}-->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" data-bs-toggle="collapse"
                                data-bs-target="#menuPersonales">
                                <!--{$bandejas.menu.personales.nombre}-->
                            </button>
                        </h2>

                        <div id="menuPersonales" class="accordion-collapse collapse">
                            <div class="accordion-body p-0">
                                <ul class="list-group list-group-flush">
                                    <!--{foreach from=$bandejas.menu.personales.sub item=menu}-->
                                    <li class="list-group-item">
                                        <a href=<!--{$menu.url}--> target="mainFrame" class="text-decoration-none">
                                            <!--{$menu.nombre}-->
                                        </a>
                                    </li>
                                    <!--{/foreach}-->
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!--{/if}-->
                </div>
            </div>

            <!-- CONTENIDO -->
            <div class="col-md-10" id="mainContent">
                <iframe name="mainFrame" class="w-100"></iframe>
            </div>
        </div>
    </div>

	<!-- JS SCRIPTS -->
	<script type="text/javascript" src="js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
	<script>
        function cargarValoresCarpetas() {
			const url = <!--{$urlCargaValores}--> + "0";

			fetch(url)
				.then(response => response.text())
				.then(data => {
					const obj = JSON.parse(data);

					Object.entries(obj).forEach(([key, value]) => {
						const elemento = document.getElementById('carpetap_' + key);
						if (elemento) elemento.textContent = value;
					});
				})
				.catch(error => console.error("Error en cargarValoresCarpetas:", error));
		}

		function cargarValoresCarpetasPersonales() {
			const url = <!--{$urlCargaValores}--> + "1";

			fetch(url)
				.then(response => response.text())
				.then(data => {
					const obj = JSON.parse(data);

					Object.entries(obj).forEach(([key, value]) => {
						const elemento = document.getElementById('carpetaPersonal_' + key);
						if (elemento) elemento.textContent = value;
					});
				})
				.catch(error => console.error("Error en cargarValoresCarpetasPersonales:", error));
		}

        document.addEventListener('DOMContentLoaded', () => {
			const bandejas = document.getElementById('bandejas');
			const personales = document.getElementById('personales');
			const copyBtn = document.getElementById('copyversion');

			if (bandejas) bandejas.addEventListener('click', cargarValoresCarpetas);
			if (personales) personales.addEventListener('click', cargarValoresCarpetasPersonales);

			if (copyBtn) {
				new bootstrap.Tooltip(copyBtn, {
					placement: 'bottom',
					sanitize: true,
					boundary: 'window',
					title: "Click para copiar la versión al portapapeles"
				});

				copyBtn.addEventListener('click', () => {
					navigator.clipboard.writeText(copyBtn.dataset.version);
				});
			}
		});

        var activity = 0;
		var events = ['mousedown', 'mousemove', 'keydown', 'scroll', 'touchstart'];

		// Eventos en la ventana principal
		events.forEach(event => 
			window.addEventListener(event, () => { activity = 1; })
		);

		// Eventos dentro del iframe
		const iframe = document.querySelector('iframe');

		if (iframe) {
			iframe.addEventListener('load', () => {
				if (!iframe.contentDocument) return;

				events.forEach(event =>
					iframe.contentDocument.addEventListener(event, () => {
						activity = 1;
					})
				);
			});
		}

		// Llamada periódica cada 30 segundos
		setInterval(() => {
			fetch("./radicacion/ajax_buscarUsuario.php", {
				method: "POST",
				headers: {
					"Content-Type": "application/x-www-form-urlencoded"
				},
				body: new URLSearchParams({
					updateUserFolders: "true",
					activity: activity
				})
			})
			.then(response => response.json())
			.then(data => {
				activity = 0;

				// Si el servidor indica sesión expirada
				if (data.error === 'session') {
					window.location.href = 'cerrar_session.php';
					return;
				}

				// Actualizar carpetas
				Object.entries(data).forEach(([key, value]) => {
					const el = document.getElementById('carpetap_' + key);
					if (el) el.textContent = value;
				});
			})
			.catch(err => console.error("Error en actualización de carpetas:", err));
		}, 30000);

    </script>
	<script>
		const sidebar = document.getElementById('sidebar');
		const main = document.getElementById('mainContent');
		const btn = document.getElementById('toggleSidebarBtn');

		btn.addEventListener('click', () => {
			const isHidden = sidebar.classList.contains('d-none');

			if (isHidden) {
				// Mostrar sidebar
				sidebar.classList.remove('d-none');
				sidebar.classList.add('col-md-2');

				main.classList.remove('col-md-12');
				main.classList.add('col-md-10');

			} else {
				// Ocultar sidebar
				sidebar.classList.add('d-none');
				sidebar.classList.remove('col-md-2');

				main.classList.remove('col-md-10');
				main.classList.add('col-md-12');
			}
		});
	</script>
</body>
</html>
