<?php

use App\Http\Controllers\API\Administration\PaypalController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::resource('newsletter', 'API\Administration\NewsLetterController');
Route::post('logs', 'GlobalErrorHandlerController@store');

Route::group([
    'prefix' => 'facebook',
], function () {
    Route::post('/delete-user-data', 'API\V1\AuthController@deleteData');
    Route::get('/deleted-data', 'API\V1\AuthController@checkDeletedData');

});

Route::controller(PaypalController::class)
    ->prefix('paypal')
    ->group(function () {
        Route::get('handle-payment', 'handlePayment')->name('make.payment');
        Route::get('cancel-payment', 'paymentCancel')->name('cancel.payment');
        Route::get('payment-success', 'paymentSuccess');
        Route::get('change-successful', 'changeSuccessful');
        Route::get('change-unsuccessful', 'changeUnSuccessful');
        Route::post('create-subscription', 'createSubscriptionForMobile');
        Route::post('create-order', 'createOrderForMobile');

        Route::post('webhook-endpoint', 'handleWebHook');
        Route::post('capture-order', 'captureOrder');
        Route::post('get-subscription-detail', 'getSubscriptionDetail');

    });

Route::post('/refreshing-token', 'API\V1\AuthController@refreshToken');

Route::group([
    'middleware' => ['auth:api', 'public-schema'],
], function () {

    Route::post('register', 'API\V1\AuthController@register')->withoutMiddleware(['auth:api']);
    Route::post('login', 'API\V1\AuthController@login')->withoutMiddleware(['auth:api']);
    Route::post('pre-login', 'API\V1\AuthController@preLogin')->withoutMiddleware(['auth:api']);

    Route::post('change-password', 'API\V1\ChangePasswordController@changePassword')->withoutMiddleware(['auth:api']);
    Route::post('can-change-password/{token}', 'API\V1\ChangePasswordController@canChangePassword')->withoutMiddleware(['auth:api']);
    Route::post('reset-password', 'API\V1\ChangePasswordController@resetPassword')->withoutMiddleware(['auth:api']);
    Route::post('logout', 'API\V1\AuthController@logout');

    Route::post('autenticacion/usuario', 'API\V1\AuthController@authUser');
    Route::post('verificar-suscripcion', 'API\V1\AuthController@verifySubscription');

    Route::post('administracion/modificar-perfil', 'API\V1\AuthController@updateProfile');
    //TUTORIAL
    Route::post('administracion/skip-tutorial', 'API\V1\AuthController@skipTutorial');

});

Route::group([
    'namespace' => 'API\Administration',
    'prefix'    => 'payment',
], function () {
    Route::post('/transferencia-bancaria', 'BankTransferController@payment');
    Route::post('/zinli', 'ZinliController@payment');
    Route::post('/manejo-transaccion', 'BankTransferController@handle_transaction');

});

Route::group([
    'namespace' => 'API\PDF',
    'prefix'    => 'pdf',
], function () {
    Route::get('/descargar-orden/{id}', 'OrderController@download');
    Route::get('/descargar-todo-croquis/{id}', 'SketchController@download_all');
    Route::get('/descargar-todo-planes/{id}', 'ManagementPlanController@download_all');
    Route::get('/descargar-todo-informes/{id}', 'ReportController@download_all');
    Route::get('/descargar-todo-mip/{id}', 'MipController@download_all');
    Route::get('/descargar-todo-tendencias/{id}', 'TrendController@download_all');

});

