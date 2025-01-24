<?php


use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('home');
    //Json for dashboard component
    Route::get('/listrequests', [App\Http\Controllers\HomeController::class, 'getListRequests'])->name('listrequests'); //->middleware('can:list_requests');


    //Create un ticket
    Route::get('/newrequest', [App\Http\Controllers\Frontend\Request\NewRequestController::class, 'create'])->name('newrequest')->middleware('can:create_new_request');
    Route::post('/newrequest', [App\Http\Controllers\Frontend\Request\NewRequestController::class, 'store'])->name('newrequest')->middleware('can:store_new_request');
    Route::post('/requestcreated', [App\Http\Controllers\Frontend\Request\NewRequestController::class, 'done'])->name('requestcreated')->middleware('can:done_creation_request');

    //The opened tickets
    Route::get('/openedrequest', [App\Http\Controllers\Frontend\Request\OpenedRequestController::class, 'index'])->name('openedrequestall'); //->middleware('can:browse_opened_request');
        //Gestion des filtres
        //Route::get('/openedrequest/prio/{priority}', [App\Http\Controllers\Frontend\Request\OpenedRequestController::class, 'index'])->name('openedrequest.filter.prio'); //->middleware('can:browse_opened_request');
        Route::get('/openedrequest/filter/{priority}/{type?}', [App\Http\Controllers\Frontend\Request\OpenedRequestController::class, 'index'])
            ->name('openedrequest.filter');

    Route::post('/openedrequest/{id}', [App\Http\Controllers\Frontend\Request\OpenedRequestController::class, 'update'])->name('openedrequest'); //->middleware('can:update_opened_request');
    Route::get('/openedrequest/{id}/{tab?}', [App\Http\Controllers\Frontend\Request\OpenedRequestController::class, 'edit'])->name('openedrequest'); //->middleware('can:view_opened_request');
    //the closed tickets
    Route::get('/closedrequest', [App\Http\Controllers\Frontend\Request\ClosedRequestController::class, 'index'])->name('closedrequest')->middleware('can:browse_closed_request');
    Route::get('/closedrequest/{id}/{tab?}', [App\Http\Controllers\Frontend\Request\ClosedRequestController::class, 'edit'])->name('closedrequest')->middleware('can:view_closed_request');

    Route::get('/getcontactlist', [App\Http\Controllers\Frontend\Request\OpenedRequestController::class, 'getAllContacts'])->name('getcontactlist'); //->middleware('can:get_contact_list');
    Route::post('/addcontactlist', [App\Http\Controllers\Frontend\Request\OpenedRequestController::class, 'addListContacts'])->name('addcontactlist'); //->middleware('can:add_contact_list');
    Route::get('/downloadattachment/{id}', [App\Http\Controllers\Frontend\Request\OpenedRequestController::class, 'downloadAttachment'])->name('downloadattachment'); //->middleware('can:download_attachment');
    Route::post('/uploadattachment', [App\Http\Controllers\Frontend\Request\OpenedRequestController::class, 'uploadAttachment'])->name('uploadattachment'); //->middleware('can:upload_attachment');
    Route::post('/removeattachment', [App\Http\Controllers\Frontend\Request\OpenedRequestController::class, 'removeAttachment'])->name('removeattachment'); //->middleware('can:remove_attachment');




    Route::get('language/{lang}', [App\Http\Controllers\LanguageController::class, 'language'])->name('language');

    //Profil
    //gestion du cas ou l'ID n'est pas dans l'url
    Route::get('/profile', [App\Http\Controllers\Frontend\User\ProfileController::class, 'index'])->name('profile'); //->middleware('can:view_profile');
    Route::post('/profile/', [App\Http\Controllers\Frontend\User\ProfileController::class, 'store'])->name('profile');//->middleware('can:update_profile');
    //gestion du cas ou l'ID est dans l'url
    Route::get('/profile/{id}', [App\Http\Controllers\Frontend\User\ProfileController::class, 'index'])->name('profile'); //->middleware('can:view_profile');
    Route::post('/profile/{id}', [App\Http\Controllers\Frontend\User\ProfileController::class, 'store'])->name('profile');//->middleware('can:update_profile');

    //Consultation des logs
    // Log viewer
    Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

//    Route::get('permission-editor/roles', [\Ihtisham467\LaravelPermissionEditor\Http\Controllers\PermissionController::class, 'index'])->middleware('can:permissions');
// Restreint uniquement aux utilisateurs ayant la permission 'permissions'
    Route::get('permission-editor/roles', [\Ihtisham467\LaravelPermissionEditor\Http\Controllers\RoleController::class, 'index'])
        ->name('permission-editor.roles.index')
        ->middleware('can:permissions');
        Route::get('permission-editor/permissions', [\Ihtisham467\LaravelPermissionEditor\Http\Controllers\PermissionController::class, 'index'])
        ->name('permission-editor.permissions.index')
        ->middleware('can:permissions');




    // !!!!! empecher l'edition ! trop permissif car si pas défini ici alors ça passe !
    /*
    * Ajax call
    */
    Route::get('/getLocations', [App\Http\Controllers\Frontend\AjaxController::class, 'getLocations'])->name('getLocations');


});
