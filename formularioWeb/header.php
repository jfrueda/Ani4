<?php 
session_start();
$tema = isset($_SESSION['tema']) ? $_SESSION['tema'] : 'claro';
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link rel="icon" href="css/favicon.ico" type="image/x-icon">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="https://cdn.www.gov.co/v2/assets/cdn.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css" />
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
		<link rel="stylesheet" type="text/css" href="css/fineuploader.css" />
		<!--<link rel="stylesheet" type="text/css" href="css/govco-style.css" />-->
		<link rel="stylesheet" type="text/css" href="css/bootstrap-select.css">
		<link rel="stylesheet" type="text/css" href="css/bootstrap-icons.css">
		<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.min.css">
		<link rel="stylesheet" type="text/css" href="css/fineuploader.css" />
		<link rel="stylesheet" type="text/css" href="css/accesibilidad.css" />
		<link href="https://www.supersalud.gov.co/Style%20Library/PortalWeb/css/govco-style.css" rel="stylesheet">
		<link href="https://www.supersalud.gov.co/Style%20Library/PortalWeb/css/estilogov.css" rel="stylesheet">
		
		<title>Gestión Documental y Trámites</title>
		<style>
			footer h4, 
			footer h3 { color: #fff; }
			h4.text-success {
				color: #198820 !important;
				margin-bottom: revert;
			}

			.hide {
				display: none;
			}

			.title {
				text-align: center;
			}

			.fecha {
				text-align: right;
			}

			.section-h {
				margin-top: 14px;
				padding-bottom: 10px;
				border-bottom:1px solid #ccc;
			}

			.email-component {
				position: relative;
				padding-left: 20px !important;
			}

			.email-component:after {
				content: '@';
				position: absolute;
				left: 0px;
				top: 38px;
			}

			.filter-option-inner-inner {
				color: black !important;
			}
			
			input::-webkit-outer-spin-button,
			input::-webkit-inner-spin-button {
				-webkit-appearance: none;
				margin: 0;
			}

			/* Firefox */
			input[type=number] {
				-moz-appearance: textfield;
			}

			.list-group-item-action:hover {
				background-color: #004884;
				color: #ffffff !important;
			}

			.alert-warning {
				background-color: #C8F1CB;
				border-color: #C8F1CB;
				color: #000;
			}

			.qq-upload-button {
				background-color: #28a745;
				border-color: #1c7430;
			}

			.limited-textarea {
				position: relative;
			}

			.limited-textarea .size {
				position: absolute;
				font-size: 12px;
				top: 10px;
				right: 10px;
			}

			/*
			div.col-md-6.is-invalid,
			div.col-md-8.is-invalid,
			div.col-md-12.is-invalid {
				border-bottom: 1px solid #dc3545;
				border-radius: 4px;
				padding-bottom: 3px;
			}
			*/

			.fuente_titulo {
				font-family: Helvetica !important;
				color: #198820;
			}
			
			a, p{
				font-family: Helvetica !important;
			}

			a.disabled {
				color: #AAA !important;
				cursor: not-allowed;
			}
			
			.btn-ic {
				margin: 0;
    			padding: 2px 8px;
			}

			.btn.dropdown-toggle {
				max-height: 38px;
				border-radius: 4px;
			}

			.btn-ic:hover i{
				color: #fff;
			}

			.error {
				display:none;
			}

			.has-error .error {
				display: block;
				color: #a80521;
			}

			.descargar {
				display: none;
			}
			.lds-ellipsis {
				display: inline-block;
				position: relative;
				left: 50%;
				top: 50%;
				width: 80px;
				height: 80px;
				margin-left: -40px;
				margin-top: -40px;
			}
			.lds-ellipsis div {
				position: absolute;
				top: 33px;
				width: 13px;
				height: 13px;
				border-radius: 50%;
				background: #3366cc;
				animation-timing-function: cubic-bezier(0, 1, 1, 0);
			}
			.lds-ellipsis div:nth-child(1) {
				left: 8px;
				animation: lds-ellipsis1 0.6s infinite;
			}
			.lds-ellipsis div:nth-child(2) {
				left: 8px;
				animation: lds-ellipsis2 0.6s infinite;
			}
			.lds-ellipsis div:nth-child(3) {
				left: 32px;
				animation: lds-ellipsis2 0.6s infinite;
			}
			.lds-ellipsis div:nth-child(4) {
				left: 56px;
				animation: lds-ellipsis3 0.6s infinite;
			}
			@keyframes lds-ellipsis1 {
				0% {
					transform: scale(0);
				}
				100% {
					transform: scale(1);
				}
				}
				@keyframes lds-ellipsis3 {
				0% {
					transform: scale(1);
				}
				100% {
					transform: scale(0);
				}
				}
				@keyframes lds-ellipsis2 {
				0% {
					transform: translate(0, 0);
				}
				100% {
					transform: translate(24px, 0);
				}
			}
			.loader {
				display: none;
				z-index: 10000000;
				position: fixed;
				width: 100%;
				height: 100%;
				top: 0;
				left: 0;
				background-color: rgba(255,255,255,.8);
			}
			.altcha-error{
				--altcha-color-border:#a80521;
			}
			.dropdown-item.active span.text {
				color: #000;
			}
		</style>
		<script src="https://eu.altcha.org/js/latest/altcha.min.js" type="module" async defer></script>
	</head>
	<script type="text/javascript">
		/*window.addEventListener( "pageshow", function ( event ) {
		  var historyTraversal = event.persisted || 
								 ( typeof window.performance != "undefined" && 
									  window.performance.navigation.type === 2 );
		  if ( historyTraversal ) {
			// Handle page restore.
			window.location.reload();
		  }
		});*/
	</script>
	<body class="<?= $tema == 'oscuro' ? 'oscuro' : '' ?>">
		<div class="block block--gov-accessibility position-inherit">
			<div class="block-options navbar-expanded">
				<a
					class="contrast-ref" data-tema="<?= $tema ?>">
					<span class="govco-icon govco-icon-contrast-n"></span>
					<label> Contraste </label>
				</a>
				<a class="min-fontsize">
					<span class="govco-icon govco-icon-less-size-n"></span>
					<label class="align-middle"> Reducir letra </label>
				</a>
				<a class="max-fontsize">
					<span class="govco-icon govco-icon-more-size-n"></span>
					<label class="align-middle"> Aumentar letra </label>
				</a>
			</div>
		</div> 
		<header class="he_header">
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-9 he_logo">
						<a href="https://www.gov.co" title="Ir al sitio web gov.co" style="margin-left:10px;"> <img src="./images/logo-govco.svg" alt="logo de gov.co"> </a> 
					</div>
				</div>			  
			</div>
		</header>
	  	<div class="container">
			<div id="SNSHeader-brand" class="row justify-content-between">
				<div class="col-4" style="text-align:left;">
					<a href="http://www.supersalud.gov.co/" target="_blank">
						<img alt="Supersalud logo" src="./images/Logo-Supersalud-2024.svg"  style="height: 60px;" align="center">
					</a>
				</div>
			</div>
		</div>