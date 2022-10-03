<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\XMLController;
use App\Http\Controllers\ZipController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CollectionsController;

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

/**
 * Authenticating, if the user is logged in.
 * For authentication we use: ->middleware('auth')
 */

/**
 * A helper class that helps you generate all the routes required for user authentication
 */
Auth::routes();

/**
 ** Active User Authorization
 */
Route::group(['middleware' => ['auth', 'active_user']], function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    // ... Any other routes that are accessed only by non-blocked user

    /**
     ** Admin Authorization
     */
    Route::middleware(['auth', 'admin'])->group(function () {
        /**
         * Function: Dashboards
         */
        Route::get('dashboard/home', [AdminController::class, 'dashboard'])->name('home');
        Route::get('dashboard/brokers', [AdminController::class, 'brokerDashboard'])->name('brokers');
        Route::get('dashboard/users', [AdminController::class, 'userDashboard'])->name('users');
        Route::get('dashboard/properties', [AdminController::class, 'propertyDashboard'])->name('properties');
        Route::get('dashboard/houses/{api_key}', [AdminController::class, 'houseDashboard'])->name('houses');

        /**
         * Profile: Admins
         */
        Route::get('admin/profile', [ProfileController::class, 'adminProfile'])->name('admin.profile');
        Route::post('admin/profile/update', [ProfileController::class, 'updateAdminProfile'])->name('admin.profile.update');

        /**
         * Image: Admins
         */
        Route::get('admin/image/upload', [ImageController::class, 'uploadImageToAdmin'])->name('admin.image.upload');
        Route::post('admin/image/store', [ImageController::class, 'storeImageToAdmin'])->name('admin.image.store');

        /**
         * Contact
         */
        Route::get('dashboard/contact', [ContactController::class, 'contact'])->name('admin.contact');
        Route::post('dashboard/email/send', [ContactController::class, 'sendEmail'])->name('admin.email.send');

        /**
         ** Components
         */
        // Route::get('dashboard/components', [ComponentController::class, 'componentDashboard']);
        // Route::get('dashboard/view/component', [ComponentController::class, 'viewComponent']);

        /**
         * New Models
         */
        Route::get('dashboard/updated/models', [ModelController::class, 'index']);

        /**
         * Function: View data & Global Scope
         */
        Route::get('dashboard/view/house/{property_ID}', [AdminController::class, 'viewHouse'])->name('house.view');

        /**
         * Function: Add data
         */
        Route::get('dashboard/add/user', [AdminController::class, 'addUser'])->name('user.add');
        Route::get('dashboard/add/broker', [AdminController::class, 'addBroker'])->name('broker.add');

        /**
         * Function: Store data
         */
        Route::post('dashboard/store/user', [AdminController::class, 'storeUser'])->name('user.store');
        Route::post('dashboard/store/broker', [AdminController::class, 'storeBroker'])->name('broker.store');

        /**
         * Function: Edit data
         */
        Route::get('dashboard/edit/user/{id}', [AdminController::class, 'editUser'])->name('user.edit');
        Route::get('dashboard/edit/broker/{api_key}', [AdminController::class, 'editBroker'])->name('broker.edit');

        /**
         * Function: Update data
         */
        Route::post('dashboard/update/user/{id}', [AdminController::class, 'updateUser'])->name('user.update');
        Route::post('dashboard/update/broker/{api_key}', [AdminController::class, 'updateBroker'])->name('broker.update');

        /**
         * Function: Soft Delete data (inactivation)
         */
        Route::get('dashboard/delete/user/{id}', [AdminController::class, 'softDeleteUser'])->name('user.delete');
        Route::get('dashboard/delete/broker/{api_key}', [AdminController::class, 'softDeleteBroker'])->name('broker.delete');
        Route::get('dashboard/delete/properties/{id}', [AdminController::class, 'softDeleteProperties'])->name('properties.delete');
        Route::get('dashboard/delete/house/{id}', [AdminController::class, 'softDeleteHouse'])->name('house.delete');

        /**
         ** Archives
         */
        Route::get('dashboard/archive', [AdminController::class, 'archiveDashboard'])->name('archive');
        Route::get('dashboard/archive/user', [AdminController::class, 'archiveDashboard_User'])->name('archive.user');
        Route::get('dashboard/archive/broker', [AdminController::class, 'archiveDashboard_Broker'])->name('archive.broker');
        Route::get('dashboard/archive/properties', [AdminController::class, 'archiveDashboard_Properties'])->name('archive.properties');
        Route::get('dashboard/archive/houses', [AdminController::class, 'archiveDashboard_Houses'])->name('archive.houses');

        /**
         * Function: Restore data from Archives
         */
        Route::post('dashboard/user/{id}/restore', [AdminController::class, 'restoreUser'])->name('user.restore');
        Route::post('dashboard/broker/{api_key}/restore', [AdminController::class, 'restoreBroker'])->name('broker.restore');
        Route::post('dashboard/properties/{id}/restore', [AdminController::class, 'restoreProperties'])->name('properties.restore');
        Route::post('dashboard/house/{id}/restore', [AdminController::class, 'restoreHouse'])->name('house.restore');

        /**
         * Function: Hard delete data from Archives
         */
        Route::post('dashboard/user/{id}/force_delete', [AdminController::class, 'forceDeleteUser'])->name('user.force_delete');
        Route::post('dashboard/broker/{api_key}/force_delete', [AdminController::class, 'forceDeleteBroker'])->name('broker.force_delete');
        Route::post('dashboard/properties/{id}/force_delete', [AdminController::class, 'forceDeleteProperties'])->name('properties.force_delete');
        Route::post('dashboard/house/{id}/force_delete', [AdminController::class, 'forceDeleteHouse'])->name('house.force_delete');
    });

    Route::get('user/dashboard/updated/models', [ModelController::class, 'index']);
    /**
     * Users Dashboard
     */
    Route::get('user/dashboard/home', [UserController::class, 'userDashboard'])->name('user.home');
    Route::get('user/dashboard/broker', [UserController::class, 'brokerDashboard'])->name('user.brokers');
    Route::get('user/dashboard/properties', [UserController::class, 'propertyDashboard'])->name('user.properties');
    Route::get('user/dashboard/houses/{api_key}', [UserController::class, 'houseDashboard'])->name('user.houses');

    /**
     * Profile: Users
     */
    Route::get('user/profile', [ProfileController::class, 'userProfile'])->name('user.profile');
    Route::post('user/profile/update', [ProfileController::class, 'updateUserProfile'])->name('user.profile.update');


    /**
     * User Function: View data & Global Scope
     */
    Route::get('user/dashboard/view/house/{property_ID}', [UserController::class, 'viewHouse'])->name('user.house.view');

    /**
     * Function: Soft Delete data (inactivation)
     */
    Route::get('user/dashboard/broker/{api_key}/delete', [UserController::class, 'softDeleteBroker'])->name('user.broker.delete');
    Route::get('user/dashboard/properties/{id}/delete', [UserController::class, 'softDeleteProperties'])->name('user.properties.delete');
    Route::get('user/dashboard/house/{id}/delete', [UserController::class, 'softDeleteHouse'])->name('user.house.delete');

    /**
     ** Sending API Data to DB (using .ENV variables) 
     */
    Route::get('user/dashboard/add/broker', [DashboardController::class, 'addBroker'])->name('user.broker.add');
    Route::post('user/dashboard/store/broker', [DashboardController::class, 'storeBroker'])->name('user.broker.store');
    Route::get('user/dashboard/edit/broker/{api_key}', [DashboardController::class, 'editBroker'])->name('user.broker.edit');
    Route::post('user/dashboard/update/broker/{api_key}', [DashboardController::class, 'updateBroker'])->name('user.broker.update');

    /**
     ** HTTP API COLLECTIONS: /api/collections?api=$api_key
     */
    Route::get('/api/collections/{api_key}', [XMLController::class, 'showAllData'])->name('collections.view');
    Route::get('/new/api/collections/{api_key}', [XMLController::class, 'mergeAllData'])->name('collections.store');
    Route::get('/save/api/collections/{api_key}', [XMLController::class, 'saveAllData'])->name('collections.save');

    /**
     ** Activating Scheduler (Daily Cronjob)
     */
    Route::get('/scheduler/activate/{api_key}', [CollectionsController::class, 'index'])->name('scheduler.activate');
    Route::get('/cronjob/activate', [CollectionsController::class, 'activateCronjob'])->name('cronjob.activate');
});
