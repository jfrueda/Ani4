<?php
session_start();
// Establish database connection
$db = new ConnectionHandler("$ruta_raiz");

// Consultar las alertas disponibles que no han sido leídas por el usuario
$currentDate = date('Y-m-d');
$sqlalertas = "SELECT a.* FROM alertas a 
                   LEFT JOIN alerta_leida al ON a.id = al.notificacion_id 
                   AND (al.usua_codi = $codusuario 
                   OR al.usua_doc = '$usua_doc')
                   WHERE a.fecha_inicial <= '$currentDate' 
                   AND a.fecha_final >= '$currentDate'
                   AND (a.depe_codi = $dependencia
                   OR a.depe_codi IS NULL)
                   AND al.id IS NULL";

$resultalertas = $db->conn->query($sqlalertas);

// Verificar si existen alertas activas no leídas
$alertas = [];
if ($resultalertas) {
    while ($row = $resultalertas->fetchRow()) {
        $alertas[] = $row;
        // Insertar registro de alerta leída
        try {
            $sqlInsert = "INSERT INTO alerta_leida (notificacion_id, usua_codi, usua_doc) 
                             VALUES ({$row['ID']}, $codusuario, '$usua_doc')";
            $db->conn->Execute($sqlInsert);
        } catch (Exception $e) {
            continue;
        }
    }
} else {
    echo "Error fetching records: " . $db->conn->ErrorMsg();
}

$db->conn->close();
?>
<script src="include/vue/vue.js"></script>
<style>
    /* Estilo personalizado para el modal */
    .custom-modal {
        border-radius: 10px;
        border: 1px solid #00381f;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        padding: 20px;
    }

    .custom-modal .modal-header {
        border-bottom: 2px solid #00381f;
        text-align: center;
    }

    .custom-modal .modal-title {
        font-weight: bold;
        font-size: 1.5rem;
    }

    .custom-modal .modal-body {
        font-size: 1.1rem;
        color: #333;
        text-align: center;
    }

    .custom-modal .modal-footer {
        border-top: 2px solid #00381f;
        text-align: center;
    }

    .custom-modal button {
        font-weight: bold;
        text-transform: uppercase;
    }

    /* Estilo para los botones */
    .btn-primary {
        background-color: #00381f;
        border: none;
    }

    .btn-primary:hover {
        background-color: #004080;
    }

    .btn-danger {
        background-color: #d9534f;
        border: none;
    }

    .btn-danger:hover {
        background-color: #c9302c;
    }
</style>
<!-- Modal para mostrar las alertas -->
<div id="alertasApp">
    <div class="modal fade" id="alertasModal" tabindex="-1" role="dialog" aria-labelledby="alertasModalLabel" aria-hidden="true" v-if="alertas.length > 0">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 70%; width: 70%;">
            <div class="modal-content custom-modal">
                <div class="modal-header">
                    <img src="./imagenes/logo-super.png" alt="Logo SuperArgo" class="img-fluid" style="max-height: 50px; margin-bottom: 10px;">
                    <h5 class="modal-title" id="alertasModalLabel">Notificaciones del Sistema</h5>
                </div>
                <div class="modal-body">
                    <!-- Mostrar la alerta actual -->
                    <!-- Show HTML if present, otherwise show text and image -->
                    <div v-if="alertas[currentAlertIndex].HTML && alertas[currentAlertIndex].HTML.trim() !== ''" v-html="decodeHtml(alertas[currentAlertIndex].HTML)"></div>
                    <template v-else>
                        <h5 class="alert-heading">{{ alertas[currentAlertIndex].TEXTO }}</h5>
                        <hr>
                    </template>
                    <img v-if="alertas[currentAlertIndex].IMAGENBASE64"
                        :src="'data:image;base64,' + alertas[currentAlertIndex].IMAGENBASE64"
                        :width="isImageEnlarged ? '100%' : '50%'"
                        alt="Imagen Alerta"
                        class="img-fluid"
                        @click="toggleImageSize" />
                </div>
                <div class="modal-footer">
                    <!-- Si hay más de una alerta, mostrar el botón "Next" -->
                    <button v-if="currentAlertIndex > 0" @click="prevAlert" type="button" class="btn btn-primary">Anterior</button>
                    <button v-if="currentAlertIndex < alertas.length - 1" @click="nextAlert" type="button" class="btn btn-primary">Siguiente</button>
                    <!-- Botón para cerrar el modal, deshabilitado por 5 segundos -->
                    <button v-if="alertas.length > 1 && currentAlertIndex === alertas.length - 1" :disabled="isCloseDisabled" type="button" class="btn btn-danger" @click="cerrarModal">Cerrar</button>
                    <button v-if="alertas.length == 1 " :disabled="isCloseDisabled" type="button" class="btn btn-danger" @click="cerrarModal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#alertasApp',
        data: {
            alertas: <?php echo json_encode($alertas); ?>,
            currentAlertIndex: 0,
            isCloseDisabled: true,
            isImageEnlarged: false
        },
        methods: {
            nextAlert() {
                this.currentAlertIndex++;
                if (this.currentAlertIndex === this.alertas.length - 1) {
                    // Si es la última alerta, deshabilitamos el botón Next
                    this.isCloseDisabled = false;
                }
            },
            prevAlert() {
                this.currentAlertIndex--;
            },
            cerrarModal() {
                $('#alertasModal').modal('hide');
            },
            toggleImageSize() {
                this.isImageEnlarged = !this.isImageEnlarged;
            },
            decodeHtml(html) {
                const txt = document.createElement('textarea');
                txt.innerHTML = html;
                return txt.value;
            }
        },
        mounted() {
            if (this.alertas.length > 0) {
                // Mostrar el modal automáticamente cuando haya alertas
                $('#alertasModal').modal({
                    show: true,
                    backdrop: 'static',
                    keyboard: false
                });
            }

            // Deshabilitar el botón Cerrar por 5 segundos
            setTimeout(() => {
                this.isCloseDisabled = false;
            }, 5000);
        }
    });
</script>