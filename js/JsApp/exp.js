function listar(tp) {
    console.log('listar');
    $("#selTipoDoc").empty();
    $("#tpacc").val(tp);

    /**
     * $("#selTipoDoc" ).empty();
     * $("#selTipoDoc" ).append('<option value="0">-- Selecione --</option>');
    */
    campos = new Array();
    campos['codigo'] = 'codserie';
    campos['nombre'] = 'descrip';
    $('#busqueda').hide();
    $('#resulEstdatos2').hide();

    $('#resulEstdatos').show();
    selectSB = 'selTipoDoc';
    //   cargarselect(data, 'selTipoDoc', campos);
    var depe = $("#dependencia").val();
    var usuario = $("#usua_codi").val();
    var usaid = $("#usuaid").val();
    var usua_doc = $("#usua_doc").val();
    var anoDep = $("#anoDep").val();
    var search = $("#mysearch").val();

    parent.$('#processing-modal').modal('show');

    axios({
        method: 'post',
        baseURL: 'exp-rest.php',
        data: 'fn=listar&usua=' + usuario + '&depe=' + depe + '&usuaid=' + usaid + '&doc=' + usua_doc + '&tp=' + tp + "&anoDep=" + anoDep + '&search=' + search
    })
        .then(function (response) {
            console.log('1');
            //console.log(response);
            datos = response.data;
            if (tp == 'mi') $('#nomListado').html('Mis Expedientes');
            if (tp == 'de') $('#nomListado').html('Expedientes Dependencia');
            //console.log(data);
            $("#tb_listaexp tbody").empty();
            $.each(datos.data, function (index, value) {
                // console.log(value['NUM']);
                codigo = value['NUM'];
                fech = value['FECH'];
                responsable = value['RESPONSABLE'];
                depe = value['DEPE'];
                creador = value['CREADOR'];
                titulo = value['TITULO'];
                estado2 = value['ESTADO'] == 2 ? 'Anulado' : value['ESTADO'] == 1 ? 'Cerrado' : 'Abierto';
                indice_electronico = value['INDICE_ELECTRONICO'] ? '<a class="btn btn-xs btn-danger" title="Descargar indice electrónico" href="exp-indice-pdf.php?exp=' + codigo + '"><i class="fa fa-file" aria-hidden="true"></i></a>' : '';
                indice_electronico_excel = '<a class="btn btn-xs btn-success" title="Descargar indice electrónico XML" href="exp-indice-xls.php?exp=' + codigo + '"><i class="fa fa-file" aria-hidden="true"></i></a>';
                indice_electronico_xml = '<a class="btn btn-xs" title="Descargar indice electrónico XML" href="exp-indice-xml.php?exp=' + codigo + '"><i class="fa fa-file" aria-hidden="true"></i></a>';
                if (tp == 'ie') {
                    indice_electronico += ' ' + indice_electronico_excel + ' ' + indice_electronico_xml;
                }
                $styleEspe3cial = value['ESTADO'] == 2 ? 'table-danger' : value['ESTADO'] == 1 ? 'table-warning' : ''
                //   console.log('<tr> <td class = "text-right" > ' +' </td><td class = "text-right" > ' + codigo + ' </td><td >' + fech +'</td><td class = "text-right">' + titulo +'</td><td class = "text-right">' + responsable +'</td><td class="text-center" >' + creador +'</td><td class="text-center" >' + estado2 +'</td></tr>');
                btnA = '<button class="btn btn-xs btn-success btn-expediente" type="button" data-toggle="tooltip"  data-placement="top" data-exp="' + codigo + '" title="Ver detalles" ><i class="fa fa-folder-o"></i></button> ';
                //btnToS = '<button class="btn btn-xs btn-success btn-rp1detXLS" data-rep="3" data-toggle="modal" data-target="DetEsta"  data-tit="' + Medio +'" data-btns="'+codigo+'" '+datosbtnextra+' data-id='+codigo+'  data-btns=2 type="button" ><i class="fa fa-table" data-toggle="tooltip"  data-placement="top"  title="Descargar detalles en excel"></i></button> <div id="detXLST" class="float-right"></div>';
                $('#tb_listaexp').append('<tr class="' + $styleEspe3cial + '"> <td class = "text-left" > ' + btnA + ' </td><td class = "text-center" > ' + codigo + ' </td><td class = "text-center">' + fech + '</td><td class = "text-left">' + titulo + '</td><td class = "text-right">' + responsable + '</td><td class="text-center" >' + creador + '</td><td class="text-center" >' + estado2 + '</td><td class="text-center">' + indice_electronico + '</td></tr>');
            });
            setTimeout(function () {
                parent.$('#processing-modal').modal('hide');
            }, 2000);
        })
        .catch(function (error) {
            parent.$('#processing-modal').modal('hide');
            $('#processing-modal').modal('hide');
            if (error.hasOwnProperty('response') && Object.keys(error.response).length > 0) {
                $(this).showError('Error en petición', 'Estado del error: ' + error.response.status + '. Mensaje: ' + error.response.data.error);
            }
            //toastr.error(data.message, 'Error al Modificar ');
        });
}

$("#tb_listaexp").on("click", '.btn-expediente', function () {
    exp = $(this).data('exp');
    location.href = "verExp.php?exp=" + exp;
});

$("#tb_bsq_listaexp").on("click", '.btn-expediente', function () {
    exp = $(this).data('exp');
    location.href = "verExp.php?exp=" + exp;
});

$(function () {
    $('#btn-ccc').click(function () {
        console.log('ingreso');
        $('#busqueda').show();
        $('#herramientas').hide();
        $('#resulEstdatos').hide();
        $('#resulEstdatosHerr').hide();
    });

    $('#btn-herramientas').on('click', function () {
        console.log('ingreso');
        $('#busqueda').hide();
        $('#herramientas').show();
        $('#resulEstdatos').hide();
        $('#resulEstdatos2').hide();
    });

    //$('#herr_respSegUsuaDoc').selectpicker();
});

function configurarPaginacion(pagina_actual, total_registros, page_size = 100) {
    primer_pagina = 0;
    total_paginas = Math.ceil(total_registros / page_size);
    var paginador = '';

    if (total_paginas <= 1) {
        paginador = '';
    } else {
        // Lógica para determinar los límites de los botones de paginación
        let inicio = Math.max(1, pagina_actual - 2);
        let fin = Math.min(total_paginas, pagina_actual + 2);

        // Ajustar los límites cuando la página actual está cerca de los extremos
        if (pagina_actual <= 3) {
            fin = Math.min(5, total_paginas);
        } else if (pagina_actual >= total_paginas - 2) {
            inicio = Math.max(total_paginas - 4, 1);
        }

        paginador += '<button class="pagina btn btn-sm btn-outline-primary" data-page=1><i class="fa fa-angle-double-left" aria-hidden="true"></i></button>';
        for (let i = inicio; i <= fin; i++) {
            if (i >= 1 && i <= total_paginas && i !== pagina_actual) {
                paginador += `<button class="pagina btn btn-sm btn-outline-primary" style="margin-left:5px;" data-page=${i}>${i}</button>`;
            } else if (i === pagina_actual) {
                paginador += `<button class="pagina btn btn-sm btn-primary" style="margin-left:5px;" data-page=${i}><strong>${i}</strong></button>`;
            }
        }
        paginador += `<button class="pagina btn btn-sm btn-outline-primary" style="margin-left:5px;" data-page=${total_paginas}><i class="fa fa-angle-double-right" aria-hidden="true"></i></button>`;
        paginador += `<input id="numero_de_pagina_destino" type="number" value="${pagina_actual}" style="width:50px; margin-left:5px; line-height: 25px;" min=1 max=${total_paginas}> <button class="btn btn-sm disabled"> / ${total_paginas}</button> <button id="ir_a_pagina" class="btn btn-sm btn-outline-primary"> Ir </button>`;
    }

    $('#paginador').html(paginador);
}

function cargarTablaE() {
    $('#processing-modal').modal('hide');

}

