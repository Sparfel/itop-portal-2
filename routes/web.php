<?php


use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/test', [App\Http\Controllers\HomeController::class, 'test'])->name('test');
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('home');
    Route::get('/getcommunications', [App\Http\Controllers\HomeController::class, 'getCommunications'])->name('getcommunications');//->middleware('can:get_communications');
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
//        Route::get('/openedrequest/filter/{priority}/{type?}', [App\Http\Controllers\Frontend\Request\OpenedRequestController::class, 'index'])
//            ->name('openedrequest.filter');
    Route::get('/openedrequest/filter', [App\Http\Controllers\Frontend\Request\OpenedRequestController::class, 'index'])->name('openedrequest.filter');
    Route::get('/closedrequest/filter', [App\Http\Controllers\Frontend\Request\ClosedRequestController::class, 'index'])->name('closedrequest.filter');


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
    //changement de mot de passe
    Route::post('/change-password', [App\Http\Controllers\Frontend\User\ProfileController::class, 'changePassword'])->name('changePassword');
    Route::post('/verify-old-password', [App\Http\Controllers\Frontend\User\ProfileController::class, 'verifyOldPassword'])->name('verifyOldPassword');
    Route::get('/verify-old-password', [App\Http\Controllers\Frontend\User\ProfileController::class, 'verifyOldPassword'])->name('verifyOldPassword');

    //Consultation des logs
    // Log viewer
    Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index'])->can('logs');

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
    Route::get('/getOrganizations', [App\Http\Controllers\Frontend\AjaxController::class, 'getOrganizations'])->name('getOrganizations');
    Route::get('/getOrganizationLocations', [App\Http\Controllers\Frontend\AjaxController::class, 'getOrganizationLocations'])->name('getOrganizationLocations');


    Route::group(['prefix' => 'administration','middleware' => ['can:permissions']], function () {
        // Comptes
        Route::get('/listusers', [App\Http\Controllers\Backend\User\UserController::class, 'index'])->name('listusers');
        Route::post('/edituser', [App\Http\Controllers\Backend\User\UserController::class, 'editUser'])->name('edititopuser');
        Route::post('/storeuser', [App\Http\Controllers\Backend\User\UserController::class, 'storeUser'])->name('storeuser');
        Route::post('/deleteuser', [App\Http\Controllers\Backend\User\UserController::class, 'deleteUser'])->name('deleteuser');
        Route::post('/deleteusers', [App\Http\Controllers\Backend\User\UserController::class, 'deleteUsers'])->name('deleteusers');

        //Permissions
        //Route::get('/listroles', [App\Http\Controllers\Backend\Permission\RoleController::class, 'index'])->name('listroles');
        //Route::get('/editrole', [App\Http\Controllers\Backend\Permission\RoleController::class, 'edit'])->name('editrole');
        Route::resource('roles', App\Http\Controllers\Backend\Permission\RoleController::class);
        Route::resource('permissions', App\Http\Controllers\Backend\Permission\PermissionController::class);

        //Import
        Route::post('/importPerson', [App\Http\Controllers\Backend\SynchronizationController::class, 'importPerson'])->name('importPerson');
        Route::post('/truncatePerson', [App\Http\Controllers\Backend\SynchronizationController::class, 'truncatePerson'])->name('truncatePerson');

        //Import iTop
        Route::get('/listitopusers', [App\Http\Controllers\Backend\User\ItopUserController::class, 'listitopusers'])->name('listitopusers');
        Route::post('/ajxcreateusers', [App\Http\Controllers\Frontend\AjaxController::class, 'createUsers'])->name('ajxcreateusers');
        Route::post('/storeitopuser', [App\Http\Controllers\Frontend\AjaxController::class, 'storeItopUser'])->name('storeitopuser');
        //    Route::get('/edititopuser/{id}', [App\Http\Controllers\Backend\User\ItopUserController::class, 'editItopUser'])->name('edititopuser');
        Route::post('/edititopuser', [App\Http\Controllers\Backend\User\ItopUserController::class, 'editItopUser2'])->name('edititopuser');
        Route::post('/deleteitopuser', [App\Http\Controllers\Backend\User\ItopUserController::class, 'deleteItopUser'])->name('deleteitopuser');
        Route::post('/deleteitopusers', [App\Http\Controllers\Backend\User\ItopUserController::class, 'deleteItopUsers'])->name('deleteitopusers');
        Route::post('/notifyitopusers', [App\Http\Controllers\Backend\User\ItopUserController::class, 'notifyItopUsers'])->name('notifyitopusers');

        Route::post('/syncitopusers', [App\Http\Controllers\Backend\User\ItopUserController::class, 'syncitop'])->name('syncitopusers');
        Route::get('/syncitopusers', [App\Http\Controllers\Backend\User\ItopUserController::class, 'syncitop'])->name('syncitopusers');

        Route::get('/listitoporg', [App\Http\Controllers\Backend\Itop\OrganizationController::class, 'index'])->name('listitoporg');
        Route::post('/deleteitoporg', [App\Http\Controllers\Backend\Itop\OrganizationController::class, 'deleteItopOrg'])->name('deleteitoporg');
        Route::post('/deleteitoporgs', [App\Http\Controllers\Backend\Itop\OrganizationController::class, 'deleteItopOrgs'])->name('deleteitorgs');
        Route::post('/importOrg', [App\Http\Controllers\Backend\SynchronizationController::class, 'importOrg'])->name('importOrg');
        Route::post('/storeitoporg', [App\Http\Controllers\Frontend\AjaxController::class, 'storeItopOrg'])->name('storeitoporg');
        Route::post('/edititoporg', [App\Http\Controllers\Backend\Itop\OrganizationController::class, 'editItopOrg'])->name('edititoporg');

        Route::get('/listitoploc', [App\Http\Controllers\Backend\Itop\LocationController::class, 'index'])->name('listitoploc');
        Route::post('/deleteitoploc', [App\Http\Controllers\Backend\Itop\LocationController::class, 'deleteItopLoc'])->name('deleteitoploc');
        Route::post('/deleteitoplocs', [App\Http\Controllers\Backend\Itop\LocationController::class, 'deleteItopLocs'])->name('deleteitlocs');
        Route::post('/importLoc', [App\Http\Controllers\Backend\SynchronizationController::class, 'importLoc'])->name('importLoc');
        Route::post('/storeitoploc', [App\Http\Controllers\Frontend\AjaxController::class, 'storeItopLoc'])->name('storeitoploc');
        Route::post('/edititoploc', [App\Http\Controllers\Backend\Itop\LocationController::class, 'editItopLoc'])->name('edititoploc');

    });

});