Route::group([
    'middleware' => ['auth:api', 'module-schema'],
    'namespace'  => 'API\Administration',
    'prefix'     => 'administracion',
], function () {

    Route::resource('/clientes', 'ClientController');
    Route::post('/clientes-crear-usuario', 'ClientController@createUser');
    Route::post('/clientes-añadir-archivo', 'ClientController@addFile');

    Route::resource('/obreros', 'WorkerController');
    Route::post('/obreros-crear-usuario', 'WorkerController@createUser');

    Route::resource('/plagas', 'PestController');
    Route::resource('/dispositivos', 'DeviceController');
    Route::resource('/ubicaciones', 'LocationController');
    Route::resource('/aplicaciones', 'AplicationController');
    Route::resource('/metodos-de-desinfeccion', 'DesinfectionMethodController');
    Route::resource('/acciones-correctivas', 'CorrectiveActionController');
    Route::resource('/elementos-afectados', 'AffectedElementController');
    Route::resource('/tipo-de-construcciones', 'ConstructionTypeController');
    Route::resource('/tratamientos-aplicados', 'AppliedTreatmentController');

    Route::resource('/transacciones', 'TransactionController');
    Route::resource('/usuarios', 'UserController');
    Route::post('/usuarios/cambiar-contrasena', 'UserController@changePassword');

    Route::resource('/lugares-de-aplicacion', 'AplicationPlaceController');
    Route::resource('/productos', 'ProductController');
    Route::resource('/orders', 'OrderController');
    Route::post('/orders/check-order', 'OrderController@checkOrder');
    Route::post('/orders/finalizar', 'OrderController@finish')->middleware('active-user');;
    Route::post('/orders/reenviar/{id}', 'OrderController@resend')->middleware('active-user');;
    Route::post('/orders/pendiente', 'OrderController@pending')->middleware('active-user');;
    Route::post('/listar-ordenes', 'OrderController@index');
    Route::post('/orders/daily-orders', 'OrderController@dailyOrders');

    Route::resource('/fumigaciones', 'FumigationController');
    Route::resource('/lamparas', 'LampController');
    Route::resource('/trampas', 'TrapController');
    Route::resource('/observaciones', 'ObservationController');
    Route::resource('/control-de-roedores', 'RodentControlController');
    Route::resource('/control-de-xilofagos', 'XylophageControlController');
    Route::resource('/control-de-legionella', 'LegionellaControlController');

    Route::resource('/imagenes', 'ImageController');
    Route::resource('/firmas', 'SignatureController');

    Route::resource('/permisos', 'PermissionController');
    Route::post('/permisos/agregar-permisos', 'PermissionController@addPermissions');

    Route::get('/permisos/descargar-permisos/{id}', 'PermissionController@download');

    Route::resource('/minsalud', 'MinSaludController');
    Route::get('/minsalud/descargar-min-salud/{id}', 'MinSaludController@download');

    Route::resource('/msds', 'MsdsController');
    Route::get('/msds/descargar-msds/{id}', 'MsdsController@download');

    Route::resource('/ficha-tecnica', 'TechnicalSheetController');
    Route::get('/ficha-tecnica/descargar-ficha-tecnica/{id}', 'TechnicalSheetController@download');

    Route::resource('/personal-tecnico', 'TechnicalStaffController');
    Route::get('/personal-tecnico/descargar-personal-tecnico/{id}', 'TechnicalStaffController@download');

    Route::resource('/croquis', 'SketchController');
    Route::get('/croquis/descargar-croquis/{id}', 'SketchController@download');

    Route::resource('/mip', 'MipController');
    Route::get('/mip/descargar-mip/{id}', 'MipController@download');

    Route::resource('/informes', 'ReportController');
    Route::get('/informes/descargar-informes/{id}', 'ReportController@download');

    Route::resource('/tendencias', 'TrendController');
    Route::get('/tendencias/descargar-tendencias/{id}', 'TrendController@download');

    Route::resource('/planes', 'ManagementPlanController');
    Route::get('/planes/descargar-planes/{id}', 'ManagementPlanController@download');

    Route::resource('/etiquetas', 'LabelController');
    Route::get('/etiquetas/descargar-etiquetas/{id}', 'LabelController@download');

    Route::resource('/dashboard', 'DashboardController');
    Route::post('/dashboard/filter-dates', 'DashboardController@filterDashboard');
    Route::post('payment-success', 'PaypalController@paymentSuccessV2');
    Route::post('change-plan', 'PaypalController@changePlan');

    Route::resource('/contacto', 'ContactController');

    Route::group([
        'prefix' => 'configuraciones',
    ], function () {
        Route::post('/eliminar-cuenta', 'Configuration\ConfigurationController@destroyModule');
        Route::post('/cancelar-suscripcion', 'PaypalController@cancelSubscription');

    });

});