function cargartabla(tp, filtro = 'ORrad', orden = 'desc', busq = 0) {
    $("#tb_listaexp").show();
    if (tp != '' && tp != this.tp) {
        $('#tb_listaexp').attr('data-page', '1');
    }

    if (tp == '') {
        tp = this.tp;
    } else {
        this.tp = tp;
    }

    console.log('cargartabla', tp, $('#tb_listaexp').attr('data-page'));
    var url = "../core/?exp/dtexp";
    $('#btnSearchTb').show();
    $('#Historico').hide();

    $('#processing-modal').modal('show');
    if (tp == 'E') {
        $('#btnSearchTb').hide();
    }
    //$('#imageproceso').show();
    $('#anexnume').val('');
    $('#radnume').val('');
    page = $('#tb_listaexp').attr('data-page');
    search = $('#tb_listaexp').attr('data-search');
    wbsq = '';
    wbusq = 0;
    if (busq == 1) {
        wbusq = 1;
        wbsq = $('#mysearch').val();
        //   $('#mysearch2').val('');
        if (wbsq.length != 14)
            return false;
    }

    numexp = $('#numExp').val();
    segperm = $('#premseg').val();
    $('#tb_listaexp tbody').empty();
    axios({
        method: 'post',
        baseURL: 'exp-rest.php',
        data: 'fn=listaDtExp&exp=' + numexp + '&atp=' + tp + '&wbsq=' + wbsq + '&page=' + page + '&filtro=' + filtro + '&search=' + search
    })
        .then(function (response) {
            console.log('2');
            //    console.log(value['FISICO']);
            datos = response.data;
            configurarPaginacion(datos.data.pagActal, datos.data.total, datos.data.page_size);
            // console.log(datos.data.dtexp);
            $("#tb_listaexp tbody").empty();
            $.each(datos.data.dtexp, function (index, value) {
                //  console.log(value['FISICO']);
                // console.log(value['NUM']);
                var numra = value['RADICA'];
                var tipo = value['TIPO'];
                var path = value['PATH'];
                var remite = value['REMITENTE'];
                var permiso_radicado = value['PERMISO_RADICADO'];
                asunto = value['ASUNTO'];
                tpdocc = value['TPDOC'];
                checkedtp = '<input type="checkbox" id="chks[]" value="' + numra + '" data-tipo="' + tipo + '" data-path="' + path + '">';
                carpeta = value['CARPETA'] ? value['CARPETA'] : '';
                fisico = value['FISICO'] ? value['FISICO'] : 'FISICO';
                subexp = value['SUBEXPB'] ? value['SUBEXPB'] : '';

                colortp = ''; dtanu = ''; btneconte = ''; tpordeoda = subexp = aligntext = tpordeoda = srbdd = btnview = btnedit = '';

                //   console.log('<tr> <td class = "text-right" > ' +' </td><td class = "text-right" > ' + codigo + ' </td><td >' + fech +'</td><td class = "text-right">' + titulo +'</td><td class = "text-right">' + responsable +'</td><td class="text-center" >' + creador +'</td><td class="text-center" >' + estado2 +'</td></tr>');
                if (segperm != 1 && segperm != 4) {
                    if (segperm == 3) {
                        if (value['TIPO'] == 'radi')
                            btnedit = '<a class="btn btn-xs btn-danger float-right" data-toggle2="tooltip" data-original-title="Modificar "  alt="Gestionar ' + value['RADICA'] + '" href="#" onclick="modificaDoc(\'' + value['TIPO'] + '\',\'' + value['RADICA'] + '\',\'\');" data-toggle="modal" data-target="#ModalOperDoc"><i class="fa fa-pencil" aria-hidden="true"></i></a >';
                        if (value['TIPO'] == 'aexp')
                            btnedit = '<a class="btn btn-xs btn-danger float-right" data-toggle2="tooltip" data-original-title="Modificar "  alt="Gestionar ' + value['RADICA'] + '" href="#" onclick="modificaDoc(\'' + value['TIPO'] + '\',\'' + numexp + '\',\'' + value['RADICA'] + '\');" data-toggle="modal" data-target="#ModalOperDoc"><i class="fa fa-pencil" aria-hidden="true"></i></a >';
                        //   else
                        //btnedit = '<a class="btn btn-xs btn-danger float-right" data-toggle2="tooltip" data-original-title="Modificar "  alt="Gestionar ' + value['RADICA'] + '" href="#" onclick="modificaDoc(\'' + value['TIPO'] + '\',\'' + numexp + '\',\'' + value['RADICA'] + '\');" data-toggle="modal" data-target="#ModalOperDoc"><i class="fa fa-pencil" aria-hidden="true"></i></a >';
                        if (value['RADICA'] === null)
                            btnedit = '<a class="btn btn-xs btn-warning float-right" data-toggle2="tooltip" data-original-title="Modificar "  alt="Gestionar ' + value['RADICA'] + '" href="#" onclick="window.open(\'anexov1.php?p=' + value['PATH'] + '&a=' + value['ASUNTO'] + '\');"><i class="fa fa-pencil" aria-hidden="true"></i></a >';

                    }
                    if (permiso_radicado == '0') {
                        btnview = '<a class="btn btn-danger btn-xs float-right" href="javascript:alert(\'No tiene permiso para acceder\');"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                    } else {
                        btnview = '<a class="btn btn-xs btn-success float-right btn-visorimage" data-toggle="modal" data-target="ModalviewImg2"  href="#s" data-toggle2="tooltip" data-original-title="Ver " data-tp="' + value['TIPO'] + '" data-link="' + value['PATH'] + '" data-rad="' + value['RADICA'] + '" alt="Ver imagen ' + value['RADICA'] + '"  onclick=\'visorimagex("' + value['RADICA'] + '" ,"' + value['TIPO'] + '" ,"' + value['PATH'] + '","' + numexp + '" )\'><i class="fa fa-eye" aria-hidden="true"></i></a >';
                    }

                    if (value['TIPO'] != 'aexp' && value['TIPO'] != 'aexpe')

                        if (permiso_radicado == '0') {
                            btneconte = '<a class="btn btn-danger btn-xs float-right" href="javascript:alert(\'No tiene permiso para acceder\');"><i class="fa fa-list" aria-hidden="true"></i></a>';
                        } else {
                            btneconte = '<a class="btn btn-xs btn-primary float-right btn-visorimage" data-toggle="modal" data-target="ModalviewImg2"  href="#s" data-toggle2="tooltip" data-original-title="Ver " data-tp="' + value['TIPO'] + '" data-link="' + value['PATH'] + '" data-rad="' + value['RADICA'] + '" alt="Ver imagen ' + value['RADICA'] + '"  onclick=\'verRad("' + value['RADICA'] + '" ,"' + value['TIPO'] + '", "' + value['KEY'] + '" )\'><i class="fa fa-list" aria-hidden="true"></i></a >';
                        }
                }
                // if(value['PATH']==NULL) btnview='';
                /*         <a href="#" class="btn btn-success btn-xs btn-visorimage" data-toggle="modal" data-target="DetEsta" contador="6" data-link="../bodega/2021/7010/docs/20217010113394100001.pdf" data-rad="202170101133941">                          <i class="fa fa-eye"></i>
                </a>*/
                //   btnview += '<a class="btn btn-xs btn-info float-right"  href="#s" data-toggle2="tooltip" data-original-title="Ver en pest'aña" alt="Ver imagen pestaña' + value['RADICA'] + '" onclick="visorimageIT2(\'' + value['PATH'] + '\',\'' + value['SUBSTR'] + '\',\''+value['RADICA']+'\',\'e\');" ><i class="fa fa-window-maximize"></i></a >';

                //  btnA = '<button class="btn btn-xs btn-success btn-expediente" type="button" data-toggle="tooltip"  data-placement="top" data-exp="'+codigo+'" title="Ver detalles" ><i class="fa fa-folder-o"></i></button> ';  onclick="visorimageIT2(\'' + value['PATH'] + '\',\'' + value['SUBSTR'] + '\',\'' + value['RADICA'] + '\',\'i\');"
                //btnToS = '<button class="btn btn-xs btn-success btn-rp1detXLS" data-rep="3" data-toggle="modal" data-target="DetEsta"  data-tit="' + Medio +'" data-btns="'+codigo+'" '+datosbtnextra+' data-id='+codigo+'  data-btns=2 type="button" ><i class="fa fa-table" data-toggle="tooltip"  data-placement="top"  title="Descargar detalles en excel"></i></button> <div id="detXLST" class="float-right"></div>';
                //    $('#tb_listaexp').append('<tr '+dtanu+' class=" ' + colortp + ' " style="font-size:11px"><td>' + btnview + btnedit + btneconte + '</td><td>' + tpordeoda + '</td><td>' + aligntext + '</td><td style="' + aligntext + '">' + numra + '</td><td style="text-align: center">' + value['FECHA'] + '</td><td style="text-align: center" >' + srbdd + '</td>\n\
                //                   <td style="text-align: center" id="divSubTP'+numra+'">' + tpdocc + '</td><td id="divAnexExpAsunto' + numra + '">' +asunto + '</td><td>' + remite + '</td><td id="divCarp' + numra + '">' + carpeta + '</td><td id="divFisico' + numra + '">' + fisico + '</td><td id="divSubExp' + numra + '"> ' + subexp + '</td></tr>');
                //  console.log(numra+  value['RADICA']);
                // console.log('<tr '+dtanu+' class=" ' + colortp + ' " style="font-size:11px"><td><td>' + btnview + btnedit + btneconte + '</td><td style="' + aligntext + '">' + numra + '</td><td style="text-align: center">' + value['FECHA'] + '</td><td style="text-align: center" >' + srbdd + '</td><td style="text-align: center" id="divSubTP'+numra+'">' + tpdocc + '</td><td id="divAnexExpAsunto' + numra + '">' +asunto + '</td><td>' + remite + '</td><td id="divSubExp' + numra + '"> ' + subexp + '</td></tr>');
                htmlf = '<tr ' + dtanu + ' class=" ' + colortp + ' " style="font-size:11px"><td>' + btnview + btnedit + btneconte + '</td><td>' + checkedtp + '</td><td style="' + aligntext + '">' + numra + '</td><td style="text-align: center">' + value['FECHA'] + '</td><td style="text-align: center" >' + srbdd + '</td><td style="text-align: center" id="divSubTP' + numra + '">' + tpdocc + '</td><td id="divAnexExpAsunto' + numra + '">' + asunto + '</td><td>' + remite + '</td><td id="divCarpeta' + numra + '"> ' + carpeta + '</td><td id="divSubExp' + numra + '"> ' + subexp + '</td><td id="divFisico' + numra + '">' + fisico + '</td></tr>';
                $('#tb_listaexp').append(htmlf);
                /*anexos*/
                $.each(datos.data.anexos[numra], function (index, value) {
                    // console.log(value['NUM']);
                    var anex = value['ANEX'];
                    var remite = value['REMITENTE'];
                    asunto = value['ASUNTO'];
                    tpdocc = value['TPDOC'];
                    radsal = value['RASAL'];
                    var anextxt = value['ANEX'].substr(16);
                    if (radsal && radsal != numra) var anextxt = radsal + '  (' + value['ANEX'].substr(16) + ')';
                    subexp = value['SUBEXPB'];
                    colortp = ''; dtanu = ''; btneconte = ''; tpordeoda = subexp = aligntext = tpordeoda = srbdd = btnview = btnedit = '';
                    colortp = ' table-default ';
                    //   console.log('<tr> <td class = "text-right" > ' +' </td><td class = "text-right" > ' + codigo + ' </td><td >' + fech +'</td><td class = "text-right">' + titulo +'</td><td class = "text-right">' + responsable +'</td><td class="text-center" >' + creador +'</td><td class="text-center" >' + estado2 +'</td></tr>');
                    //btnedit = '<a class="btn btn-xs btn-danger float-right" data-toggle2="tooltip" data-original-title="Modificar "  alt="Gestionar ' + value['RADICA'] + '" href="#" onclick="modificaDoc(\'' + value['TIPO'] + '\',\'' + value['RADICA'] + '\',\'\');" data-toggle="modal" data-target="#ModalOperDoc"><i class="fa fa-pencil" aria-hidden="true"></i></a >';

                    btnview = '<a class="btn btn-xs btn-success float-right" href="#s" data-toggle2="tooltip" data-original-title="Ver en pestaña" alt="Ver imagen en pestaña ' + value['ANEX'] + '" onclick="visorimageIT2(\'' + value['PATH'] + '\',\'' + value['SUBSTR'] + '\',\'' + value['ANEX'] + '\',\'i\');" ><i class="fa fa-eye" aria-hidden="true"></i></a >';
                    btnview += '<a class="btn btn-xs btn-info float-right"    href="#s" data-toggle2="tooltip" data-original-title="Ver en pestaña" alt="Ver imagen en pestaña ' + value['ANEX'] + '" onclick="visorimageIT2(\'' + value['PATH'] + '\',\'' + value['SUBSTR'] + '\',\'' + value['RADICA'] + '\',\'e\');" ><i class="fa fa-window-maximize"></i></a >';
                    if (permiso_radicado == '0') {
                        btnview = '<a class="btn btn-danger btn-xs float-right" href="javascript:alert(\'No tiene permiso para acceder\');"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                    } else {
                        btnview = '<a class="btn btn-xs btn-success float-right btn-visorimage" data-toggle="modal" data-target="ModalviewImg2"  href="#s" data-toggle2="tooltip" data-original-title="Ver " data-tp="' + value['TIPO'] + '" data-link="' + value['PATH'] + '" data-rad="' + value['RADICA'] + '" alt="Ver imagen ' + value['RADICA'] + '"  onclick=\'visorimagex("' + value['RADICA'] + '" ,"' + value['TIPO'] + '" ,"' + value['PATH'] + '","' + numexp + '" )\'><i class="fa fa-eye" aria-hidden="true"></i></a >';
                    }
                    htmlf = '<tr ' + dtanu + ' class=" ' + colortp + ' " style="font-size:11px"><td>' + btnview + btnedit + btneconte + '</td><td></td><td style="text-align: right;">' + anextxt + '</td><td style="text-align: center">' + value['FECHA'] + '</td><td style="text-align: center" >' + srbdd + '</td><td style="text-align: center" id="divSubTP' + anex + '">' + tpdocc + '</td><td id="divAnexExpAsunto' + anex + '">' + asunto + '</td><td>' + remite + '</td><td id="divSubExp' + numra + '"> ' + subexp + '</td></tr>';
                    $('#tb_listaexp').append(htmlf);
                });
            });
        })
        .catch(function (error) {
            if (error.hasOwnProperty('response') && Object.keys(error.response).length > 0) {
                $(this).showError('Error en petición', 'Estado del error: ' + error.response.status + '. Mensaje: ' + error.response.data.error);
            }
            $('#processing-modal').modal('hide');
            //toastr.error(data.message, 'Error al Modificar ');
        })
        .finally(function () {
            console.log('finally ');
            $('#processing-modal').modal('hide');
            setTimeout(function () {
                $('#processing-modal').modal('hide');
            }, 2000)
        });

    $('#processing-modal').modal('hide');
}

