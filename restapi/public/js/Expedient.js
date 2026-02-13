

class Expedient {

	constructor(number) {
		this.number = number
	}

	closeExpedient() {
		swal({
			title: "Confirmación",
			text: `¿Estás segur@ que quieres cerrar el expediente #${this.number}?`,
			icon: "warning",
			buttons: ["Cancelar", "Si"],
			dangerMode: true,
		})
			.then(async (willClose) => {
				if (willClose) {
					const url = `${this.number}/close/`

					swal({
						title: "Cargando...",
						text: `Cerrando el expediente #${this.number}`,
						allowOutsideClick: false,
						buttons: false
					})

					$.ajax({
						method: "PUT",
						url: url,
					}).done(function (data) {
						swal({
							title: "El expediente se cerró correctamente",
							text: "Para cerrar pulsa el botón",
							icon: "success",
							button: "Cerrar"
						}).then((value) => {
							window.location.reload()
						});
					}).fail(function (data) {
						let message = ""
						if (data.responseText) {
							message = JSON.parse(data.responseText).errors
						}
						swal("Ocurrió un error", `${message}`, "error");
					})
				}
			});
	}

	linkNewExpedient(numberNewExpedient) {
		if (numberNewExpedient != "" && numberNewExpedient.charAt(numberNewExpedient.length - 1) == "E") {
			swal({
				title: "Confirmación",
				text: `¿Estás segur@ que quieres agrupar el expediente #${this.number} con el expediente #${numberNewExpedient}?`,
				icon: "warning",
				buttons: ["Cancelar", "Si"],
				dangerMode: true,
			})
				.then(async (willLinked) => {
					if (willLinked) {
						const url = `${this.number}/vincularExp/`
						const body = {
							numExpLink: numberNewExpedient
						}

						swal({
							title: "Cargando...",
							text: `Agrupando el expediente #${numberNewExpedient}`,
							allowOutsideClick: false,
							buttons: false
						})

						$.ajax({
							method: "POST",
							url: url,
							data: JSON.stringify(body),
							contentType: "application/json"
						}).done(function (data) {
							swal({
								title: "La vinculación se realizó correctamente",
								text: "Para cerrar pulsa el botón",
								icon: "success",
								button: "Cerrar"
							}).then((value) => {
								$('#modalLinkExpedient').modal('hide');
								$('#expedientsDataTable').DataTable().ajax.reload();
							});
						}).fail(function (data) {
							let message = ""
							if (data.responseText) {
								message = JSON.parse(data.responseText).errors
							}
							swal("Ocurrió un error", `${message}`, "error");
						})
					}
				});
		} else {
			alert("El número del expediente no es valido")
		}
	}

	ungroundExpedient(numberUngroupExpedient) {
		if (numberUngroupExpedient != "" && numberUngroupExpedient.charAt(numberUngroupExpedient.length - 1) == "E") {
			swal({
				title: "Confirmación",
				text: `¿Estás segur@ que quieres desagrupar el expediente #${this.number} del expediente #${numberUngroupExpedient}?`,
				icon: "warning",
				buttons: ["Cancelar", "Si"],
				dangerMode: true,
			})
				.then(async (willUngroup) => {
					if (willUngroup) {
						const url = `${this.number}/ungroupExp/`
						const body = {
							numUngroupExp: numberUngroupExpedient
						}

						swal({
							title: "Cargando...",
							text: `Desagrupando el expediente #${numberUngroupExpedient}`,
							allowOutsideClick: false,
							buttons: false
						})

						$.ajax({
							method: "POST",
							url: url,
							data: JSON.stringify(body),
							contentType: "application/json"
						}).done(function (data) {
							swal({
								title: "La desvinculación se realizó correctamente",
								text: "Para cerrar pulsa el botón",
								icon: "success",
								button: "Cerrar"
							}).then((value) => {
								$('#modalUngroupExpedient').modal('hide');
								$('#expedientsDataTable').DataTable().ajax.reload();
							});
						}).fail(function (data) {
							let message = ""
							if (data.responseText) {
								message = JSON.parse(data.responseText).errors
							}
							swal("Ocurrió un error", `${message}`, "error");
						})
					}
				});
		} else {
			alert("El número del expediente no es valido")
		}
	}

	openExpedient(openingReason) {

		if (openingReason.length >= 30 && openingReason.length <= 350) {
			const url = `${this.number}/open/`
			const body = {
				reason: openingReason
			}

			swal({
				title: "Cargando...",
				text: `Abriendo el expediente #${this.number}`,
				allowOutsideClick: false,
				buttons: false
			})

			$.ajax({
				method: "POST",
				url: url,
				data: JSON.stringify(body),
				contentType: "application/json"
			}).done(function (data) {
				swal({
					title: "El expediente se reabrió correctamente",
					text: "Para cerrar pulsa el botón",
					icon: "success",
					button: "Cerrar"
				}).then((value) => {
					window.location.reload()
				});
			}).fail(function (data) {
				let message = ""
				if (data.responseText) {
					message = JSON.parse(data.responseText).errors
				}
				swal("Ocurrió un error", `${message}`, "error");
			})
		} else {
			alert("La observación debe tener entre 30 y 350 carácteres")
		}
	}

	getNumber() {
		return this.number;
	}
}
