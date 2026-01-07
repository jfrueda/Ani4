INSERT INTO bodega_empresas (
    identificador_empresa,
    nombre_de_la_empresa,
    nit_de_la_empresa,
    direccion,
    codigo_del_departamento,
    codigo_del_municipio,
    telefono_1,
    telefono_2,
    email,
    nombre_rep_legal,
    cargo_rep_legal
    ) VALUES (
        {{identEmpresa}},
        '{{nombreEmpresa}}',
        '{{nitEmpresa}}',
        '{{direcccion}}',
        {{codigoDpto}},
        {{codigoMpio}},
        '{{telefono1}}',
        '{{telefono2}}',
        '{{email}}',
        '{{represLegal}}',
        '{{cargo}}'
      ) 