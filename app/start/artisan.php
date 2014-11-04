<?php

/*
|--------------------------------------------------------------------------
| Register The Artisan Commands
|--------------------------------------------------------------------------
|
| Each available Artisan command must be registered with the console so
| that it is available to be called. We'll register every command so
| the console gets access to each of the command object instances.
|
*/
Artisan::add(new App\Core\Commands\MakeAdminCommand());
Artisan::add(new App\Core\Commands\GenerateConfigsCommand());
Artisan::add(new App\Core\Commands\CreateDummyUsersCommand());
Artisan::add(new App\Core\Commands\CreateRoleConsumerCommand());
Artisan::add(new App\Core\Commands\FixMissingRoleCommand());
Artisan::add(new App\Core\Commands\IndexExistingBusinessCommand());
Artisan::add(new App\Cart\Commands\UnlockCartItemsCommand());
