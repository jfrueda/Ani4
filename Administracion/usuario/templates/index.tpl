<-{assign var="gruposHtml" value='
		<tr>
			<td class="toogletd">
				<a href="javascript:void(0);" class="button-icon" data-tipo="grupos" >
					<i class="fa fa-minus"></i>
				</a>
				<a href="javascript:void(0);" class="button-icon" data-tipo="grupos">
					<i class="fa fa-save"></i>
				</a>
			</td>

			<td class="hasinput">
				<label class="input">
					<input type="text" name="nombre" value="" required>
				</label>
			</td>

			<td class="hasinput">
				<label name="" class="input">
					<input type="text" name="descripcion" value="" required>
				</label>
			</td>
		</tr>
'}->

<!DOCTYPE>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link href="../../dist/css/select2.min.css" rel="stylesheet" />
	<script src="../../dist/js/select2.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.min.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
	<title>Administración de usuarios</title>
</head>

<body>
	<div class="container-fluid">
		<article class="col-12">
			<!-- widget content -->
			<div class="well">
				<button class="close" data-dismiss="alert">×</button>

				<h1 class="semi-bold"> Administración de usuarios y permisos </h1>

				<div class="well">
					<div class="widget-body no-padding smart-form">
						<FORM action="index.php" method="POST" >
							<label class="select">
								<select data-tipo="dependencias"  id="grupo_dependencias" name="grupo_adm" class="form-select" onchange="submit();">
									<option value="">-- Seleccione una Opción --</option>
									<-{foreach item=i from=$dependencias}->
										<option value="<-{$i.DEPE_CODI}->" <-{if $grupo_adm == $i.DEPE_CODI}->selected<-{/if}-> > <-{$i.DEPE_NOMB}-> </option>
									<-{/foreach}->
								</select>
							</label>
						</FORM>
						<div class="widget-body-toolbar"></div>
					</div>
					<p>ss
						- El siguiente panel nos muestra las distintas opciones para realizar las relaciones entre usuarios y
						permisos mediante la utilización de roles.
					</p>
					<p>
						Un rol de seguridad es una función del trabajo que identifica las tareas que puede realizar el
						usuario y los recursos a los que tiene acceso. Todas las autorizaciones en se realizan en estos roles.
						La navegación de la interfaz web se filtra en función del rol de seguridad asignado del usuario.
					</p>
					<p>
						Los roles de seguridad son una función del control de accesos que siempre está habilitada.
						Se debe asignar un usuario a un rol de seguridad con el permiso básico para iniciar la sesión.
					</p>
					<p>
						Los administradores del sistema tienen acceso a toda la interfaz web. Configuran las nuevas
						cuentas de usuario y pueden asignar controles de accesos en el perfil de cuenta de usuario.
						La información de cuenta de cada usuario debe incluir información de contacto, un nombre de
						usuario y contraseña, un grupo de acceso predeterminado y un rol de seguridad asignado.
					</p>
					<p>
						Además de asignar roles de seguridad a un usuario determinado, también se le puede establecer
						como superusuario. Un superusuario es alguien que no tiene ningún control de accesos y que
						puede ver todos los recursos y realizar acciones en cualquier elemento del modelo de datos.
					</p>
				</div>

				<div class="alert alert-success alertFixed" style="display: none;">
					<i class="fa-fw fa fa-check"></i>
					<strong id="alert-success" >Guardado</strong>
				</div>

				<div class="alert alert-danger alertFixed" style="display: none;">
					<i class="fa-fw fa fa-times"></i>
					<strong id="alert-danger" >Error! </strong>
				</div>

				<div id="accordion"> 
					<!-- ACORDION -->
					<-{if $Perm_solo_usuario eq 0 and $tiene_filtro eq 0}->
						<div>
							<!-- Roles -->
							<h4><strong>Roles</strong></h4>
							<div class="padding-10">
								<div class="row">
									<div class="col-sm-12">
										<div class="well">
											<button class="close" data-dismiss="alert">×</button>
											<p>
												Puede crear y asignar roles de seguridad en función del área de responsabilidad o la
												autoridad de aprobación de cambios  en el sistema. Tiene una  asignación de roles flexibles.
												Cada  rol de seguridad proporciona acceso a un conjunto de
												recursos específico. Puede asignar el rol de administrador del sistema a un usuario para
												proporcionar acceso a todos los recursos, o asignar uno o varios roles de seguridad en
												función de la responsabilidad del trabajo.  Estos roles de seguridad,
												que se pueden personalizar, representan un conjunto de permisos básicos.
											</p>
										</div>
									</div>
								</div>

								<!-- Widget ID (each widget will need unique ID)-->
								<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
									<header></header>
									<!-- widget div-->
									<div>
										<!-- widget edit box -->
										<div class="jarviswidget-editbox">
											<!-- This area used as dropdown edit box -->
										</div>
										<!-- end widget edit box -->

										<!-- widget content -->
										<div class="widget-body no-padding">
											<div class="widget-body-toolbar"></div>
											<table id="dt_basic" class="table table-striped table-bordered table-hover smart-form" width="100%">
												<thead>
													<tr>
														<th style="width: 35px;">
															<a href="javascript:void(0);" id="xdt_basic" class="btn btn-sm">
																<i class="fa fa-plus"></i>
															</a>
														</th>
														<th>Nombre</th>
														<th>Descripción</th>
													</tr>
												</thead>
												<tbody id="bdt_basic">
													<-{if count($grupos) eq 0}->
														<-{$gruposHtml}->
													<-{else}->
														<-{foreach item=grupo from=$grupos}->
															<tr>
																<td class="toogletd">
																	<a href="#" data-tipo="grupos" data-id="<-{$grupo.ID}->"
																	class="button-icon" >
																		<i class="fa fa-minus"></i>
																	</a>
																	<a href="javascript:void(0);" data-tipo="grupos" data-id="<-{$grupo.ID}->"
																	class="bu$resultado['estado']tton-icon">
																		<i class="fa fa-save "></i>
																	</a>
																</td>

																<td class="hasinput">
																	<label class="input">
																		<input type="text" name="nombre" value="<-{$grupo.NOMBRE}->">
																	</label>
																</td>

																<td class="hasinput">
																	<label name="" class="input">
																		<input type="text" name="descripcion" value="<-{$grupo.DESCRIPCION}->">
																	</label>
																</td>
															</tr>
														<-{/foreach}->
													<-{/if}->
												</tbody>
											</table>
										</div>
										<!-- end widget content -->
									</div>
									<!-- end widget div -->
								</div>
								<!-- WIDGET END -->
							</div>
						</div>
					<!-- Fin Roles -->
					<-{/if}->

					<-{if $Perm_solo_usuario eq 0 and $tiene_filtro eq 0}->
						<div> 
							<!-- Permisos -->
							<h4><strong>Permisos</strong></h4>
							<div class="padding-10">
								<!-- Widget ID (each widget will need unique ID)-->
								<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
									<header></header>
									<!-- widget div-->
									<div>
										<!-- widget edit box -->
										<div class="jarviswidget-editbox">
											<!-- This area used as dropdown edit box -->
										</div>
										<!-- end widget edit box -->

										<!-- widget content -->
										<div class="widget-body no-padding smart-form">
											<table id="dt_basic2">
												<thead>
													<tr>
														<th style="width: 35px;">
															<a href="javascript:void(0);" id="xdt_basic2" class="btn btn-sm"><i class="fa
															fa-plus"></i></a>
														</th>
														<th>Nombre</th>
														<th>Descripci&oacute;n</th>
														<th>Crud</th>
														<th>Grupo</th>
													</tr>
												</thead>
												<tbody id="bdt_basic2">
													<-{if count($permisos) eq 0}->
														<tr>
															<td class="toogletd">
																<a href="javascript:void(0);" data-tipo="permisos" data-id="<-{$item.ID}->"class="button-icon">
																	<i class="fa fa-save "></i>
																</a>
															</td>
															<td class="hasinput">
																<label class="input">
																	<input type="text" name="nombre" value="<-{$item.NOMBRE}->">
																</label>
															</td>
															<td class="hasinput">
																<label name="" class="input">
																	<input type="text" name="descripcion" value="<-{$item.DESCRIPCION}->">
																</label>
															</td>
															<td class="hasinput">
																<label class="select">
																	<select class="input-sm" name="crud">
																		<option value="">-- Seleccione una Opción --</option>
																		<-{foreach item=i from=$crud}->
																			<option value="<-{$i.ID}->">
																				<-{$i.NOMBRE}->
																			</option>
																		<-{/foreach}->
																	</select> <i></i>
																</label>
															</td>
															<td class="hasinput">
																<label class="select select-multiple">
																	<select class="custom-scroll" multiple name="grupo">
																		<option value="">-- Seleccione una Opción --</option>
																		<-{foreach item=i from=$grupos}->
																			<-{if $item.AUTG_ID eq $i.ID}->
																				<option value="<-{$i.ID}->" selected>
																					<-{$i.NOMBRE}->
																				</option>
																			<-{else}->
																				<option value="<-{$i.ID}->">
																					<-{$i.NOMBRE}->
																				</option>
																			<-{/if}->
																		<-{/foreach}->
																	</select> <i></i>
																</label>
															</td>
														</tr>
													<-{else}->
														<-{foreach item=item from=$permisos}->
															<tr>
																<td class="toogletd">
																	<a href="javascript:void(0);" data-tipo="permisos" data-id="<-{$item.ID}->" class="button-icon">
																		<i class="fa fa-save "></i>
																	</a>
																</td>
																<td class="hasinput">
																	<div class="hide"><-{$item.NOMBRE}-></div>
																	<label class="input">
																		<input type="text" name="nombre" value="<-{$item.NOMBRE}->">
																	</label>
																</td>
																<td class="hasinput">
																	<div class="hide"><-{$item.DESCRIPCION}-></div>
																	<label name="" class="input">
																		<input type="text" name="descripcion" value="<-{$item.DESCRIPCION}->">
																	</label>
																</td>
																<td class="hasinput">
																	<label class="select">
																		<select class="input-sm" name="crud">
																			<option value="">-- Seleccione una Opción --</option>
																			<-{foreach item=i from=$crud}->
																				<-{if $item.CRUD eq $i.ID}->
																					<option value="<-{$i.ID}->" selected>
																						<-{$i.NOMBRE}->
																					</option>
																				<-{else}->
																					<option value="<-{$i.ID}->">
																						<-{$i.NOMBRE}->
																					</option>
																				<-{/if}->
																			<-{/foreach}->
																		</select> <i></i>
																	</label>
																</td>
																<td class="hasinput">
																	<label class="select select-multiple">
																		<select class="custom-scroll select2" multiple name="grupo">
																			<option value="">-- Seleccione una Opción --</option>
																			<-{foreach item=i from=$grupos}->
																				<option value="<-{$i.ID}->"
																					<-{foreach item=j from=$item.AUTG_ID}->
																						<-{if $j eq $i.ID}->
																							selected
																							{break}
																						<-{/if}->
																					<-{/foreach}->>
																					<-{$i.NOMBRE}->
																				</option>
																			<-{/foreach}->
																		</select> <i></i>
																	</label>
																</td>
															</tr>
														<-{/foreach}->
													<-{/if}->
												</tbody>
											</table>

											<!-- pager -->
											<div class="pager2">
												<div class="btn btn-sm">
													<span class="fa fa-fast-backward txt-color-blueLight first"></span>
												</div>
												<div class="btn btn-sm">
													<span class="fa fa-backward prev txt-color-blueLight"></span>
												</div>
												<span class="pagedisplay"></span> 
												<!-- this can be any element, including an input -->
												<div class="btn btn-sm">
													<span class="fa fa-forward next txt-color-blueLight"></span>
												</div>
												<div class="btn btn-sm">
													<span class="fa fa-fast-forward txt-color-blueLight last"></span>
												</div>
												<select class="pagesize" title="Select page size">
													<option selected="selected" value="10">10</option>
													<option value="20">20</option>
													<option value="30">30</option>
													<option value="40">40</option>
												</select>
												<select class="gotoPage" title="Select page number"></select>
											</div>
										</div>
										<!-- end widget content -->
									</div>
									<!-- end widget div -->
								</div>
								<!-- WIDGET END -->
							</div>
						</div> 
						<!-- Fin Permisos -->
					<-{/if}->

						<div> 
							<!-- Usuarios -->
							<h4><strong>Usuarios</strong></h4>
							<div class="padding-10">
								<!-- Widget ID (each widget will need unique ID)-->
								<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
									<header></header>
									<!-- widget div-->
									<div>
										<!-- widget edit box -->
										<div class="jarviswidget-editbox">
											<!-- This area used as dropdown edit box -->
										</div>
										<!-- end widget edit box -->
										<!-- widget content -->
										<div class="widget-body no-padding smart-form">
											<div class="widget-body-toolbar"></div>
											<table id="dt_basic3" class="table table-striped table-bordered table-hover smart-form" width="100%">
												<thead>
													<tr>
														<th style="width: 35px;">
															<-{if $Less_edita_usuario eq 0}->
																<a href="javascript:void(0);" id="xdt_basic3" class="btn btn-sm">
																	<i class="fa fa-plus"></i>
																</a>
															<-{/if}->
														</th>
														<th>Usuario</th>
														<th>Nuevo</th>
														<th>Nombres</th>
														<th>Documento</th>
														<th>Dependencias</th>
														<th>Correo</th>
														<th>Estado</th>
														<th>Nivel de seguridad</th>
														<th>LDAP user</th>
														<th>Perfil</th>
														<th>Ultima conexion</th>
													</tr>
												</thead>
												<tbody id="bdt_basic3">
												<-{if count($usuarios) eq 0}->
													<tr>
														<td class="toogletd">
															<a href="javascript:void(0);" class="button-icon" data-tipo="usuarios">
																<i class="fa fa-save"></i>
															</a>
														</td>
														<td class="hasinput">
															<label class="input">
																<input type="text" name="usuarios" value="">
															</label>
														</td>
														<td class="hasinput">
															<label class="select">
																<select class="custom-scrollselectpicker" name="nuevo">
																	<option value=""> Seleccione una opción </option>
																	<option value="0"> Actual </option>
																	<option value="1"> Nuevo </option>
																</select> <i></i>
															</label>
														</td>
														<td class="hasinput">
															<label class="input">
																<input type="text" name="nombres" value="">
															</label>
														</td>
														<td class="hasinput">
															<label class="input">
																<input type="text" name="documento" value="">
															</label>
														</td>
														<td class="hasinput">
															<label class="select">
																<select class="custom-scrollselectpicker" name="dependencia" readonly/>
																	<option value="">-- Seleccione una Opción --</option>
																	<-{foreach item=i from=$dependencias}->
																		<option value="<-{$i.DEPE_CODI}->">
																			<-{$i.DEPE_NOMB}->
																		</option>
																	<-{/foreach}->
																</select> <i></i>
															</label>
														</td>
														<td class="hasinput">
															<label class="input">
																<input type="text" name="correo" value="">
															</label>
														</td>
														<td class="hasinput">
															<label class="select">
																<select class="custom-scrollselectpicker" name="estado">
																	<option value="">-- Seleccione una Opción --</option>
																	<option value="0"> Inactivo </option>
																	<option value="1"> Activo </option>
																</select> <i></i>
															</label>
														</td>
														<td class="hasinput">
															<label class="select">
																<select class="custom-scrollselectpicker" name="nivel">
																	<-{for $i=1 to 5}->
																		<option value="<-{$i}->"
																		<-{if $item.CODIGO_NIVEL eq $i}->
																			selected
																		<-{/if}->><-{$i}->
																	<-{/for}->
																</select> <i></i>
															</label>
														</td>
														<td class="hasinput">
															<label class="input">
																<select name="ldap_login">
																	<option value="null"></option>
																	<option value="0"
																		<-{if $item.AUTH_LDAP eq '0'}->
																			selected
																		<-{/if}->> Inactivo
																	</option>
																	<option value="1"
																		<-{if $item.AUTH_LDAP eq '1'}->
																			selected
																		<-{/if}->> Activo
																	</option>
																</select>
																<div class="hide"><-{$item.AUTH_LDAP}-></div>
															</label>
														</td>
														<td>
															<label class="select">
																<select class="custom-scrollselectpicker" name="grupo" data-roles_id="<-{$item['ROLES_ID']}->" data-roles="<-{$item['ROLES']}->">
																	<option value="">Seleccionar</option>
																	<-{foreach item=grupo from=$grupos_requeridos}->
																		<option value="<-{$grupo['ID']}->"><-{$grupo['NOMBRE']}-></option>
																	<-{/foreach}->
																</select> <i></i>
															</label>
														</td>
														<td class="hasinput">
															<input type="text" name="conexion" value="">
														</td>
													</tr>
												<-{else}->
													<-{foreach item=item from=$usuarios}->
														<tr>
															<td class="toogletd">
																<-{if $Less_edita_usuario eq 0}->
																	<a href="javascript:void(0);" data-tipo="usuarios" data-id="<-{$item.ID}->" class="button-icon">
																		<i class="fa fa-save "></i>
																	</a>
																<-{/if}->
																<a href="javascript:void(0);" data-id="<-{$item.ID}->" class="button-icon listusua"> 
																	<i class="fa fa-list"></i>
																</a>
															</td>
															<td class="hasinput">
																<label class="input">
																	<input type="text" name="usuarios" value="<-{$item.USUARIO}->" readonly>
																	<div class="hide"><-{$item.USUARIO}-></div>
																</label>
															</td>
															<td class="hasinput">
																<label class="select">
																	<select class="custom-scrollselectpicker" name="nuevo">
																		<option value="">-- Seleccione una Opción --</option>
																		<option value="1"
																			<-{if $item.NUEVO eq 1}->
																				selected
																			<-{/if}-> > actual
																		</option>
																		<option value="0"
																			<-{if $item.NUEVO eq 0}->
																				selected
																			<-{/if}->> Nuevo
																	</option>
																	</select> <i></i>
																</label>
															</td>
															<td class="hasinput">
																<label class="input">
																	<input type="text" name="nombres" value="<-{$item.NOMBRES}->">
																	<div class="hide"><-{$item.NOMBRES}-></div>
																</label>
															</td>
															<td class="hasinput">
																<label class="input">
																	<input type="text" name="documento" value="<-{$item.DOCUMENTO}->" disabled>
																	<div class="hide"><-{$item.DOCUMENTO}-></div>
																</label>
															</td>
															<td class="hasinput">
																<label class="select">
																	<select class="custom-scroll" name="dependencia" readonly/>
																		<option value="">-- Seleccione una Opción --</option>
																		<-{foreach item=i from=$dependencias}->
																			<-{if $item.DEPECODI eq $i.DEPE_CODI}->
																				<option value="<-{$i.DEPE_CODI}->" selected>
																					<-{$i.DEPE_NOMB}->(Actual)
																				</option>
																				{break}
																			<-{else}->
																				<option value="<-{$i.DEPE_CODI}->">
																					<-{$i.DEPE_NOMB}->
																				</option>
																			<-{/if}->
																		<-{/foreach}->
																	</select> <i></i>
																</label>
															</td>
															<td class="hasinput">
																<label class="input">
																	<input type="text" name="correo" value="<-{$item.CORREO}->">
																	<div class="hide"><-{$item.CORREO}-></div>
																</label>
															</td>
															<td class="hasinput">
																<label class="select">
																	<select class="custom-scrollselectpicker" name="estado" id="est" onchange="handleChangeEstado(this,'<-{$item.USUARIO}->', <-{$item.DOCUMENTO}->, <-{$item.DEPECODI}->)">
																		<option value="">-- Seleccione una Opción --</option>
																		<option value="0"
																			<-{if $item.ESTADO eq 0}->
																				selected
																			<-{/if}->> Inactivo
																		</option>
																		<option value="1"
																			<-{if $item.ESTADO eq 1}->
																				selected
																			<-{/if}->> Activo
																		</option>
																	</select> <i></i>
																</label>
															</td>
															<td class="hasinput">
																<label class="select">
																	<select class="custom-scrollselectpicker" name="nivel">
																		<-{for $i=1 to 5}->
																			<option value="<-{$i}->"
																			<-{if $item.CODIGO_NIVEL eq $i}->
																				selected
																			<-{/if}->><-{$i}->
																		<-{/for}->
																	</select> <i></i>
																</label>
															</td>
															<td class="hasinput">
																<label class="input">
																	<select name="ldap_login">
																		<option value="null"></option>
																		<option value="0"
																			<-{if $item.AUTH_LDAP eq '0'}->
																				selected
																			<-{/if}->> Inactivo
																		</option>

																		<option value="1"
																			<-{if $item.AUTH_LDAP eq '1'}->
																				selected
																			<-{/if}->> Activo
																		</option>
																	</select>
																	<div class="hide"><-{$item.AUTH_LDAP}-></div>
																</label>
															</td>
															<td>
																<label class="select">
																	<select class="custom-scrollselectpicker" name="grupo" data-roles_id="<-{$item['ROLES_ID']}->" data-roles="<-{$item['ROLES']}->">
																		<option value="">Seleccionar</option>
																		<-{foreach item=grupo from=$grupos_requeridos}->
																			<option value="<-{$grupo['ID']}->"
																				<-{if in_array($grupo['ID'], explode(',', $item['ROLES_ID'])) }->
																					selected
																				<-{/if}->>
																				<-{$grupo['NOMBRE']}->
																			</option>
																		<-{/foreach}->
																	</select> <i></i>
																</label>
															</td>
															<td class="hasinput">
																<label class="input">
																	<-{$item.CONEXION}->
																</label>
															</td>
														</tr>
													<-{/foreach}->
												<-{/if}->
												</tbody>
											</table>
										</div>
										<!-- end widget content -->
									</div>
									<!-- end widget div -->
								</div>
								<!-- WIDGET END -->
							</div>
						</div>
						<!-- Fin Usuarios -->

						<div> 
							<!-- Perfiles -->
							<h4><strong>Perfiles</strong></h4>
							<div class="padding-10">
								<!-- Widget ID (each widget will need unique ID)-->
								<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
									<header></header>
									<!-- widget div-->
									<div>
										<!-- widget edit box -->
										<div class="jarviswidget-editbox">
											<!-- This area used as dropdown edit box -->
										</div>
										<!-- end widget edit box -->

										<!-- widget content -->
										<div class="widget-body no-padding smart-form">
											<-{if $Less_edita_profile eq 0}->
												<label class="select">
													<select data-tipo="membresias"  id="grupo_membresias" name="grupo"
															class="custom-scroll select2">
														<option value="">-- Seleccione una Opción --</option>
														<-{foreach item=i from=$grupos}->
															<option value="<-{$i.ID}->"> <-{$i.NOMBRE}-> </option>
														<-{/foreach}->
													</select>
												</label>
											<-{/if}->

											<table id="datatable"  width="100%">
												<colgroup>
													<col/>
													<col/>
													<col/>
													<col/>
												</colgroup>
												<thead>
													<tr>
														<th>ID</th>
														<th>Roles</th>
														<th>Usuario</th>
														<th>Dependencia</th>
														<th>Nombre</th>
													</tr>
												</thead>
												<tbody id="contentable">
													<-{foreach item=i from=$usuarios}->
														<-{if $i.ESTADO eq "1"}->
															<tr>
																<td> 
																	<-{if $Less_edita_profile eq 0  and $tiene_filtro eq 0 }->
																	<label class="checkbox">
																		<input data-tipo="membresias"  type="checkbox"
																			name="<-{$i.ID}->" value="<-{$i.ID}->">
																		<i></i>
																	</label>
																</td> <-{/if}->
																<td><-{$i.ROLES}-></td>
																<td><-{$i.USUARIO}-></td>
																<td><-{$i.DEPENDENCIA}-></td>
																<td><-{$i.NOMBRES}-> <-{if $i.CODIGO_USUARIO eq "1"}-> ( Jefe ) <-{/if}-></td>
															</tr>
														<-{/if}->
													<-{/foreach}->
												</tbody>
											</table>

											<!-- pager -->
											<div class="pager">
												<div class="btn btn-sm"><span class="fa fa-fast-backward txt-color-blueLight first"></span></div>
												<div class="btn btn-sm"><span class="fa fa-backward prev txt-color-blueLight"></span></div>
												<span class="pagedisplay"></span> <!-- this can be any element, including an input -->
												<div class="btn btn-sm"><span class="fa fa-forward next txt-color-blueLight"></span></div>
												<div class="btn btn-sm"><span class="fa fa-fast-forward txt-color-blueLight last"></span></div>
												<select class="pagesize" title="Select page size">
													<option selected="selected" value="10">10</option>
													<option value="20">20</option>
													<option value="30">30</option>
													<option value="40">40</option>
												</select>

												<select class="gotoPage" title="Select page number"></select>
											</div>
										</div>
										<!-- end widget content -->
									</div>
									<!-- end widget div -->
								</div>
								<!-- WIDGET END -->
							</div>
						</div><!-- Fin Perfiles -->
				</div> <!-- FIN ACORDION -->
			</div>
		</article>
	</div>

	<a href="#" data-toggle="modal" data-bs-target="#confirm-delete" id="show_toggle"></a>

	<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header bg-primary text-white d-flex justify-content-between align-items-center">
					Borrar el Registro
				</div>
				<div class="modal-body">
					Seguro que desea borrar este registro ?
					Esto puede afectar a la funcionalidad de los roles en todo el sistema
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" id="ocultar_modal" data-dismiss="modal">Cancelar</button>
					<a href="javascript:void(0);" id="atribute_confirm_delete" class="btn btn-danger danger">Borrar</a>
				</div>
			</div>
		</div>
	</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

	<style>
		.swal2-confirm {
			border: 2px solid blue;
			color: blue;
			background-color: transparent;
		}
	</style>

	<script>
		async function handleChangeEstado(selectElement, usuario, documento, dependencia) {
			const selectedValue = selectElement.value;
			//console.log(window.location.pathname.split('/')[0]);
			let subDom = (window.location.pathname.split('/')[0] === '') ? window.location.pathname.split('/')[1] : window.location.pathname.split('/')[0]
			// Aquí puedes agregar la lógica para manejar el cambio
			console.log("Nuevo estado seleccionado:", selectedValue);
			console.log(usuario);
			console.log(documento);
			console.log(dependencia);

			try {
				let res = await fetch('./ajaxValExp.php', {
						method: 'POST',
						mode: 'cors',
						body: JSON.stringify({
							condi: 'valExp',
							userLogi: usuario,
							userDoc: documento,
							dependencia: dependencia,
							path: subDom,
							select: selectedValue
						}),
						headers: {
							'Content-type': 'application/json'
						}
					}),
					json = await res.json();
				//console.log(json);

				if (json.resp == 'failed') {
					Swal.fire({
						title: 'Advertencia!',
						html: `<hr> El usuario ${usuario} tiene: ${json.expedientes} expedientes asignados como responsable, por lo que no es posible inactivarlo <hr>`,
						icon: 'warning',
						confirmButtonText: 'CERRAR',
						customClass: {
							confirmButton: 'swal2-confirm'
						}
					});
					document.getElementById('est').value = (json.seleccionado == 1) ? 0 : 1;

				}
			} catch (error) {
				alert(`Error de conexión ${error}`);
			}
		}
	</script>

	<!-- 
		Plantilla para la creación de los grupos.
		Elemento necesario para duplicar los campos
		de inserción para los nuevos registros. 
	-->
	<script id="plantillaGrupos" type="text/html">
		<-{$gruposHtml}->
	</script>

	<!-- 
		Plantilla para la creación de los permisos.
		Elemento necesario para duplicar los campos
		de inserción para los nuevos registros. 
	-->
	<script id="plantillaPermisos" type="text/html">
		<tr>
			<td class="toogletd">
				<a href="javascript:void(0);" data-tipo="permisos" data-id="" class="button-icon">
					<i class="fa fa-save "></i>
				</a>
			</td>

			<td class="hasinput">
				<label class="input">
					<input type="text" name="nombre" value="">
				</label>
			</td>

			<td class="hasinput">
				<label name="" class="input">
					<input type="text" name="descripcion" value="">
				</label>
			</td>

			<td class="hasinput">
				<label class="select">
					<select class="input-sm" name="crud">
						<option value="">-- Seleccione una Opción --</option>
						<-{foreach item=i from=$crud}->
							<option value="<-{$i.ID}->">
								<-{$i.NOMBRE}->
							</option>
							<-{ /foreach}->
					</select>
					<i></i>
				</label>
			</td>

			<td class="hasinput">
				<div>
					<label class="select select-multiple">
						<select class="custom-scroll select2" multiple name="grupo">
							<option value="">-- Seleccione una Opción --</option>
							<-{foreach item=i from=$grupos}->
								<option value="<-{$i.ID}->">
									<-{$i.NOMBRE}->
								</option>
								<-{ /foreach}->
						</select>
						<i></i>
					</label>
				</div>
			</td>
		</tr>
	</script>

	<!-- 
		Plantilla para la creación de los Usuarios.
		Elemento necesario para duplicar los campos
		de inserción para los nuevos registros. 
	-->
	<script id="plantillaUsuarios" type="text/html">
		<tr>
			<td class="toogletd">
				<a href="javascript:void(0);" class="button-icon" data-tipo="usuarios">
					<i class="fa fa-save"></i>
				</a>
			</td>

			<td class="hasinput">
				<label class="input">
					<input type="text" name="usuarios" value="">
				</label>
			</td>

			<td class="hasinput">
				<label class="select">
					<select class="custom-scrollselectpicker" name="nuevo">
						<option value=""> Seleccione una opción </option>
						<option value="0"> Actual </option>
						<option value="1"> Nuevo </option>
					</select> <i></i>
				</label>
			</td>

			<td class="hasinput">
				<label class="input">
					<input type="text" name="nombres" value="">
				</label>
			</td>

			<td class="hasinput">
				<label class="input">
					<input type="text" name="documento" value="">
				</label>
			</td>

			<td class="hasinput">
				<label class="select">
					<select class="custom-scrollselectpicker" name="dependencia">
						<option value="">-- Seleccione una Opción --</option>
						<-{foreach item=i from=$dependencias}->
							<option value="<-{$i.DEPE_CODI}->">
								<-{$i.DEPE_NOMB}->
							</option>
							<-{ /foreach}->
					</select> <i></i>
				</label>
			</td>

			<td class="hasinput">
				<label class="input">
					<input type="text" name="correo" value="">
				</label>
			</td>

			<td class="hasinput">
				<label class="select">
					<select class="custom-scrollselectpicker" name="estado">
						<option value="">-- Seleccione una Opción --</option>
						<option value="0"> Inactivo </option>
						<option value="1"> Activo </option>
					</select> <i></i>
				</label>
			</td>

			<td class="hasinput">
				<label class="select">
					<select class="custom-scrollselectpicker" name="nivel">
						<option value="">-- Seleccione una Opción --</option>
						<option value="1"> 1 </option>
						<option value="2"> 2 </option>
						<option value="3"> 3 </option>
						<option value="4"> 4 </option>
						<option value="5"> 5 </option>
					</select> <i></i>
				</label>
			</td>

			<td class="hasinput">
				<label class="input">
					<select name="ldap_login">
						<option value="null">-- Seleccione una Opción --</option>
						<option value="0">Inactivo</option>
						<option value="1">Activo</option>
					</select>
				</label>
			</td>

			<td>
				<label class="select">
					<select class="custom-scrollselectpicker" name="grupo" data-roles_id="<-{$item['ROLES_ID']}->" data-roles="<-{$item['ROLES']}->">
						<option value="">Seleccionar</option>
						<-{foreach item=grupo from=$grupos_requeridos}->
							<option value="<-{$grupo['ID']}->">
								<-{$grupo['NOMBRE']}->
							</option>
							<-{ /foreach}->
					</select> <i></i>
				</label>
			</td>

			<td></td>
		</tr>
	</script>

	<script type="text/javascript">
		document.addEventListener('DOMContentLoaded', function() {
			// DO NOT REMOVE : GLOBAL FUNCTIONS!
			pageSetUp();

			$('.listusua').on('click', function(event) {
				var iduser = $(this).data('id');

				$('<div>').dialog({
					modal: true,
					open: function() {
						$(this).load('dialogpermisos.php?id=' + iduser);
					},
					title: 'Permisos del usuario',
					width: 600,
					position: {
						my: "top",
						at: "top",
						of: window
					}
				})
			});

			// PAGE RELATED SCRIPTS
			loadDataTableScripts();

			function loadDataTableScripts() {
				loadScript("../../js/plugin/datatables/jquery.dataTables-cust.min.js", dt_2);

				function dt_2() {
					loadScript("../../js/plugin/datatables/DT_bootstrap.js", runDataTables);
				}
			}

			function runDataTables() {
				$('#dt_basic, #dt_basic3').dataTable();
			}

			$("#dt_basic2").tablesorter({
				widgets: ["filter"],
				widgetOptions: {
					filter_reset: '.reset'
				}
			}).tablesorterPager({
				// target the pager markup - see the HTML block below
				container: $(".pager2"),
				// use this url format "http:/mydatabase.com?page={page}&size={size}"
				ajaxUrl: null
			});

			$("#datatable").tablesorter({
				widgets: ["filter"],
				widgetOptions: {
					filter_reset: '.reset'
				}
			}).tablesorterPager({
				// target the pager markup - see the HTML block below
				container: $(".pager"),
				// use this url format "http:/mydatabase.com?page={page}&size={size}"
				ajaxUrl: null
			});

			//Validacion de los campos del formulario
			function validarCampos(datos) {
				const alertDanger = document.querySelector('.alert-danger');

				if (alertDanger) {
					alertDanger.innerHTML = 'Error!';
				}

				const res = datos.split('&');
				let test = true;

				res.forEach(function(value) {
					const partes = value.split('=');
					const key = partes[0];
					const dato = partes[1];

					if (
						(dato === "" || dato === undefined || dato.length === 0) &&
						key !== 'id' &&
						key !== 'ldap_login'
					) {
						if (alertDanger) {
							alertDanger.style.display = 'block';

							setTimeout(() => {
								alertDanger.style.display = 'none';
							}, 3000);
						}
						test = false;
					}
				});

				return test;
			}

			/**
			* ACCORDION
			* jquery accordion
			*/
			var accordionIcons = {
				header: "fa fa-plus", // custom icon class
				activeHeader: "fa fa-arrow-up" // custom icon class
			};

			$("#accordion").accordion({
				autoHeight: false,
				heightStyle: "content",
				collapsible: true,
				animate: 300,
				header: "h4",
				icons: {
					"header": "fa fa-plus",
					"activeHeader": "fa fa-arrow-up"
				},
				active: 3
			});

			//agregar elementos
			const ids = ['xdt_basic2', 'xdt_basic', 'xdt_basic3', 'xdt_basic4'];

			ids.forEach(function(id) {
				const el = document.getElementById(id);
				if (!el) return;

				el.addEventListener('click', function(event) {

					// equivale a: 'b' + $(this).attr('id').substring(1)
					const nomPlus = 'b' + this.id.substring(1);

					switch (nomPlus) {
						case 'bdt_basic':
							// Plantillas y clonar elementos Grupos
							const plaGrup = document.getElementById('plantillaGrupos');
							if (plaGrup) {
								document.getElementById('bdt_basic')
									.insertAdjacentHTML('afterbegin', plaGrup.innerHTML);
							}
							break;

						case 'bdt_basic2':
							// Plantillas y clonar elementos Permisos
							const plaPerm = document.getElementById('plantillaPermisos');
							if (plaPerm) {
								document.getElementById('bdt_basic2')
									.insertAdjacentHTML('afterbegin', plaPerm.innerHTML);
							}
							break;

						case 'bdt_basic3':
							// Plantillas y clonar elementos Usuarios
							const plaUsua = document.getElementById('plantillaUsuarios');
							if (plaUsua) {
								document.getElementById('bdt_basic3')
									.insertAdjacentHTML('afterbegin', plaUsua.innerHTML);
							}
							break;
					}

					event.stopPropagation();
				});
			});

			//Eliminar un campo de la selección
			document.body.addEventListener('click', function(event) {
				const target = event.target.closest('.fa-minus');
				if (!target) return;

				const parent = target.parentElement;

				const tipo = parent.dataset.tipo;
				const id = parent.dataset.id;
				const datos = 'accion=borrar&tipo=' + tipo + '&id=' + id;

				const confirmDelete = document.getElementById('atribute_confirm_delete');
				if (confirmDelete) {
					confirmDelete.setAttribute('data-id', id);
					confirmDelete.setAttribute('data-tipo', tipo);
					confirmDelete.setAttribute('data-datos', datos);
				}

				const tr = target.closest('tr');
				if (tr) {
					tr.classList.add('deleteme');
				}

				const showToggle = document.getElementById('show_toggle');
				if (showToggle) {
					showToggle.click();
				}
			});

			//Confirmar eliminación
			const confirmBtn = document.getElementById('atribute_confirm_delete');
			if (!confirmBtn) return;

			confirmBtn.addEventListener('click', function(event) {
				const id = this.getAttribute('data-id');
				const tipo = this.getAttribute('data-tipo');
				const datos = this.getAttribute('data-datos');

				// Valida si existe algun campo en blanco
				if (!validarCampos(datos)) {
					return false;
				}

				if (id === null || id === undefined) {
					const tr = this.closest('tr');
					if (tr) tr.remove();
					return;
				}

				const alertDanger = document.getElementById('alert-danger');
				if (alertDanger) {
					alertDanger.innerHTML = 'Error!';
				}

				fetch('ajaxPermisos.php', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
						},
						body: datos
					})
					.then(response => response.json())
					.then(data => {
						if (data.estado == 1) {
							const row = document.querySelector('.deleteme');
							if (row) {
								const tr = row.closest('tr');
								if (tr) tr.remove();
							}

							const alertSuccess = document.querySelector('.alert-success');
							if (alertSuccess) {
								alertSuccess.style.display = 'block';
								setTimeout(() => {
									alertSuccess.style.display = 'none';
								}, 3000);
							}

						} else {
							const alertDanger = document.querySelector('.alert-danger');
							if (alertDanger) {
								alertDanger.style.display = 'block';
								setTimeout(() => {
									alertDanger.style.display = 'none';
								}, 3000);
							}
						}
					})
					.catch(() => {
						const alertDanger = document.querySelector('.alert-danger');
						if (alertDanger) {
							alertDanger.style.display = 'block';
						}
					});

				const ocultarModal = document.getElementById('ocultar_modal');
				if (ocultarModal) {
					ocultarModal.click();
				}
			});

			//Agregar o editar
			document.body.addEventListener('click', function(event) {
				const target = event.target.closest('.fa-save');
				if (!target) return;

				const parent = target.parentElement;
				const tipo = parent.dataset.tipo;
				const id = parent.dataset.id;

				let datos = 'accion=guardar&tipo=' + tipo;
				const boton = target;
				const elemnttr = target.closest('tr');

				if (id !== undefined) {
					datos += '&id=' + id;
				} else {
					datos += '&id=';
				}

				// inputs
				elemnttr.querySelectorAll('input').forEach(function(inpe) {
					const name = inpe.getAttribute('name');
					const valu = inpe.value;

					if (name !== undefined && inpe !== undefined && valu !== undefined) {
						datos += '&' + name + '=' + valu;
					}
				});

				let auxEstado;

				// selects en for
				elemnttr.querySelectorAll('select').forEach(function(inpe) {
					const name = inpe.getAttribute('name');
					const valu = inpe.value;

					datos += '&' + name + '=' + valu;

					if (name === 'estado') {
						auxEstado = inpe;
					}
				});

				if (!validarCampos(datos)) {
					return false;
				}

				fetch('ajaxPermisos.php', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded'
						},
						body: datos
					})
					.then(response => response.json())
					.then(data => {

						if (data['estado'] == 1) {

							boton.closest('td').querySelectorAll('a').forEach(function(a) {
								a.setAttribute('data-id', data['valor']);
							});

							if (id === undefined) {

								switch (tipo) {

									case 'grupos':
										const newdato = new Option(data['nombre'], data['valor']);

										document
											.querySelectorAll('#bdt_basic2 [name="grupo"]')
											.forEach(function(el) {
												el.append(newdato.cloneNode(true));
											});

										document
											.getElementById('grupo_membresias')
											.append(newdato.cloneNode(true));
										break;

									case 'usuarios':
										window.location.reload();
										break;
								}
							}

							const alertSuccess = document.querySelector('.alert-success');
							if (alertSuccess) {
								alertSuccess.style.display = 'block';
								setTimeout(() => {
									alertSuccess.style.display = 'none';
								}, 3000);
							}

						} else {

							if (data['valor'] === 'El Usuario tiene el perfil de jefé de área y no se puede inactivar.') {
								if (auxEstado) auxEstado.value = 1;
							}

							const alertDanger = document.querySelector('.alert-danger');
							if (alertDanger) {
								alertDanger.innerHTML = data['valor'];
								alertDanger.style.display = 'block';
								setTimeout(() => {
									alertDanger.style.display = 'none';
								}, 3000);
							}
						}
					});
			});

			//Cuando se cambia el grupo de dependencias
			const grupoDependencias = document.getElementById('grupo_dependencias');
			if (!grupoDependencias) return;

			grupoDependencias.addEventListener('change', function() {
				const selectedText = grupoDependencias.options[grupoDependencias.selectedIndex].text.slice(0, -1) + '(Actual)';

				const inputs = document.querySelectorAll('#dt_basic3_wrapper .form-control');

				inputs.forEach(function(input) {
					input.value = selectedText;
					input.focus();
				});
			});

			//Selector multiple para las membresias Grupos
			//Cuando se cambia un grupo debe buscar los usuarios existente
			document.body.addEventListener('change', function(event) {
				const target = event.target;
				if (!target || target.id !== 'grupo_membresias') return;

				const tipo = target.dataset.tipo;
				const datos = 'accion=buscarUsuariosDelGrupo&' + target.getAttribute('name') + '=' + target.value;

				// Limpia inputs
				document.querySelectorAll('.form-control').forEach(function(el) {
					el.value = '';
				});

				// Desmarca checkboxes
				document.querySelectorAll('input[type="checkbox"]').forEach(function(el) {
					el.checked = false;
				});

				if (!validarCampos(datos)) {
					return false;
				}

				// POST
				fetch('ajaxPermisos.php', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded'
						},
						body: datos
					})
					.then(response => response.json())
					.then(data => {
						if (data['estado'] == 1 && Array.isArray(data['valor'])) {
							data['valor'].forEach(function(value) {
								const checkbox = document.querySelector(
									`input[name="${value}"]`
								);
								if (checkbox) {
									checkbox.checked = true;
								}
							});
						}
					});
			});

			//Selector multiple para las Membresias Usuarios
			//Si se selecciona un usuario o se desmarca uno
			document.body.addEventListener('change', function(e) {
				const checkbox = e.target;
				if (!checkbox.matches('input[type="checkbox"]')) return;

				const tipo = checkbox.dataset.tipo;
				const grupoSelect = document.getElementById('grupo_membresias');
				const grupo = grupoSelect ? grupoSelect.value : null;
				const idusua = checkbox.value;
				const esusua = checkbox.checked;

				const datos =
					'accion=grabarUsuariosDelGrupo' +
					'&usuario=' + idusua +
					'&estado=' + esusua +
					'&grupo=' + grupo;

				let actualizar_membresia = true;

				if (grupo == 2) {
					actualizar_membresia = confirm('¿Esta seguro de cambiar el jefe del área?');
				}

				if (actualizar_membresia) {

					fetch('ajaxPermisos.php', {
							method: 'POST',
							headers: {
								'Content-Type': 'application/x-www-form-urlencoded'
							},
							body: datos
						})
						.then(response => response.json())
						.then(data => {

							if (grupo == 2) {
								if (grupoSelect) {
									grupoSelect.dispatchEvent(new Event('change', {
										bubbles: true
									}));
								}

								if (data['estado'] == 1) {
									mostrarAlerta('.alert-success', data['valor']);
								} else {
									mostrarAlerta('.alert-danger', data['valor']);
								}

							} else {

								if (data['estado'] == 1) {

									if (Array.isArray(data['valor'])) {
										data['valor'].forEach(function(val) {
											const option = document.querySelector(
												'#usuarios_membresias option[value="' + val + '"]'
											);
											if (option) option.selected = true;
										});
									}

									mostrarAlerta('.alert-success', data['valor']);

								} else {
									mostrarAlerta('.alert-danger', data['valor']);
								}
							}
						});

				} else {
					checkbox.checked = !checkbox.checked;
					e.preventDefault();
				}
			});

			function mostrarAlerta(selector, mensaje) {
				const alert = document.querySelector(selector);
				if (!alert) return;

				alert.innerHTML = mensaje;
				alert.style.display = 'block';

				setTimeout(() => {
					alert.style.display = 'none';
				}, 3000);
			}
		});
	</script>
</body>
</html>