function cargartablaHisto(tphist = 'tb_listaHistoexp') {
    //  tb_listaHistoexpCons   tb_listaHistoexp tb_listaHistoexpArch
    $('#tb_listaexp').hide();
    $('#tb_listaHistoexpCons').hide();
    $('#tb_listaHistoexp').hide();
    $('#tb_listaHistoexpArch').hide();
    //$('#listado').hide();
    $('#processing-modal').modal('show');
    $('#tphist').show();
    $('#tipohistorico').val(tphist);
    $('#Historico').show();
    var url = "../core/?exp/dtexp"
    $('#btnSearchTH').hide();
    // ajax adding data to database
    //    $('#imageprocesohist').show();

    numexp = $('#numExp').val();

    $('#' + tphist + ' tbody').empty();
    axios({
        method: 'post',
        baseURL: 'exp-rest.php',
        data: 'fn=listaHistExp&exp=' + numexp + '&tipo=' + tphist
    })
        .then(function (response) {
            console.log('3');
            //console.log(response);
            datos = response.data;
            console.log(datos.data.dtexp);
            $("#tb_listaHistoexp tbody").empty();
            $("#tb_listaHistoexp").show();
            $.each(datos.data.dtexp, function (index, value) {
                // console.log(value['NUM']);
                var numra = value['RADICADO'];
                var dependecia = value['DEPE'];
                Fecha = value['FECHA'];
                Trans = value['TRASN'];
                usuario = value['USUARIO'];
                comentrario = value['HIST_OBSERVA'];
                colortp = ''; dtanu = ''; btneconte = ''; tpordeoda = subexp = aligntext = tpordeoda = srbdd = btnview = btnedit = '';
                htmlf = '<tr class=" ' + colortp + ' " style="font-size:11px"><td>' + dependecia + '</td><td style="text-align: center">' + Fecha + '</td><td style="text-align: center">' + Trans + '</td><td style="text-align: center" >' + usuario + '</td><td style="text-align: center" ">' + numra + '</td><td >' + comentrario + '</td></tr>';
                $('#tb_listaHistoexp').append(htmlf);

            });
            setTimeout(function () {
                parent.$('#processing-modal').modal('hide');
            }, 2000);
        })
        .catch(function (error) {
            if (error.hasOwnProperty('response') && Object.keys(error.response).length > 0) {
                $(this).showError('Error en petición', 'Estado del error: ' + error.response.status + '. Mensaje: ' + error.response.data.error);
            }
            $('#processing-modal').modal('hide');
            //toastr.error(data.message, 'Error al Modificar ');
        })
        .finally(function () {
            console.log('finally ');
            $('#processing-modal').modal('hide');
            setTimeout(function () {
                $('#processing-modal').modal('hide');
            }, 2000)
        });

    $('#processing-modal').modal('hide');
}

function modificaDoc(tipo, radicado, anexo) {
    $('#tituloOperDoc').html('Modificar info ' + radicado + anexo);
    $('#tpdocAnexomod').hide();
    $('#tpdocAsuntomod').hide();

    if (tipo == 'aexp') {
        $('#tpdocAnexomod').show();
        $('#tpdocAsuntomod').show();
        Rcap = $('#divCarp' + anexo).text();
        Sbexp = $('#divSubExp' + anexo).text();
        Tpexp = $('#divSubTP' + anexo).text();
        FsExp = $('#divFisico' + anexo).text().toUpperCase();
        $('#aexpasunto').val($('#divAnexExpAsunto' + anexo).text().toUpperCase().trim());
        $('#tpdocMD').val($('select[name="tpdocMD"] option:contains(' + Tpexp + ')').val());
        //$('#tpdocMD option:contains(Acta)')
    } else {
        Rcap = $('#divCarp' + radicado).text();
        Sbexp = $('#divSubExp' + radicado).text();
        FsExp = $('#divFisico' + radicado).text().toUpperCase();
    }

    $('#tipooc').val(tipo);
    $('#numerpDoc').val(radicado);
    $('#numerAexp').val(anexo);
    $('#operCarpi').val(Rcap.trim());
    $('#operaddsubei').val(Sbexp.trim());
    $('#operFisicoMasi').val(FsExp.trim());

    $('#txaccI').html('');
}

// tb_listaHistoexpresp
$(document).ready(function () {
    $("#mysearchhisto").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#" + $('#tipohistorico').val() + " tbody tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > - 1)
        });
        // $('#tb_titulo').show();
    });
});

$(function () {
    $('#dependenciaExp').on('change', function () {
        series();
        $("#selSubSerie").empty();
        usuario();
        if ($(this).data('dependencias-clasificadas-trigger') == true) {
            var dependencias = $(this).data('dependencias-clasificadas').split(',');
            if (dependencias.includes($(this).val())) {
                $('#idseguridad').html('<option value="2">Pública clasificada (usuario que proyectó, jefe y usuario actual)</option>');
            }
        } else if (['95000', '95001', '1400'].includes($(this).val())) {
            $('#idseguridad').html('<option value="1">Pública reservada (solo la dependencia)</option>');
        } else {
            $('#idseguridad').html('<option value="0">Pública</option>' +
                '<option value="1">Pública reservada (solo la dependencia)</option>' +
                '<option value="2">Pública clasificada (usuario que proyectó, jefe y usuario actual)</option>'
            );
        }
    });
    $('#dependenciaExp').trigger('change');
    $('#selSerie').change(function () {
        subSeries();
        $("#selSubSerie").empty();
    });
    $('#selSubSerie').change(function () {
        var ser = $('#selSerie option:selected').text().split('-');
        var sub = $('#selSubSerie option:selected').text().split('-');
        if (ser[1] < 10) sertxt = '0' + ser[1]; else sertxt = ser[1];
        if (sub[1] < 10) subtxt = '0' + sub[1]; else subtxt = sub[1];
        $("#numsrb").val(sertxt + subtxt);
        deope = $('#dependenciaExp').val();
        if (deope.length == 3)
            deope = '00' + $('#dependenciaExp').val();
        $('#depExp').val(deope);
    });
    $('#bsq_dep').change(function () {
        usuarioBSQ('bsq');
    });
    $('#herr_dep').change(function () {
        usuarioBSQ('herr');
    });
    $('#herr_dep_resp').change(function () {
        usuarioBSQ('herr-resp');
    });
    $('#herr_dep_seg_resp').change(function () {
        usuarioBSQ('herr-resp-seg');
    });
    $('#dependenciaExpO').change(function () {
        usuario2();
    })

    $('#paginador').delegate('.pagina', 'click', function (e) {
        $('#tb_listaexp').attr('data-page', $(this).data('page'));
        console.log($('#tb_listaexp').attr('data-page'));
        cargartabla('');
    });

    $('#paginador').delegate('#ir_a_pagina', 'click', function (e) {
        var min = 1;
        var max = $('#numero_de_pagina_destino').attr('max');
        var pagina = $('#numero_de_pagina_destino').val() * 1;
        if (isNaN(pagina) || (pagina < min || pagina > max)) {
            alert('Valor invalido');
        } else {
            $('#tb_listaexp').attr('data-page', pagina);
            cargartabla('');
        }
    });

    $('#btnsearch').on('click', function () {
        var search = $('#mysearch').val();
        $('#tb_listaexp').attr('data-search', search);
        $('#tb_listaexp').attr('data-page', '1');
        console.log($('#tb_listaexp').attr('data-page'));
        cargartabla('');
    });

    $('#processing-modal').on('shown.bs.modal', function () {
        console.log('show');
    });

    $('#processing-modal').on('hide.bs.modal', function () {
        console.log('hide');
    });
});

function series() {
    selectID1 = 'selSerie';
    /*$("#"+selectID1).empty();
    $("#"+selectID1).append('<option value="0">-- Selecione --</option>');*/
    campos = new Array();
    campos['codigo'] = 'codserie';
    campos['nombre'] = 'descrip';
    data = new Array();
    data[0] = new Array();
    data[0]['codserie'] = '1';
    data[0]['descrip'] = 'prueba';
    var depe = $("#dependenciaExp").val();
    // console.log($("#dependenciaExp").val());
    //cargarselect(data, selectID, campos);
    $('#animationload').show();
    axios({
        method: 'post',
        baseURL: '../core/rest-est.php',
        data: 'fn=serie&dep_busq=' + depe
    })
        .then(function (response) {
            //   console.log(response.data);
            data = response.data;
            $("#" + selectID1).empty();
            $("#" + selectID1).append('<option value="0">-- Selecione --</option>');
            campos = new Array();
            campos['codigo'] = 'COD';
            campos['id'] = 'ID';
            campos['nombre'] = 'NOMB';
            console.log('Serie');
            cargarselect(data.data, selectID1, campos, 2);
            $('#animationload').hide();
        })
        .catch(function (error) {
            $('#animationload').hide();
            if (error.hasOwnProperty('response') && Object.keys(error.response).length > 0) {
                $(this).showError('Error en petición', 'Estado del error: ' + error.response.status + '. Mensaje: ' + error.response.data.error);
            }
            //toastr.error(data.message, 'Error al Modificar ');
        });

}

function subSeries() {
    selectID = 'selSubSerie';
    /*
    $("#"+selectID).empty();
    $("#"+selectID ).append('<option value="0">-- Selecione --</option>');*/
    campos = new Array();
    campos['codigo'] = 'codserie';
    campos['nombre'] = 'descrip';
    data = new Array();
    data[0] = new Array();
    data[0]['codserie'] = '1';
    data[0]['descrip'] = 'prueba';
    var depe = $("#dependenciaExp").val();
    //var serie =$("#selSerie").val();
    var serie = $('select[name="selSerie"]').val();
    //    console.log($("#dependenciaExp").val());
    //cargarselect(data, selectID, campos);
    $('#animationload').show();
    axios({
        method: 'post',
        baseURL: '../core/rest-est.php',
        data: 'fn=subSeries&serie=' + serie + '&dep_busq=' + depe
    })
        .then(function (response) {
            // console.log(response);
            data = response.data;
            $("#" + selectID).empty();
            $("#" + selectID).append('<option value="0">-- Selecione --</option>');
            campos = new Array();
            campos['codigo'] = 'COD';
            campos['id'] = 'ID';
            campos['nombre'] = 'NOMB';
            console.log('prueba');
            cargarselect(data.data, selectID, campos, 2);
            $('#animationload').hide();
        })
        .catch(function (error) {
            $('#animationload').hide();
            if (error.hasOwnProperty('response') && Object.keys(error.response).length > 0) {
                $(this).showError('Error en petición', 'Estado del error: ' + error.response.status + '. Mensaje: ' + error.response.data.error);
            }
            //toastr.error(data.message, 'Error al Modificar ');
        });

}

function usuario() {
    selectIDu = 'selUsuario'
    var depe = $("#dependenciaExp").val();
    var activo = $("#dependenciaExp").val() ? '1' : '0';
    var tpUs = $("#CHKselUsuario").is(':checked') ? '1' : '0';
    $('#animationload').show();
    //console.log('fn=usuarios&depe='+depe+'&tpus='+tpUs);
    axios({
        method: 'post',
        baseURL: '../core/rest-est.php',
        data: 'fn=usuarios&depe=' + depe + '&tpus=' + tpUs
    })
        .then(function (response) {
            // console.log(response);
            data = response.data;
            //  console.log(data);
            $("#" + selectIDu).empty();
            $("#" + selectIDu).append('<option value="0">-- Selecione --</option>');
            campos = new Array();
            campos['codigo'] = 'COD';
            campos['nombre'] = 'NOMB';
            console.log('prueba');
            cargarselect2(data.data, selectIDu, campos, 2);
            $('#animationload').hide();
        })
        .catch(function (error) {
            $('#animationload').hide();
            if (error.hasOwnProperty('response') && Object.keys(error.response).length > 0) {
                $(this).showError('Error en petición', 'Estado del error: ' + error.response.status + '. Mensaje: ' + error.response.data.error);
            }
            //toastr.error(data.message, 'Error al Modificar ');
        });


}

