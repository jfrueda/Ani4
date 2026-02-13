<?php
use App\Auth\Authentication;
use App\Radicador\Registro;
use App\Mails\Mails;
use App\Lib\Request;
use App\Lib\Response;
use App\Lib\Router;
use App\Auth\AuthJwt;



Router::post('/restapi/auth/', function (Request $req, Response $res) {
	(new Authentication())->token($req, $res);
});

//********************************************************************** */
// Endpoints para gestionar la radicacion
//********************************************************************* */

Router::post('/restapi/webservice/', function (Request $req, Response $res) {
    (new Registro())->webservice($req, $res);
});

Router::get('/restapi/mails/', function (Request $req, Response $res) {
    (new Mails())->webservice($req, $res);
});

Router::post('/restapi/radmails/', function (Request $req, Response $res) {
    (new Mails())->mailsWs($req, $res);
});

//********************************************************************** */
// Endpoints Para Gestionar La Firma
//********************************************************************* */
Router::post('/restapi/authJwt/', function (Request $req, Response $res) {
    (new AuthJwt())->generateToken($req, $res);
});

Router::post('/restapi/encriptPdf/', function (Request $req, Response $res) {
    (new AuthJwt())->tokenDesencript($req, $res);
});
