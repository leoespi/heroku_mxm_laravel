<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Incapacidades\IncapacidadesController;
use App\Http\Controllers\API\UserApiController;
use App\Http\Controllers\API\RolApiController;

use App\Http\Controllers\CesantiasController;
use App\Http\Controllers\ReferidosController;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\ExcelIncapacidadesController;
use App\Http\Controllers\ExcelCesantiasController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\HorariosController;
use App\Http\Controllers\RegistrosController;


// Rutas sin middleware

Route::get('/feeds/{id}/download-images', [FeedController::class, 'downloadImages']);

Route::post('password/forgot', [UserApiController::class, 'sendResetPin']); //Enviar pin de reseteo de contraseña
Route::post('password/reset', [UserApiController::class, 'resetPasswordWithPin']); //Resetear contraseña

Route::post('login', [AuthenticationController::class, 'login']); //Iniciar sesion
Route::post('register', [AuthenticationController::class, 'register']); //Registrarse Usuarios
Route::post('registeradmin', [AuthenticationController::class, 'registerAdmin']); //Registro Administrador



Route::get('/test', function () {
    return response(['message' => 'Api is working'], 200);
});
Route::get('/Excel', function () {
    return view('Excel');
});



// Rutas con middleware 'auth:api'
Route::middleware('auth:api')->group(function () {

    //endpoints Usuarios
    Route::put('/updateUser', [UserApiController::class, 'update']);
    Route::apiResource('user', UserApiController::class); //Apiresource como pa asegurar
    Route::get('/users', [UserApiController::class, 'index']);
    Route::post('/users/{id}/activate', [UserApiController::class, 'activate']); //desactivar usuario
    Route::post('/users/{id}/deactivate', [UserApiController::class, 'deactivate']); //activar usuario
    Route::get('/get/user', [UserApiController::class, 'indexUser']);


    Route::get('logout', [AuthController::class, "logout"]);//Cerrar sesion

    Route::get('/export-users', [ExcelController::class, 'exportUsers'])->name('export-users'); //Exportar usuarios

    // EndPoints Incapacidades
    Route::apiResource('incapacidades', IncapacidadesController::class); // Apiresource (pa que no se despapaye)
    Route::get('/incapacidadesall', [IncapacidadesController::class, 'indexAll']); //get all incapacidades
    Route::get('incapacidades/{id}/documentos', [IncapacidadesController::class, 'downloadDocument']); //Descargar documentos incapacidades
    Route::get('/incapacidades/{id}/download-images', [IncapacidadesController::class, 'downloadImages']); //Descargar imagenes incapacidades
    Route::get('/export-incapacidades', [ExcelIncapacidadesController::class, 'exportIncapacidades'])->name('export-incapacidades'); //Export de todas las incapacidades


    Route::middleware('auth:sanctum')->get('/incapacidades/user', [IncapacidadesController::class, 'userIncapacidades'])->name('incapacidades.user');
    

    // EndPoints Cesantias
    Route::apiResource('cesantias', CesantiasController::class); //api resource de las cesantias
    Route::get('/cesantiasall', [CesantiasController::class, 'indexAll']); // get de todas las cesantias
    Route::get('/export-cesantias/{year}', [ExcelCesantiasController::class, 'exportCesantias'])->name('export-cesantias'); //Export excell de cesantias
    Route::put('/cesantias/{id}/authorize', [CesantiasController::class, 'authorizeCesantia']); //Autorizar cesantia
    Route::post('cesantias/deny/{id}', [CesantiasController::class, 'DenyCesantia']); //Denegar cesantia    
    Route::get('cesantias/{id}/documentos', [CesantiasController::class, 'downloadDocument']); //Descargar documentos cesantias
    Route::get('/cesantias/{id}/download-images', [CesantiasController::class, 'downloadImages']);//Descargar imagenes cesantias 
    Route::get('authorizedCesantia', [CesantiasController::class, 'indexCesantiasAutorizadas']); //Get  cesantias Autorizadas
    Route::post('/cesantias/denyadmin/{id}', [CesantiasController::class, 'DenyAuthorizedCesantia']); //Denegar cesantia autorizada
    Route::post('cesantias/aprobar/{id}', [CesantiasController::class, 'AcceptCesantia']); // APROBAR cesantia

    Route::get('cesantias/{uuid}/images-size', [CesantiasController::class, 'calculateImagesSizeInMB']); // Calcular tamaño de las imagenes


    //Route::get('authorizedCesantia/download-zip/{uuid}', [CesantiasController::class, 'downloadZipAutorized']);

    // EndPoints Referidos
    Route::apiResource('referidos', ReferidosController::class);
    Route::get('referidos/download/{id}', [ReferidosController::class, 'downloadDocumento']);


    // MIS Registros 
    Route::get('/indexcesantias', [RegistrosController::class, 'indexcesantias']);
    Route::get('/indexincapacidades', [RegistrosController::class, 'indexincapacidades']);


    // EndPoints Feed (publicacion)
    Route::apiResource('feeds', FeedController::class);
    Route::post('feeds', [FeedController::class, 'store']);
    Route::get('feeds', [FeedController::class, 'index']);
    
    
    Route::get("/perfil/ver", [PerfilController::class, 'verPerfil']);

    Route::post("/horarios-import", [HorariosController::class, 'store']);
    Route::get('/horarios', [HorariosController::class, 'index']);



    


});