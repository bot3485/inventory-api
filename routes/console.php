<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your closure based console
| commands. Each closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

/**
 * Schedule the pruning of expired Sanctum tokens.
 * This will run daily to keep the 'personal_access_tokens' table clean.
 */
Schedule::command('sanctum:prune-expired --hours=24')->daily();

/**
 * Optional: Schedule the pruning of old activity logs.
 * Since you use spatie/laravel-activitylog, you might want to clean
 * logs older than 30 days to prevent database bloating.
 */
Schedule::command('activitylog:clean')->daily();
