<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::get('/', 'AuthController@loginPage');
Route::get('/login', 'AuthController@login')->name('login');
Route::get('/pegi', 'AuthController@pegi')->name('pegi');
Route::get('/altis', 'AuthController@altis')->name('altis');
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');

Route::get('/tos', 'HomeController@tos')->name('tos');
Route::get('/monetization', 'HomeController@monetization')->name('monetization');
Route::get('/privacy', 'HomeController@privacy')->name('privacy');
Route::get('/about', 'HomeController@about')->name('about');

Route::get('/verify/{code}', 'UserController@verifyCode')->name('verify');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/account', 'UserController@accountPage')->name('account');

Route::get('account/namechange', 'UserController@namePage')->name('account-namechange');
Route::post('account/namechange', 'UserController@name');
Route::post('account/namechange/check', 'UserController@nameCheck')->name('account-namechange-check');

Route::post('/account/resetemail', 'UserController@resetEmail')->name('account-resetemail');

Route::group(['prefix' => 'setup'], function () {
    Route::get('/welcome', 'SetupController@welcome')->name('setup-welcome');

    Route::get('/checkgame', 'SetupController@checkGamePage')->name('setup-checkgame');
    Route::post('/checkgame', 'SetupController@checkGame');

    Route::get('/info', 'SetupController@infoPage')->name('setup-info');
    Route::post('/info', 'SetupController@info');

    Route::get('/email', 'SetupController@emailPage')->name('setup-email');
    Route::post('/email', 'SetupController@email');
    Route::post('/email/code', 'SetupController@emailCode');

    Route::get('/intro', 'SetupController@introPage')->name('setup-intro');

    Route::get('/name', 'SetupController@namePage')->name('setup-name');
    Route::post('/name', 'SetupController@name');
    Route::post('/name/check', 'SetupController@nameCheck')->name('setup-name-check');

    Route::get('/rules', 'SetupController@rulesPage')->name('setup-rules');
    Route::get('/rules/check', 'SetupController@rulesCheck')->name('setup-rules-check');

    Route::get('/exam/{page?}', 'SetupController@examPage')->name('setup-exam')
        ->where('page', '[0-9]+');
    Route::post('/exam', 'SetupController@generateExam');
    Route::post('/exam/{page}', 'SetupController@exam')
        ->where('page', '[0-9]+');

    Route::get('/forum', 'SetupController@forumPage')->name('setup-forum');
    Route::get('/forum/socialite/', 'SetupController@forumRedirect')->name('setup-forum-socialite-redirect');
    Route::get('/forum/socialite/callback/ipb', 'SetupController@forumCallback')->name('setup-forum-socialite-callback');

    Route::get('/interview', 'SetupController@interviewPage')->name('setup-interview');
});

Route::post('notifications/markallasread', 'NotificationsController@markAllAsRead')->name('notifications-allread');

Route::group(['prefix' => 'mod'], function () {
    Route::get('/', 'ModController@dashboardPage')->name('mod-dashboard');
    Route::get('/', 'ModController@dashboardPage')->name('mod-dashboard');
    Route::get('/', 'ModController@dashboardPage')->name('mod-dashboard');

    Route::get('search', 'ModController@searchPage')->name('mod-search');

    Route::get('/review', 'ModController@reviewPage')->name('mod-review');
    Route::post('/review', 'ModController@review');
    Route::get('/review/get', 'ModController@reviewGet')->name('mod-review-get');

    // Supervisar
    Route::get('/supervise', 'ModController@supervisePage')->name('mod-supervise');
    Route::post('/supervise', 'ModController@supervise');
    Route::get('/supervise/get', 'ModController@superviseGet')->name('mod-supervise-get');

    // User
    Route::group(['prefix' => 'user'], function () {
        Route::get('/{id}', 'ModController@userPage')->name('mod-user');
        Route::group(['prefix' => '/{id}/reveal'], function () {
            Route::post('birthdate', 'ModController@revealBirthDate')->name('mod-reveal-birhtdate');
            Route::post('email', 'ModController@revealEmail')->name('mod-reveal-email');
        });
        Route::post('/{id}/name/disable', 'ModController@disableName')->name('mod-user-name-disable');
        Route::post('/{id}/name/enable', 'ModController@enableName')->name('mod-user-name-enable');
    });

    // Interview
    Route::get('/interview/{id}', 'ModController@interviewPage')->name('mod-interview');
    Route::post('/interview/{id}', 'ModController@interview');
    Route::post('/interview/{id}/code', 'ModController@interviewCode')->name('mod-interview-code');
    Route::post('/interview/{id}/cancel', 'ModController@interviewCancel')->name('mod-interview-cancel');

    Route::post('/interview/{id}/grade', 'ModController@interviewGrade')->name('mod-interview-grade');

    Route::resource('names', 'Mod\NameController');
    Route::resource('exams', 'Mod\ExamController');
});