function usuario4() {
    selectIDu = 'selUsuario'
    var depe = $("#dependenciaExp3").val();
    var activo = $("#dependenciaExp3").val() ? '1' : '0';
    var tpUs = $("#CHKselUsuario").is(':checked') ? '1' : '0';
    $('#animationload').show();
    //console.log('fn=usuarios&depe='+depe+'&tpus='+tpUs);
    axios({
        method: 'post',
        baseURL: '../core/rest-est.php',
        data: 'fn=usuarios&depe=' + depe + '&tpus=' + tpUs
    })
        .then(function (response) {
            // console.log(response);
            data = response.data;
            //  console.log(data);
            $("#" + selectIDu).empty();
            //$("#" + selectIDu).append('<option value="0">-- Selecione --</option>');


            //data.data['a']['NOMB']=' TODOS';
            //  data.data['a']['COD']='0';
            campos = new Array();
            campos['codigo'] = 'COD';
            campos['nombre'] = 'NOMB';
            console.log('prueba');
            cargarselect2(data.data, selectIDu, campos, 2);
            $("#" + selectIDu).append('<option value="0">Todos</option>');
            $('#animationload').hide();
        })
        .catch(function (error) {
            $('#animationload').hide();
            if (error.hasOwnProperty('response') && Object.keys(error.response).length > 0) {
                $(this).showError('Error en petición', 'Estado del error: ' + error.response.status + '. Mensaje: ' + error.response.data.error);
            }
            //toastr.error(data.message, 'Error al Modificar ');
        });


}

function usuario2() {
    selectIDu = 'selUsuario2'
    var depe = $("#dependenciaExpO").val();
    var activo = $("#dependenciaExpO").val() ? '1' : '0';
    var tpUs = $("#CHKselUsuario").is(':checked') ? '1' : '0';
    $('#animationload').show();
    //console.log('fn=usuarios&depe='+depe+'&tpus='+tpUs);
    axios({
        method: 'post',
        baseURL: '../core/rest-est.php',
        data: 'fn=usuarios&depe=' + depe + '&tpus=' + tpUs
    })
        .then(function (response) {
            // console.log(response);
            data = response.data;
            //  console.log(data);
            $("#" + selectIDu).empty();
            $("#" + selectIDu).append('<option value="0">-- Selecione --</option>');
            campos = new Array();
            campos['codigo'] = 'USUA_DOC';
            campos['nombre'] = 'NOMB';
            console.log('prueba');
            cargarselect2(data.data, selectIDu, campos, 2);
            $('#animationload').hide();
        })
        .catch(function (error) {
            $('#animationload').hide();
            if (error.hasOwnProperty('response') && Object.keys(error.response).length > 0) {
                $(this).showError('Error en petición', 'Estado del error: ' + error.response.status + '. Mensaje: ' + error.response.data.error);
            }
            //toastr.error(data.message, 'Error al Modificar ');
        });


}

function cargarselect(vector, campo, campos, tipo = 1) {
    //  $("#" + campo).empty();
    //  $("#" + campo).append('<option value="0">-- Selecione --</option>');
    //  console.log(vector, campo, campos,tipo);
    $.each(vector, function (id, value) {
        if (tipo == 1)
            $("#" + campo).append('<option  value="' + value[campos['codigo']] + '" data-id="' + value[campos['id']] + '">' + value[campos['codigo']] + ' - ' + value[campos['nombre']] + '</option>');
        else
            $("#" + campo).append('<option  value="' + value[campos['codigo']] + '" data-id="' + value[campos['id']] + '">' + value[campos['nombre']] + '</option>');
    });
    // console.log('acabo');.

}

function cargarselect2(vector, campo, campos, tipo = 1) {
    //  $("#" + campo).empty();
    //  $("#" + campo).append('<option value="0">-- Selecione --</option>');
    //  console.log(vector, campo, campos,tipo);
    $.each(vector, function (id, value) {
        if (tipo == 1)
            $("#" + campo).append('<option  value="' + value[campos['codigo']] + '">' + value[campos['codigo']] + ' - ' + value[campos['nombre']] + '</option>');
        else
            $("#" + campo).append('<option  value="' + value[campos['codigo']] + '">' + value[campos['nombre']] + '</option>');
    });

    if (campo == 'herr_respSegUsuaDoc') {
        //$('#herr_respSegUsuaDoc').selectpicker('refresh');
    }
    // console.log('acabo');

}

document.addEventListener('DOMContentLoaded', function () {

    const btnCrearExp = document.querySelector('.btn-crearExp');
    const btnCancelar = document.getElementById('btnRadcancelartx');

    btnCrearExp.addEventListener('click', function () {

        const creaExpstatus = validaExpJQ(); // se mantiene tal cual

        if (!creaExpstatus) return;

        save_method = 'Confirmar';

        const btnRcera = document.getElementById('btnRcera');
        if (btnRcera) document.getElementById('btnRcera').style.display = 'none';

        const btnRadcancelartx = document.getElementById('btnRadcancelartx');
        if (btnRadcancelartx) document.getElementById('btnRadcancelartx').style.display = 'block';

        const dataformCrearExp = document.getElementById('dataformCrearExp');
        if (dataformCrearExp) document.getElementById('dataformCrearExp').style.display = 'none';

        const btnRadcancelartxslir = document.getElementById('btnRadcancelartxslir');
        if (btnRadcancelartxslir) document.getElementById('btnRadcancelartxslir').style.display = 'none';

        const switchdato = document.getElementById('switchdato');
        if (switchdato) document.getElementById('switchdato').style.display = 'none';

        const trextasunto = document.getElementById('trextasunto');
        if (trextasunto) document.getElementById('tr-extasunto').style.display = 'none';

        const trextobservacion = document.getElementById('trextobservacion');
        if (trextobservacion) document.getElementById('tr-extobservacion').style.display = 'none';

        const trextentidad = document.getElementById('trextentidad');
        if (trextentidad) document.getElementById('tr-extEntidad').style.display = 'none';

        const checkedSwitch = document.getElementById('switch');
        if (checkedSwitch) {
            if (document.getElementById('switch').checked) {
                document.getElementById('tr-extasunto').style.display = '';
                document.getElementById('tr-extobservacion').style.display = '';
                document.getElementById('tr-extEntidad').style.display = '';
            }
        }

        document.getElementById('btnConfCrea').style.display = 'block';
        document.getElementById('confCrea').style.display = 'block';
        document.getElementById('creacionconfi').style.display = 'none';

        const sbrd = document.getElementById('selSubSerie').value;
        const anoExp = document.getElementById('anoExp').value;
        const depExp = document.getElementById('dependenciaExp').value;
        const codserie = document.getElementById('selSerie').value;

        axios({
            method: 'post',
            baseURL: '../expediente/exp-rest.php',
            data: `fn=crearConfirmar&depExp=${depExp}&serie=${codserie}&subserie=${sbrd}&anoExp=${anoExp}`
        })
            .then(function (response) {
                console.log(response?.data);

                const data = response.data.data;

                document.getElementById('titleexp').innerHTML = data.numexp;
                document.getElementById('txt-extasunto').innerHTML = document.getElementById('extasunto') ? document.getElementById('extasunto').value : '';
                document.getElementById('txt-extobservacion').innerHTML = document.getElementById('extobservacion') ? document.getElementById('extobservacion').value : '';
                document.getElementById('txt-extEntidad').innerHTML = document.getElementById('extEntidad') ? document.getElementById('extEntidad').value : '';
                document.getElementById('txt-titulo').innerHTML = document.getElementById('exptilulo') ? document.getElementById('exptilulo').value : '';
                document.getElementById('txt-seguridad').innerHTML = document.querySelector('select[name="idseguridad"] option:checked').textContent;
                document.getElementById('txt-serie').innerHTML = document.querySelector('select[name="selSerie"] option:checked').textContent;
                document.getElementById('txt-subserie').innerHTML = document.querySelector('select[name="selSubSerie"] option:checked').textContent;
                document.getElementById('txt-fechini').innerHTML = document.getElementById('fechaExp').value;
                document.getElementById('txt-resp').innerHTML = document.querySelector('select[name="selUsuario"] option:checked').textContent;

                const animationload = document.getElementById('animationload');
                if (animationload) {
                    document.getElementById('animationload').style.display = 'none';
                }
            })
            .catch(function (error) {
                console.log(error);
                const animationload = document.getElementById('animationload');
                if (animationload) {
                    document.getElementById('animationload').style.display = 'none';
                }

                if (error?.response) {
                    showError(
                        'Error en petición',
                        `Estado del error: ${error.response.status}. Mensaje: ${error.response.data.error}`
                    );
                }
            });
    });

    btnCancelar.addEventListener('click', function () {

        save_method = 'Confirmar';

        document.getElementById('switchdato').style.display = '';
        document.getElementById('switch').checked = false;
        document.getElementById('btnRadcancelartxslir').style.display = 'none';

        document.getElementById('btnRcera').style.display = '';
        document.getElementById('btnRadcancelartx').style.display = '';
        document.getElementById('dataformCrearExp').style.display = '';
        document.getElementById('btnConfCrea').style.display = 'none';
        document.getElementById('confCrea').style.display = 'none';
        document.getElementById('creacionconfi').style.display = 'none';

        document.getElementById('selSerie').value = 0;
        document.getElementById('selSubSerie').value = 0;
        document.getElementById('exptilulo').value = '';
        document.getElementById('selUsuario').value = 0;
        document.getElementById('extasunto').value = '';
        document.getElementById('extobservacion').value = '';
        document.getElementById('extEntidad').value = 0;

        tpexpcrea();
    });
});

function cancelarCrearExp() { }

function validaExpJQ() {
    let ok = true;

    // Limpiar mensajes de error
    if (document.getElementById('error-codserie'))
        document.getElementById('error-codserie').innerHTML = '';

    if (document.getElementById('error-sbrd'))
        document.getElementById('error-sbrd').innerHTML = '';

    if (document.getElementById('error-exptilulo'))
        document.getElementById('error-exptilulo').innerHTML = '';

    if (document.getElementById('error-usuaDocExp'))
        document.getElementById('error-usuaDocExp').innerHTML = '';

    if (document.getElementById('error-crearexp'))
        document.getElementById('error-crearexp').innerHTML = '';

    // Inputs
    const dependenciaExp = document.getElementById('dependenciaExp');
    const selSerie = document.getElementById('selSerie');
    const selSubSerie = document.getElementById('selSubSerie');
    const selUsuario = document.getElementById('selUsuario');
    const exptilulo = document.getElementById('exptilulo');

    // Quitar clases de error
    dependenciaExp.classList.remove('alert-danger');
    selSerie.classList.remove('alert-danger');
    selSubSerie.classList.remove('alert-danger');
    selUsuario.classList.remove('alert-danger');
    exptilulo.classList.remove('alert-danger');

    // Validaciones
    if (dependenciaExp.value == 0) {
        dependenciaExp.classList.add('alert-danger');
        ok = false;
    }

    if (selSerie.value == 0) {
        selSerie.classList.add('alert-danger');
        ok = false;
    }

    if (selSubSerie.value === '0' || !selSubSerie.value) {
        selSubSerie.classList.add('alert-danger');
        ok = false;
    }

    if (selUsuario.value === '0') {
        selUsuario.classList.add('alert-danger');
        ok = false;
    }

    if (exptilulo.value.trim() === '') {
        exptilulo.classList.add('alert-danger');
        ok = false;
    }

    if (!ok) {
        if (document.getElementById('error-crearexp')) {
            document.getElementById('error-crearexp').innerHTML = ' Diligenciar los campos pendientes ';
        }
    }

    return ok;
}

