Route::get('github/auth/login', [LoginController::class, 'redirectToProvider']);

Route::get('github/auth/callback', [LoginController::class,'handleProviderCallback']);