Route::get('/home', 'HomeController@index')->name('home');

// ACL
Route::group(['prefix' => 'acl', 'middleware' => ['auth', 'admin']], function () {
    // Users
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', 'ACL\UsersController@listUsers')->name('acl-users');

        // Nuevo usuario
//        Route::get('/new', 'ACL\UsersController@newUserPage')->name('acl-users-new');
//        Route::post('/new', 'ACL\UsersController@newUser');

        // Editar usuario
        Route::get('/{id}', 'ACL\UsersController@editUserPage')->name('acl-users-edit');
        Route::post('/{id}', 'ACL\UsersController@editUser');
    });

    // Roles
    Route::group(['prefix' => 'roles'], function () {
        Route::get('/', 'ACL\RolesController@listRoles')->name('acl-roles');

        Route::get('/new', 'ACL\RolesController@newRolePage')->name('acl-roles-new');
        Route::post('/new', 'ACL\RolesController@newRole');

        Route::get('/{id}', 'ACL\RolesController@editRolePage')->name('acl-roles-edit');
        Route::post('/{id}', 'ACL\RolesController@editRole');

        Route::post('/{id}/delete', 'ACL\RolesController@deleteRole')->name('acl-roles-delete');
    });

    Route::group(['prefix' => 'permissions'], function () {
        Route::get('/', 'ACL\PermissionsController@listPermissions')->name('acl-permissions');
    });
});

Route::get('/discourse/login', 'DiscourseController@sso')->name('discourse-login');

Route::group(['prefix' => 'whitelist'], function () {
    Route::get('/', 'WhitelistController@whitelistPage')->name('whitelist');
    Route::get('download', 'WhitelistController@download')->name('whitelist-download');
});

Route::group(['prefix' => 'inrol', 'middleware' => ['auth', 'setup_required']], function () {
    Route::get('/dgt/matriculados', 'Inrol\Dgt\VehicleController@listOwnVehicles')->name('inrol-dgt-matriculados');
    Route::get('/dgt/permisos', 'Inrol\Dgt\VehicleController@viewOwnLicenses')->name('inrol-dgt-permisos');

    Route::get('/dgt/transferir/{id}', 'Inrol\Dgt\VehicleController@transferPage')->name('inrol-dgt-transferir');

    Route::get('/banco/cuentas', 'Inrol\Banco\BankController@viewAccounts')->name('inrol-banco-cuentas');

    Route::group(['prefix' => 'justicia'], function () {
        Route::get('/personas', 'Inrol\Justicia\PlayerController@search')->name('inrol-justicia-personas');
    });
});

Route::get('poplifeservers.txt', 'ServerController@json');

/* CATCH-ALL ROUTE for Backpack/PageManager - needs to be at the end of your routes.php file  **/
Route::get('{page}/{subs?}', ['uses' => 'PageController@index'])
    ->where(['page' => '^((?!admin).)*$', 'subs' => '.*'])->name('page')->middleware('auth');