function crearEA() {
    $('#btnRadconftx').hide(); //change button text
    $('#btnRadcancelartx').hide();//attr('disabled', true); //set button disable 
    $('#dataformCrearExp').hide();
    $('#btnConfCrea').hide();
    $('#confCrea').hide();
    $('#creacionconfi').show();
    $('#btnRadcancelartxslir').show();

    var btnExp = '';

    sbrd = $('#selSubSerie').val();
    anoExp = $('#anoExp').val();
    depExp = $('#dependenciaExp').val();
    codserie = $('#selSerie').val();
    codserie = $('#selSerie').val();
    fechaExp = $('#fechaExp').val();
    respo = $('#selUsuario').val();
    seguridad = $('#idseguridad').val();
    dt1 = $('#exptilulo').val();
    dt2 = $('#param2').val() ? $('#param2').val() : '';
    dt3 = $('#param3').val() ? $('#param3').val() : '';
    dt4 = $('#param4').val() ? $('#param4').val() : '';
    dt5 = $('#param5').val() ? $('#param5').val() : '';

    axios({
        method: 'post',
        baseURL: '../expediente/exp-rest.php',
        data: 'fn=crear&depExp=' + depExp + '&serie=' + codserie + '&subserie=' + sbrd + '&anoExp=' + anoExp + "&fechaExp=" + fechaExp + "&respo=" + respo + "&seguridad=" + seguridad + "&dt1=" + dt1 + "&dt2=" + dt2 + "&dt3=" + dt3 + "&dt4=" + dt4 + "&dt5=" + dt5
    })
        .then(function (response) {
            console.log('5');
            // console.log(response);
            data = response.data;
            //  console.log(data);
            $('#titleexp').html(data.numexp);
            respotxt = $("#usuaDocExp option:selected").text();
            btnExp = '<a class="btn btn-xs btn-primary float-right" data-toggle2="tooltip" data-original-title="ver Expedientes "  alt="ver Expediente" href="../expediente/verExp.php?exp=' + data.data.numexp + '" ><i class="fa fa-folder" aria-hidden="true"></i></a >';
            $('#creacionconfi').html('<div class="alert alert-success"> Expediente ' + data.data.numexp + ' Creado ' + btnExp + '(responsable: ' + respotxt + ' ) (titulo:' + dt1 + ' )</div>');
            $('#animationload').hide();
        })
        .catch(function (error) {
            $('#animationload').hide();
            if (error.hasOwnProperty('response') && Object.keys(error.response).length > 0) {
                $(this).showError('Error en petición', 'Estado del error: ' + error.response.status + '. Mensaje: ' + error.response.data.error);
            }
            //toastr.error(data.message, 'Error al Modificar ');
        });

    filtrarexp(0);
}

function SeguridadList() {
    //  $('#btnSearchTH').hide();
    $('#Historico').hide();
    // ajax adding data to database
    $('#imageprocesoSeg').show();
    var Exp = $('#numexp').val();
    $('#tb_listaSegExp tbody').empty();
    var ante = '';
    axios({
        method: 'post', baseURL: '../expediente/exp-rest.php', data: 'fn=listaSeguridadExp&exp=' + Exp
    })
        .then(function (response) {
            console.log('6');
            // console.log(response);
            data = response.data;
            //   console.log(data);
            $.each(data.dtexp, function (index, value) {
                nombusuari = 'Todos';
                if (value['USUA_NOMB']) {
                    nombusuari = value['USUA_NOMB'];
                }
                $segSel = '';
                $segSel1 = '';
                $segSel2 = '';
                $segSel3 = '';
                if (value['AEXP_ACL'] == 1) {
                    $segSel1 = " selected='selected' ";
                } else if (value['AEXP_ACL'] == 2) {
                    $segSel2 = " selected='selected' ";
                } else if (value['AEXP_ACL'] == 3) {
                    $segSel3 = " selected='selected' ";
                } else {
                    $segSel = " selected='selected' ";
                }
                $segSelA = 'Denegar';
                switch (value['AEXP_ACL']) {
                    case '1':
                        $segSelA = 'listar';
                        break;
                    case '2':
                        $segSelA = 'Listar y Ver Documentos';
                        break;
                    case '3':
                        $segSelA = 'Administrar';
                        break;
                    default:
                        $segSelA = 'Denegar';
                        break;
                }
                var segSelimg = 'Ver';



                seguridadtp = " <select class='custom-select' id=\"sele" + value['ID_AEXP'] + "\" name=\"sele" + value['ID_AEXP'] + "\" onchange='modSeg(" + value['ID_AEXP'] + ",\"sele" + value['ID_AEXP'] + "\")' id='seltiposeg' name='seltiposeg'><option value='0' " + $segSel + "> Denegar </option><option value='1' " + $segSel1 + "> ver listado </option><option value='2' " + $segSel2 + "> Ver Contenido (Ver Documentos)</option><option value='3' " + $segSel3 + "> Full Permisos</option></select>";
                //btneconte = '<a class="btn btn-xs btn-warning float-right"  href="#" onclick="modpermisosSeg(\'' + value['ID_AEXP'] + '\',\''+value['DEPE_CODI'] + '\',\''+value['USUA_CODI'] + '\',\''+value['AEXP_ACL'] +'\');" ><i class="fa fa-pencil" aria-hidden="true"></i></a >';
                $('#tb_listaSegExp').append('<tr  style="font-size:11px"><td style="vertical-align: middle;">' + nombusuari + '</td><td style="vertical-align: middle;">' + value['DEPE_NOMB'] + '</td><td >' + seguridadtp + '</td><!--<td style="vertical-align: middle;">' + segSelimg + '</td>--><td><span id="divsele' + value['ID_AEXP'] + '" name="sele' + value['ID_AEXP'] + '"></div></td></tr>');
            });
            $('#imageprocesoSeg').hide();
            //  $('#btnSearchTbH').show();
            $('#tb_listaSegExp').show();
            // Cargar lista de usuarios automáticamente
            usuario4();

        })
        .catch(function (error) {
            $('#animationload').hide();
            if (error.hasOwnProperty('response') && Object.keys(error.response).length > 0) {
                $(this).showError('Error en petición', 'Estado del error: ' + error.response.status + '. Mensaje: ' + error.response.data.error);
            }
            //toastr.error(data.message, 'Error al Modificar ');
        });

}

function saveSeg() {
    $('#imageprocesoSeg').show();
    depe = $('#dependenciaExp3').val();
    //  usuariosearch();
    usuario = $('#selUsuario').val(); //funcion de usuario
    tpseg = $('#seltiposeg').val();
    var Exp = $('#numexp').val();
    /* if (usuario == '0') {
         $('#diverrorsave').html('<i class="fa fa-close" style="color: red";font-size: 35px;></i> Se debe selecionar usuario ');
         return false;
     }
 */
    if (usuario == '999999') {
        usuario = 0;
    }
    axios({
        method: 'post',
        baseURL: '../expediente/exp-rest.php',
        data: 'fn=saveSeguridadexp&depe=' + depe + '&tpseg=' + tpseg + '&usuario=' + usuario + '&exp=' + Exp
    })
        .then(function (response) {
            console.log('7');
            // console.log(response);
            data = response.data;
            //  console.log(data);
            codline = data.codigo;
            nombusuari = $('select[name="selUsuario"] option:selected').text();
            if (usuario == 0) {
                nombusuari = 'Todos';
            }
            nombrDepe = $('select[name="dependenciaExp3"] option:selected').text();
            if (data.estadoOper == 'new') {
                /*nombusuari = $('select[name="selusers"] option:selected').text();
                 if (usuario == 0) {
                 nombusuari = 'Todos';
                 }
                 nombrDepe = $('select[name="seldep"] option:selected').text();*/
                $segSel = '';
                $segSel1 = '';
                $segSel2 = '';
                $segSel3 = '';
                if (tpseg == 1) {
                    $segSel1 = " selected='selected' ";
                } else if (tpseg == 2) {
                    $segSel2 = " selected='selected' ";
                } else if (tpseg == 3) {
                    $segSel3 = " selected='selected' ";
                } else {
                    $segSel = " selected='selected' ";
                }
                seguridadtp = " <select class='custom-select' id=\"sele" + codline + "\" name=\"sele" + codline + "\" onchange='modSeg(" + codline + ",\"sele" + codline + "\")' id='seltiposeg' name='seltiposeg'><option value='0' " + $segSel + "> Denegar </option><option value='1' " + $segSel1 + "> ver listado </option><option value='2' " + $segSel2 + "> Ver Contenido (Ver Documentos)</option><option value='3' " + $segSel3 + "> Full Permisos</option></select>";
                //btneconte = '<a class="btn btn-xs btn-warning float-right"  href="#" onclick="modpermisosSeg(\'' + value['ID_AEXP'] + '\',\''+value['DEPE_CODI'] + '\',\''+value['USUA_CODI'] + '\',\''+value['AEXP_ACL'] +'\');" ><i class="fa fa-pencil" aria-hidden="true"></i></a >';
                $('#tb_listaSegExp').prepend('<tr  style="font-size:11px"><td style="vertical-align: middle;">' + nombusuari + '</td><td style="vertical-align: middle;">' + nombrDepe + '</td><td >' + seguridadtp + '</td><td><span id="divsele' + codline + '" name="sele' + codline + '"><i class="fa fa-check" style="color: green;font-size: 35px;"></i></div></td></tr>');
                //$('#div' + codline).html('<i class="fa fa-check" style="color: green;font-size: 35px;"></i>');
                $('#diverrorsave').html('Acción realizada');
            } else {
                $('#diverrorsave').html('Acción No realizada Debido que ya esta registrado la combinacion (' + nombrDepe + ' con ' + nombusuari + ') y se debe modificar el permiso.');
            }

            $('#imageprocesoSeg').hide();



            $('#titleexp').html(data.data.numexp);

            btnExp = '<a class="btn btn-xs btn-primary float-right" data-toggle2="tooltip" data-original-title="ver Expedientes "  alt="ver Expediente" href="../expediente/verExp.php?exp=' + data.data.numexp + '" ><i class="fa fa-folder" aria-hidden="true"></i></a >';
            $('#creacionconfi').html('<div class="alert alert-success"> Expediente ' + data.numexp + ' Creado ' + btnExp + '</div>');
            $('#animationload').hide();
        })
        .catch(function (error) {
            $('#animationload').hide();
            if (error.hasOwnProperty('response') && Object.keys(error.response).length > 0) {
                $(this).showError('Error en petición', 'Estado del error: ' + error.response.status + '. Mensaje: ' + error.response.data.error);
            }
            //toastr.error(data.message, 'Error al Modificar ');
        });



}

