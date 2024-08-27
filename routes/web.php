<?php

use Lib\Route;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Middleware\RoleMiddleware;

/**
 * Login
 */

Route::get(
    '/login',
    [AuthController::class, 'showLoginForm']
);
Route::post(
    '/login',
    [AuthController::class, 'login']
);
Route::get(
    '/register',
    [AuthController::class, 'showRegisterForm']
);
Route::post(
    '/register',
    [AuthController::class, 'register']
);

Route::get(
    '/auth/logoutpopup',
    [AuthController::class, 'logoutpopup']
);
Route::post(
    '/logout',
    [AuthController::class, 'logout']
);

Route::get(
    '/success',
    [AuthController::class, 'success']
);
Route::get(
    '/auth/unauthorized',
    [AuthController::class, 'unauthorized']
);

/**
 * Password Reset
 */

Route::get(
    '/password/email',
    [AuthController::class, 'showResetRequestForm']
);
Route::post(
    '/password/email',
    [AuthController::class, 'sendResetLinkEmail']
);
Route::get(
    '/password/reset/{token}',
    [AuthController::class, 'showResetForm']
);
Route::post(
    '/password/reset',
    [AuthController::class, 'resetPassword']
);

/**
 * HomeController
 */
Route::get(
    '/',
    [HomeController::class, 'index'],
    [RoleMiddleware::class => ['admin']]
);

Route::get(
    '/home',
    [HomeController::class, 'admin'],
    [RoleMiddleware::class => ['admin']]
);

Route::dispatch();
