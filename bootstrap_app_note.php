<?php
/**
 * Dans bootstrap/app.php de votre projet Laravel, ajoutez :
 *
 *   ->withMiddleware(function (Middleware $middleware) {
 *       $middleware->alias([
 *           'role' => \App\Http\Middleware\CheckRole::class,
 *       ]);
 *   })
 *
 * Cela permet d'utiliser ->middleware('role:administrateur') dans les routes.
 */