function modSeg(idS, item) {
    valor = $('#' + item).val();
    axios({
        method: 'post',
        baseURL: '../expediente/exp-rest.php',
        data: 'fn=modSeguridadexp&aclid=' + idS + '&tpseg=' + valor
    })
        .then(function (response) {
            console.log('8');
            // console.log(response);
            data = response.data;
            //  console.log(data);
            if (value['USUA_NOMB']) {
                nombusuari = value['USUA_NOMB'];
            }
            $segSel = '';
            $segSel1 = '';
            $segSel2 = '';
            $segSel3 = '';
            if (value['AEXP_ACL'] == 1) {
                $segSel1 = " selected='selected' ";
            } else if (value['AEXP_ACL'] == 2) {
                $segSel2 = " selected='selected' ";
            } else if (value['AEXP_ACL'] == 3) {
                $segSel3 = " selected='selected' ";
            } else {
                $segSel = " selected='selected' ";
            }
            seguridadtp = " <select class='custom-select' id=\"sele" + value['ID_AEXP'] + "\" name=\"sele" + value['ID_AEXP'] + "\" onchange='modSeg(" + value['ID_AEXP'] + ",\"sele" + value['ID_AEXP'] + "\")' id='seltiposeg' name='seltiposeg'><option value='0' " + $segSel + "> Denegar </option><option value='1' " + $segSel1 + "> ver listado </option><option value='2' " + $segSel2 + "> Ver Contenido (Ver Documentos)</option><option value='3' " + $segSel3 + "> Full Permisos</option></select>";
            //btneconte = '<a class="btn btn-xs btn-warning float-right"  href="#" onclick="modpermisosSeg(\'' + value['ID_AEXP'] + '\',\''+value['DEPE_CODI'] + '\',\''+value['USUA_CODI'] + '\',\''+value['AEXP_ACL'] +'\');" ><i class="fa fa-pencil" aria-hidden="true"></i></a >';
            $('#tb_listaSegExp').append('<tr  style="font-size:11px"><td style="vertical-align: middle;">' + nombusuari + '</td><td style="vertical-align: middle;">' + value['DEPE_NOMB'] + '</td><td >' + seguridadtp + '</td><td><span id="divsele' + value['ID_AEXP'] + '" name="sele' + value['ID_AEXP'] + '"></div></td></tr>');
            $('#div' + item).html('<i class="fa fa-check" style="color: green;font-size: 35px;"></i>');
        })
        .catch(function (error) {
            $('#div' + item).html('<i class="fa fa-close" style="color: red";font-size: 35px;></i>');
            $('#animationload').hide();
            if (error.hasOwnProperty('response') && Object.keys(error.response).length > 0) {
                $(this).showError('Error en petición', 'Estado del error: ' + error.response.status + '. Mensaje: ' + error.response.data.error);
            }
            //toastr.error(data.message, 'Error al Modificar ');
        });

}

$(function () {
    $('.btn-o-limpia').click(function () {
        $('#bsq_usuaDoc').val(0);
        $('#bsq_nume_expe').val('');
        $('#bsq_nume_radi').val('');
        $('#bsq_nomexpe').val('');
        $('#bsq_dep').val(0);
    });
    $('.btn-o-limpia-herr').click(function () {
        $('#herr_usuaDoc').val(0);
        $('#herr_nume_expe').val('');
        $('#herr_nume_radi').val('');
        $('#herr_nomexpe').val('');
        $('#herr_dep').val(0);
    });
    $('.btn-o-limpia2').click(function () {
        $('#bsq_nume_expe').val('');
        $('#bsq_nomexpe').val('');
    });
    $('#btn-addv1expediente').click(function () {
        $('#AddCrearExpModal').show();
    });

    $('#seleccionar_todos_seguridad').on('click', function (e) {
        $('#herr_respSegUsuaDoc option').prop('selected', true);
    });
    $('#cancelar_seleccion_seguridad').on('click', function (e) {
        $('#herr_respSegUsuaDoc option').prop('selected', false);
    });


    $('.btn-o-bsq').click(function () {
        numExp = $('#bsq_nume_expe').val();
        radicado = $('#bsq_nume_radi').val();
        parametro = $('#bsq_nomexpe').val();
        depe = $('#bsq_dep').val();
        usuar = $('#bsq_usuaDoc').val();
        //   parent.$('#processing-modal').modal('show');
        $('#resulEstdatos2').show();
        axios({
            method: 'post',
            baseURL: '../expediente/exp-rest.php',
            data: 'fn=bsqexp&numExp=' + numExp + '&radicado=' + radicado + '&parametro=' + parametro + '&depe=' + depe + '&usuar=' + usuar
        })
            .then(function (response) {
                console.log('9');
                //  console.log(response);
                datos = response.data;
                // console.log(data);
                $("#tb_bsq_listaexp tbody").empty();
                parent.$('#processing-modal').modal('hide');
                $.each(datos.data, function (index, value) {
                    // console.log(value['NUM']);
                    codigo = value['NUM'];
                    fech = value['FECH'];
                    responsable = value['RESPONSABLE'];
                    depe = value['DEPE'];
                    creador = value['CREADOR'];
                    titulo = value['TITULO'];
                    //  estado2 = value['ESTADO'];
                    param2 = value['PARAM2'];
                    param3 = value['PARAM3'];
                    param4 = value['PARAM4'];
                    param5 = value['PARAM5'];
                    //estado2 = value['ESTADO']==1?'Anulado':value['ESTADO']==2?'Cerrado':'Abierto';
                    switch (value['ESTADO']) {
                        case '1':
                        case '3':
                            estado2 = 'Cerrado';
                            break;
                        case '2':
                            estado2 = 'Anulado';
                            break;
                        default:
                            estado2 = 'Abierto';
                            break;
                    }
                    btnbsq = '';
                    //   console.log('<tr> <td class = "text-right" > ' +' </td><td class = "text-right" > ' + codigo + ' </td><td >' + fech +'</td><td class = "text-right">' + titulo +'</td><td class = "text-right">' + responsable +'</td><td class="text-center" >' + creador +'</td><td class="text-center" >' + estado2 +'</td></tr>');
                    btnA = '<button class="btn btn-xs btn-success btn-expediente" type="button" data-toggle="tooltip"  data-placement="top" data-exp="' + codigo + '" title="Ver detalles" ><i class="fa fa-folder-o"></i></button> ';
                    if (param2)
                        btnbsq += '<button class="btn btn-xs btn-primary float-right " type="button" data-toggle="tooltip"  data-placement="top" data-original-title="' + param2 + '"  ><i class="fa fa-info"></i></button> ';
                    if (param3)
                        btnbsq += '<button class="btn btn-xs btn-primary float-right " type="button" data-toggle="tooltip"  data-placement="top" data-original-title="' + param3 + '"  ><i class="fa fa-info"></i></button> ';
                    if (param4)
                        btnbsq += '<button class="btn btn-xs btn-primary float-right " type="button" data-toggle="tooltip"  data-placement="top" data-original-title="' + param4 + '"  ><i class="fa fa-info"></i></button> ';
                    if (param5)
                        btnbsq += '<button class="btn btn-xs btn-primary float-right " type="button" data-toggle="tooltip"  data-placement="top" data-original-title="' + param5 + '"  ><i class="fa fa-info"></i></button> ';

                    //btnToS = '<button class="btn btn-xs btn-success btn-rp1detXLS" data-rep="3" data-toggle="modal" data-target="DetEsta"  data-tit="' + Medio +'" data-btns="'+codigo+'" '+datosbtnextra+' data-id='+codigo+'  data-btns=2 type="button" ><i class="fa fa-table" data-toggle="tooltip"  data-placement="top"  title="Descargar detalles en excel"></i></button> <div id="detXLST" class="float-right"></div>';
                    $('#tb_bsq_listaexp').append('<tr> <td class = "text-left" > ' + btnA + ' </td><td class = "text-center" > ' + codigo + ' </td><td class = "text-center">' + fech + '</td><td class = "text-left">' + titulo + btnbsq + '</td><td class = "text-right">' + responsable + '</td><td class="text-center" >' + creador + '</td><td class="text-center" >' + estado2 + '</td></tr>');
                });
                setTimeout(function () {
                    parent.$('#processing-modal').modal('hide');
                }, 2000);
                $(function () { $('[data-toggle="tooltip"]').tooltip() })

            })
            .catch(function (error) {
                parent.$('#processing-modal').modal('hide');
                $('#processing-modal').modal('hide');
                //    if (error.hasOwnProperty('response') && Object.keys(error.response).length > 0) {
                //        $(this).showError('Error en petición', 'Estado del error: ' + data.response.status + '. Mensaje: ' + data.response.data.error);
                //     }
                //toastr.error(data.message, 'Error al Modificar ');
            });


    });

    $('#check_uncheck').on('click', function (e) {
        var old = $(this).attr('data-status');
        var status = old == 1 ? 0 : 1;
        $(this).attr('data-status', status);

        if (status == 1) {
            $(this).find('.fa-check-square-o').show();
            $(this).find('.fa-square-o').hide();
            $('input[name="expediente[]"]').prop('checked', true);
        } else {
            $(this).find('.fa-check-square-o').hide();
            $(this).find('.fa-square-o').show();
            $('input[name="expediente[]"]').prop('checked', false);
        }
    });

    $('.herr-o-bsq').click(function () {
        numExp = $('#herr_nume_expe').val();
        radicado = $('#herr_nume_radi').val();
        parametro = $('#herr_nomexpe').val();
        depe = $('#herr_dep').val();
        usuar = $('#herr_usuaDoc').val();
        //   parent.$('#processing-modal').modal('show');
        $('#resulEstdatos2').show();
        axios({
            method: 'post',
            baseURL: '../expediente/exp-rest.php',
            data: 'fn=bsqexp&numExp=' + numExp + '&radicado=' + radicado + '&parametro=' + parametro + '&depe=' + depe + '&usuar=' + usuar
        })
            .then(function (response) {
                console.log('9');
                //  console.log(response);
                datos = response.data;
                // console.log(data);
                $("#tb_bsq_listaexp tbody").empty();
                parent.$('#processing-modal').modal('hide');
                $.each(datos.data, function (index, value) {
                    // console.log(value['NUM']);
                    codigo = value['NUM'];
                    fech = value['FECH'];
                    responsable = value['RESPONSABLE'];
                    depe = value['DEPE'];
                    creador = value['CREADOR'];
                    titulo = value['TITULO'];
                    //  estado2 = value['ESTADO'];
                    param2 = value['PARAM2'];
                    param3 = value['PARAM3'];
                    param4 = value['PARAM4'];
                    param5 = value['PARAM5'];
                    estado2 = value['ESTADO'] == 1 ? 'Anulado' : value['ESTADO'] == 2 ? 'Cerrado' : 'Abierto';
                    btnbsq = '';
                    //   console.log('<tr> <td class = "text-right" > ' +' </td><td class = "text-right" > ' + codigo + ' </td><td >' + fech +'</td><td class = "text-right">' + titulo +'</td><td class = "text-right">' + responsable +'</td><td class="text-center" >' + creador +'</td><td class="text-center" >' + estado2 +'</td></tr>');
                    btnA = '<button class="btn btn-xs btn-success btn-expediente" type="button" data-toggle="tooltip"  data-placement="top" data-exp="' + codigo + '" title="Ver detalles" ><i class="fa fa-folder-o"></i></button> ';
                    if (param2)
                        btnbsq += '<button class="btn btn-xs btn-primary float-right " type="button" data-toggle="tooltip"  data-placement="top" data-original-title="' + param2 + '"  ><i class="fa fa-info"></i></button> ';
                    if (param3)
                        btnbsq += '<button class="btn btn-xs btn-primary float-right " type="button" data-toggle="tooltip"  data-placement="top" data-original-title="' + param3 + '"  ><i class="fa fa-info"></i></button> ';
                    if (param4)
                        btnbsq += '<button class="btn btn-xs btn-primary float-right " type="button" data-toggle="tooltip"  data-placement="top" data-original-title="' + param4 + '"  ><i class="fa fa-info"></i></button> ';
                    if (param5)
                        btnbsq += '<button class="btn btn-xs btn-primary float-right " type="button" data-toggle="tooltip"  data-placement="top" data-original-title="' + param5 + '"  ><i class="fa fa-info"></i></button> ';

                    //btnToS = '<button class="btn btn-xs btn-success btn-rp1detXLS" data-rep="3" data-toggle="modal" data-target="DetEsta"  data-tit="' + Medio +'" data-btns="'+codigo+'" '+datosbtnextra+' data-id='+codigo+'  data-btns=2 type="button" ><i class="fa fa-table" data-toggle="tooltip"  data-placement="top"  title="Descargar detalles en excel"></i></button> <div id="detXLST" class="float-right"></div>';
                    $('#tb_bsq_listaexp').append('<tr> <td class = "text-left" > ' + btnA + ' </td><td class = "text-center" > ' + codigo + ' </td><td class = "text-center">' + fech + '</td><td class = "text-left">' + titulo + btnbsq + '</td><td class = "text-right">' + responsable + '</td><td class="text-center" >' + creador + '</td><td class="text-center" >' + estado2 + '</td></tr>');
                });
                setTimeout(function () {
                    parent.$('#processing-modal').modal('hide');
                }, 2000);
                $(function () { $('[data-toggle="tooltip"]').tooltip() })

            })
            .catch(function (error) {
                parent.$('#processing-modal').modal('hide');
                $('#processing-modal').modal('hide');
                //    if (error.hasOwnProperty('response') && Object.keys(error.response).length > 0) {
                //        $(this).showError('Error en petición', 'Estado del error: ' + data.response.status + '. Mensaje: ' + data.response.data.error);
                //     }
                //toastr.error(data.message, 'Error al Modificar ');
            });
    });

    $('.btn-o-bsqV1').click(function () {
        numExp = $('#bsq_nume_expe2').val();
        parametro = $('#bsq_nomexpe2').val();
        //   parent.$('#processing-modal').modal('show');
        $('#resulEstdatos2').show();
        axios({
            method: 'post',
            baseURL: '../expediente/exp-rest.php',
            data: 'fn=bsqexpV1&numExp=' + numExp + '&parametro=' + parametro
        })
            .then(function (response) {
                console.log('10');
                //  console.log(response);
                datos = response.data;
                // console.log(data);
                $("#tb_bsq_listaexp tbody").empty();
                parent.$('#processing-modal').modal('hide');
                $.each(datos.data, function (index, value) {
                    // console.log(value['NUM']);
                    codigo = value['NUM'];
                    fech = value['FECH'];
                    responsable = value['RESPONSABLE'];
                    depe = value['DEPE'];
                    creador = value['CREADOR'];
                    titulo = value['TITULO'];
                    //  estado2 = value['ESTADO'];
                    param2 = value['PARAM2'];
                    param3 = value['PARAM3'];
                    param4 = value['PARAM4'];
                    param5 = value['PARAM5'];
                    estado2 = value['ESTADO'] == 1 ? 'Anulado' : value['ESTADO'] == 2 ? 'Cerrado' : 'Abierto';
                    btnbsq = '';
                    respoQAgo2 = value['respNew'];
                    //   console.log('<tr> <td class = "text-right" > ' +' </td><td class = "text-right" > ' + codigo + ' </td><td >' + fech +'</td><td class = "text-right">' + titulo +'</td><td class = "text-right">' + responsable +'</td><td class="text-center" >' + creador +'</td><td class="text-center" >' + estado2 +'</td></tr>');
                    btnA = '';
                    if (!respoQAgo2)
                        btnA = '<button type="button" class="btn btn-xs  btn-primary btn-bbaaold" onclick="addexpOld(' + "'" + codigo + "'" + ')" data-toggle="modal" data-id="' + codigo + '" data-target="#AddCrearExpModal"><i class="fa fa-plus"></i></button>';
                    /*'<button class="btn btn-xs btn-success btn-addv1expediente" id="btn-addv1expediente" type="button" data-toggle="tooltip"  data-placement="top" data-exp="' + codigo
                     + '" title="Asociar" data-toggle="modal" data-target="#AddCrearExpModal" ><i class="fa fa-plus"></i></button> ';*/
                    if (param2)
                        btnbsq += '<button class="btn btn-xs btn-primary float-right " type="button" data-toggle="tooltip"  data-placement="top" data-original-title="' + param2 + '"  ><i class="fa fa-info"></i></button> ';
                    if (param3)
                        btnbsq += '<button class="btn btn-xs btn-primary float-right " type="button" data-toggle="tooltip"  data-placement="top" data-original-title="' + param3 + '"  ><i class="fa fa-info"></i></button> ';
                    if (param4)
                        btnbsq += '<button class="btn btn-xs btn-primary float-right " type="button" data-toggle="tooltip"  data-placement="top" data-original-title="' + param4 + '"  ><i class="fa fa-info"></i></button> ';
                    if (param5)
                        btnbsq += '<button class="btn btn-xs btn-primary float-right " type="button" data-toggle="tooltip"  data-placement="top" data-original-title="' + param5 + '"  ><i class="fa fa-info"></i></button> ';

                    //btnToS = '<button class="btn btn-xs btn-success btn-rp1detXLS" data-rep="3" data-toggle="modal" data-target="DetEsta"  data-tit="' + Medio +'" data-btns="'+codigo+'" '+datosbtnextra+' data-id='+codigo+'  data-btns=2 type="button" ><i class="fa fa-table" data-toggle="tooltip"  data-placement="top"  title="Descargar detalles en excel"></i></button> <div id="detXLST" class="float-right"></div>';
                    $('#tb_bsq_listaexp').append('<tr> <td class = "text-left" > ' + btnA + ' </td><td class = "text-center" > ' + codigo + ' </td><td class = "text-center">' + fech + '</td><td class = "text-left">' + titulo + btnbsq + '</td><td class = "text-right">' + responsable + '</td><td class="text-center" >' + creador + '</td><td class="text-center" >' + estado2 + '</td><td class="text-center" >' + respoQAgo2 + '</td></tr>');
                });
                setTimeout(function () {
                    parent.$('#processing-modal').modal('hide');
                }, 2000);
                $(function () { $('[data-toggle="tooltip"]').tooltip() })

            })
            .catch(function (error) {
                parent.$('#processing-modal').modal('hide');
                $('#processing-modal').modal('hide');
                //    if (error.hasOwnProperty('response') && Object.keys(error.response).length > 0) {
                //        $(this).showError('Error en petición', 'Estado del error: ' + data.response.status + '. Mensaje: ' + data.response.data.error);
                //     }
                //toastr.error(data.message, 'Error al Modificar ');
            });


    })

});

