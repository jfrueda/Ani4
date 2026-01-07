<?php include ('header.php') ?>
<?php 
$ruta_raiz = "../";
include_once "$ruta_raiz/processConfig.php";

// Get base64 image from configuration variable
$src_imagen = 'img/mantenimiento.jpg'; // Default fallback

// Check if we have the base64 data for MANTENIMIENTO_PQRD
if (isset($MANTENIMIENTO_PQRD_base64) && !empty($MANTENIMIENTO_PQRD_base64)) {
    $src_imagen =  $MANTENIMIENTO_PQRD_base64;
}
?>
	<div class="loader">
		<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
	</div>
	<div class="container">
		<div class="row justify-content-between">
			<div class="col-sm">
				<p class="fecha">
					<small>Fecha radicación <?= date('d/m/Y H:i') ?></small>
				</p>
			</div>
		</div>
		<div class="row">
			<div class="col-sm justify-content-between" style="text-align:center">
                <img src="<?= $src_imagen ?>" style="width:95%; padding: 30px;" alt="Página en mantenimiento">
            </div>
        </div>
    </div>
	<script type="text/javascript" src="scripts/jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="scripts/popper.min.js"></script>
	<script type="text/javascript" src="scripts/bootstrap.min.js"></script>
	<script type="text/javascript" src="scripts/bootstrap-select.js"></script>
	<script type="text/javascript" src="scripts/bootstrap-autocomplete.min.js"></script>
	<script type="text/javascript" src="scripts/jquery.datetimepicker.full.min.js"></script>
	<script type="text/javascript" src="scripts/moment.min.js"></script>
	<script	type="text/javascript" src="scripts/accesibilidad.js"></script>
	<script src="https://cdn.www.gov.co/v2/assets/js/utils.js"></script>
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php include ('footer.php') ?>