function usuarioBSQ(formulario) {

    var depe = '';
    var activo = '';
    var campo_codigo = 'USUA_DOC';

    if (formulario == 'bsq') {
        selectIDu = 'bsq_usuaDoc';
        depe = $("#bsq_dep").val();
        activo = $("#bsq_dep").val() ? '1' : '0';
    } else if (formulario == 'herr') {
        selectIDu = 'herr_usuaDoc'
        depe = $("#herr_dep").val();
        activo = $("#herr_dep").val() ? '1' : '0';
    } else if (formulario == 'herr-resp') {
        selectIDu = 'herr_respUsuaDoc'
        depe = $("#herr_dep_resp").val();
        activo = $("#herr_dep_resp").val() ? '1' : '0';
    } else if (formulario == 'herr-resp-seg') {
        selectIDu = 'herr_respSegUsuaDoc'
        depe = $("#herr_dep_seg_resp").val();
        activo = $("#herr_dep_seg_resp").val() ? '1' : '0';
        campo_codigo = 'COD';
    }
    var tpUs = '1';
    $('#animationload').show();
    //console.log('fn=usuarios&depe='+depe+'&tpus='+tpUs);
    axios({
        method: 'post',
        baseURL: '../core/rest-est.php',
        data: 'fn=usuarios&depe=' + depe + '&tpus=' + tpUs
    })
        .then(function (response) {
            // console.log(response);
            data = response.data;
            //  console.log(data);
            $("#" + selectIDu).empty();
            if (selectIDu != 'herr_respUsuaDoc' && selectIDu != 'herr_respSegUsuaDoc') {
                $("#" + selectIDu).append('<option value="0">-- Selecione --</option>');
            }

            campos = new Array();
            campos['codigo'] = campo_codigo;
            campos['nombre'] = 'NOMB';
            console.log('prueba');
            cargarselect2(data.data, selectIDu, campos, 2);
            $('#animationload').hide();
        })
        .catch(function (error) {
            $('#animationload').hide();
            if (error.hasOwnProperty('response') && Object.keys(error.response).length > 0) {
                $(this).showError('Error en petición', 'Estado del error: ' + error.response.status + '. Mensaje: ' + error.response.data.error);
            }
            //toastr.error(data.message, 'Error al Modificar ');
        });
}

function addoption(optionfun) {
    var Exp = $('#numexp').val();
    $('#txaccAdm').html('');
    $('#opertitulo-error').html('');
    vard = Array();
    datasen = '';
    switch (optionfun) {
        case "SGD_SEXP_PAREXP1":
            vard['tp'] = 'param';
            vard['txt'] = 'txt-title';
            titulo = $('#SGD_SEXP_PAREXP1').val();
            tituloOld = $('#txt-title').text();
            if (titulo == tituloOld || !titulo) {
                $('#opertitulo-error').html('Debe colocar un tiltulo o titulo diferente');
                return false;
            }
            datasen = 'fn=modMeta&cambio=' + titulo + '&camp=SGD_SEXP_PAREXP1';
            break;
        case "SGD_SEXP_PAREXP2":
            vard['tp'] = 'param';
            vard['txt'] = 'txt-param2';
            titulo = $('#SGD_SEXP_PAREXP2').val();
            tituloOld = $('#txt-param2').text();
            if (titulo == tituloOld) {
                $('#opertitulo-error').html('Debe colocar un texto diferente');
                return false;
            }
            datasen = 'fn=modMeta&cambio=' + titulo + '&camp=SGD_SEXP_PAREXP2';
            break;
        case "SGD_SEXP_PAREXP3":
            vard['tp'] = 'param';
            vard['txt'] = 'txt-param3';
            titulo = $('#SGD_SEXP_PAREXP3').val();
            tituloOld = $('#txt-param3').text();
            if (titulo == tituloOld) {
                $('#opertitulo-error').html('Debe colocar un texto diferente');
                return false;
            }
            datasen = 'fn=modMeta&cambio=' + titulo + '&camp=SGD_SEXP_PAREXP3';
            break;
        case "SGD_SEXP_PAREXP4":
            vard['tp'] = 'param';
            vard['txt'] = 'txt-param4';
            titulo = $('#SGD_SEXP_PAREXP4').val();
            tituloOld = $('#txt-param4').text();
            if (titulo == tituloOld) {
                $('#opertitulo-error').html('Debe colocar un texto diferente');
                return false;
            }
            datasen = 'fn=modMeta&cambio=' + titulo + '&camp=SGD_SEXP_PAREXP4';
            break;
        case "SGD_SEXP_PAREXP5":
            vard['tp'] = 'param';
            vard['txt'] = 'txt-param5';
            titulo = $('#SGD_SEXP_PAREXP5').val();
            tituloOld = $('#txt-param5').text();
            if (titulo == tituloOld) {
                $('#opertitulo-error').html('Debe colocar un texto diferente');
                return false;
            }
            datasen = 'fn=modMeta&cambio=' + titulo + '&camp=SGD_SEXP_PAREXP5';
            break;
        case "responsable":
            vard['tp'] = 'responsable';
            vard['txt'] = 'txt-Responsa';
            respo = $('#usuaDocExp').val();
            respotxt = $("#usuaDocExp option:selected").text();
            depesa = $('#dependenciaResp').val();
            datasen = 'fn=modMeta&camp=USUA_DOC_RESPONSABLE&cambio=' + respo + '&depeexp' + depesa;
            //  console.log(respotxt);
            break;
        case "fisico":
            vard['tp'] = 'fisico';
            radicado = $('#numerpDoc').val();
            titulo = $('#operFisicoMasi').val();
            datasen = 'fn=modRad&cambio=' + titulo + '&camp=sgd_exp_ufisica&rad=' + radicado;
            break;
        case "carpeta":
            vard['tp'] = 'carpeta';
            radicado = $('#numerpDoc').val();
            titulo = $('#operCarpi').val();
            datasen = 'fn=modRad&cambio=' + titulo + '&camp=sgd_exp_carpeta&rad=' + radicado;
            break;
        case "subexp":
            vard['tp'] = 'subexp';
            radicado = $('#numerpDoc').val();
            titulo = $('#operaddsubei').val();
            datasen = 'fn=modRad&cambio=' + titulo + '&camp=sgd_exp_subexpediente&rad=' + radicado;
            break;

        /* case"Seguridad":
                     m_data.append('accion', 'cambiarSeguridad');
                     m_data.append('seg', $('#ExpSeguridad').val());
                     
                     respo = $('#ExpSeguridad').val();
                     datasen='fn=modMeta&cambio='+ titulo;
                     break;?*/
    }
    if (!datasen) return true;
    axios({
        method: 'post',
        baseURL: '../expediente/exp-rest.php',
        data: datasen + '&exp=' + Exp
    })
        .then(function (response) {
            console.log('11');
            // console.log(response);
            data = response.data;
            // console.log(data);
            // console.log(respotxt);
            switch (vard['tp']) {
                case "param":
                    // console.log(respotxt);
                    $('#txaccAdm').html('<div class="alert alert-success">Se asigno a la Nombre de expediente: ' + titulo + ' </div>');
                    // $('#txaccI').html('<div class="alert alert-success">Se asigno a la Nombre de expediente: ' + titulo + ' </div>');

                    $('#opertituloOld').val(titulo);
                    $('#' + vard['txt']).html(titulo);

                    break;
                case "responsable":
                    //   alert();
                    $('#txaccAdm').html('<div class="alert alert-success">Se cambio el responsable del expediente: ' + respotxt + ' </div>');
                    // $('#txaccI').html('<div class="alert alert-success">Se asigno a la Nombre de expediente: ' + titulo + ' </div>');
                    /// console.log(respotxt);
                    $('#opertituloOld').val(respotxt);
                    $('#' + vard['txt']).html(respotxt);
                    break;
                case "fisico":
                    //  $('#txaccAdm').html('<div class="alert alert-success">Se asigno a la Nombre de expediente: ' + titulo + ' </div>');
                    console.log('fisico');
                    $('#txaccI').html('<div class="alert alert-success">Se asigno a la Fisico de expediente: ' + titulo + ' </div>');

                    //   $('#opertituloOld').val(titulo);
                    $('#divFisico' + radicado).html(titulo);

                    break;
                case "carpeta":
                    //  $('#txaccAdm').html('<div class="alert alert-success">Se asigno a la Nombre de expediente: ' + titulo + ' </div>');
                    console.log('carpeta');
                    $('#txaccI').html('<div class="alert alert-success">Se asigno a la Carpeta de expediente: ' + titulo + ' </div>');

                    //   $('#opertituloOld').val(titulo);
                    $('#divCarpeta' + radicado).html(titulo);

                    break;
                case "subexp":
                    //  $('#txaccAdm').html('<div class="alert alert-success">Se asigno a la Nombre de expediente: ' + titulo + ' </div>');
                    console.log('subexp');
                    $('#txaccI').html('<div class="alert alert-success">Se asigno a la Subexpediente de expediente: ' + titulo + ' </div>');

                    //   $('#opertituloOld').val(titulo);
                    $('#divSubExp' + radicado).html(titulo);

                    break;
            };
            /* $("#" + selectIDu).empty();
             $("#" + selectIDu).append('<option value="0">-- Selecione --</option>');
             campos = new Array();
             campos['codigo'] = 'USUA_DOC';
             campos['nombre'] = 'NOMB';
             console.log('prueba');
             cargarselect2(data.data, selectIDu, campos, 2);
             $('#animationload').hide();*/
        })
        .catch(function (error) {
            $('#animationload').hide();
            if (error.hasOwnProperty('response') && Object.keys(error.response).length > 0) {
                $(this).showError('Error en petición', 'Estado del error: ' + error.response.status + '. Mensaje: ' + error.response.data.error);
            }
            //toastr.error(data.message, 'Error al Modificar ');
        });
    /*   m_data.append('exp', '<?= $expdata['SGD_EXP_NUMERO']; ?>');
               var url = "../core/?exp/dtexp";
               $.ajax({
               url: url,
                       type: "POST",
                       data: m_data,
                       dataType: "JSON",
                       processData: false,
                       contentType: false,
                       success: function (data)
                       {
                       switch (optionfun) {
                       case "titulo":
                               $('#txaccAdm').html('<div class="alert alert-success">Se asigno a la Nombre de expediente: ' + titulo + ' </div>');
                               $('#opertituloOld').val(titulo);
                               $('#txt-title').html(titulo);
                               
                        break;
                        case "Seguridad":
                           txtseg='PUBLICA';
                           if($('#ExpSeguridad').val()==1)
                               txtseg='PRIVADA';
                           $('#txaccAdm').html('<div class="alert alert-success">Se modifico la seguridad: ' + txtseg + ' </div>');
                               //$('#opertituloOld').val($('#ExpSeguridad').val());
                           $('#txt-seguridadExped').html(txtseg);
                               
                        break;
                      case"responsable":
                               if (data.dt == 'Ok') {
                       $('#txaccAdm').html('<div class="alert alert-success">Se módifico el  responsable del Expediente </div>');
                               $('#txt-Rsponsa').html($('select[name="usuaDocExp"] option:selected').text());
                       } else
                               $('#txaccAdm').html('<div class="alert alert-danger">No se realizo la acción</div>');
                               break;
                       }
   
                       },
                       error: function (jqXHR, textStatus, errorThrown)
                       {
                       $('#' + txacc).html('<div class="alert alert-danger">No se Realizo la transacción </div>');
                               //   $('#diverrorsave').html('<i class="fa fa-close" style="color: red";font-size: 35px;></i> No se realizo la acción');
                       }
               });*/
}

$(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $('#modalInfo').on('show.bs.modal', function (e) {
        var request = $.get('propiedades.php?expediente=' + $(this).data('expediente'));
        request.done(function (data) {
            $('#modalInfo #anexos_expediente').text(data['anexos_expediente']);
            $('#modalInfo #radicados_expediente').text(data['radicados_expediente']);
            $('#modalInfo #borradores_expediente').text(data['borradores_expediente']);
            $('#modalInfo #total_objetos').text(data['anexos_expediente'] + data['radicados_expediente'] + data['borradores_expediente']);
            $('#modalInfo #anexos_expediente_peso').text((data['anexos_expediente_peso'] / (1024 * 1024)).toFixed(2) + " Mb");
            $('#modalInfo #radicados_expediente_peso').text((data['radicados_expediente_peso'] / (1024 * 1024)).toFixed(2) + " Mb");
            $('#modalInfo #borradores_expediente_peso').text((data['borradores_expediente_peso'] / (1024 * 1024)).toFixed(2) + " Mb");
            $('#modalInfo #total_peso').text(((data['anexos_expediente_peso'] + data['radicados_expediente_peso'] + data['borradores_expediente_peso']) / (1024 * 1024)).toFixed(2) + " Mb");
        });
    });
});

$('#myTab a').on('click', function (event) {
    event.preventDefault()
    $(this).tab('show')
})

$(".modal-header-andje").on("click", '.btn-op-setting', function () {
    $('#SGD_SEXP_PAREXP1').val($('#txt-title').text());
    $('#SGD_SEXP_PAREXP2').val($('#txt-param2').text());
    $('#SGD_SEXP_PAREXP3').val($('#txt-param3').text());
    $('#SGD_SEXP_PAREXP4').val($('#txt-param4').text());
    $('#SGD_SEXP_PAREXP5').val($('#txt-param5').text());

});

function usuario3(depe) {
    selectIDu = 'usuaDocExp'
    usuados = $("#UCRESPONSABLE").val();
    ;    //var depe = $("#dependenciaExp").val();
    //var activo = $("#dependenciaExp").val() ? '1' : '0';
    // var tpUs = $("#CHKselUsuario").is(':checked') ? '1' : '0';
    //   $('#animationload').show();
    //console.log('fn=usuarios&depe='+depe+'&tpus='+tpUs);
    axios({
        method: 'post',
        baseURL: '../core/rest-est.php',
        data: 'fn=usuarios&depe=' + depe
    })
        .then(function (response) {
            // console.log(response);
            data = response.data;
            //  console.log(data);
            $("#" + selectIDu).empty();
            $("#" + selectIDu).append('<option value="0">-- Selecione --</option>');
            campos = new Array();
            campos['codigo'] = 'USUA_DOC';
            campos['nombre'] = 'NOMB';
            // console.log(usuados);

            cargarselect2(data.data, selectIDu, campos, 2);
            $("#" + selectIDu).val(usuados);
            $('#animationload').hide();
        })
        .catch(function (error) {
            $('#animationload').hide();
            if (error.hasOwnProperty('response') && Object.keys(error.response).length > 0) {
                $(this).showError('Error en petición', 'Estado del error: ' + error.response.status + '. Mensaje: ' + error.response.data.error);
            }
            //toastr.error(data.message, 'Error al Modificar ');
        });


}

function filtrobtn(xls) {
    if (xls == 0)
        listar($("#tpacc").val())
    else {
        $("#tb_listaexp").table2excel({
            exclude: ".noExl",
            name: "Excel Lista Expediente",
            filename: "ListaExpediente"

        });
    }
}

function uploadAnexos() {
    $('#boton_cargar_anexos_expediente').prop('disabled', true);
    var formData = new FormData();
    var imagefile = document.querySelector('#archFile');
    //$('#operAnexo-error').html('Debe colocar un tiltulo diferente');
    $('#operAnexo-error').html('');
    $('#operAnexo-msg').html('');
    msq = '';
    if (!$('#descriop').val()) msq = 'Debe colocar una descripción';
    if (!$('#tpDocAnex').val()) msq = 'Debe selecionar un tipo de documento';
    if (!imagefile.files[0]) msq = 'Debe selecionar un archivo';

    if (msq) {
        $('#operAnexo-error').html(msq);
        return false;
    }
    formData.append("image", imagefile.files[0]);
    formData.append("exp", $('#numexp').val());
    formData.append("tpDocAnex", $('#tpDocAnex').val());
    formData.append("descriop", $('#descriop').val());
    formData.append("fn", "uploadAnex");
    parent.$('#processing-modal').modal('show');
    axios.post('../expediente/exp-rest.php', formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }).then(function (response) {
        console.log('12');
        // console.log(response);
        data = response.data;
        // console.log(data);
        if (data.success) {
            $('#operAnexo-msg').html('Se creo el anexo ' + data.conse + ' del expediente ' + $('#numexp').val());
        }
        else $('#operAnexo-msg').html('No Se creo el anexo, No se puede cargar el documento ');
        cargartabla('S');
        setTimeout(function () { $('#processing-modal').modal('hide'); }, 2000);
        $('#boton_cargar_anexos_expediente').prop('disabled', false);
        parent.$('#processing-modal').modal('hide');
        $('#ModaladdAnex').modal('hide');

    })
        .catch(function (error) {
            parent.$('#processing-modal').modal('hide');
            $('#boton_cargar_anexos_expediente').prop('disabled', false);
            $('#ModaladdAnex').modal('hide');

            if (error.hasOwnProperty('response') && Object.keys(error.response).length > 0) {
                $(this).showError('Error en petición', 'Estado del error: ' + error.response.status + '. Mensaje: ' + error.response.data.error);
            }
            //toastr.error(data.message, 'Error al Modificar ');
        });

}

$(function () {
    $('#generar_indice_electronico').on('click', function (e) {
        $('#generar_indice_electronico').prop('disabled', true);
        var request = $.get($(this).data('rel'));
        request.done(function () {
            console.log('done');
            window.location.reload();
        });

        request.fail(function (xhr, status, error) {
            console.log('fail', status, error);
        })

        request.always(function () {
            console.log('always');
        });
    });
})