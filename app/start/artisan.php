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
Artisan::add(new App\Core\Commands\FixEscapedCharactersCommand());
Artisan::add(new App\Core\Commands\IndexExistingBusinessCommand());
Artisan::add(new App\Core\Commands\BackupDatabaseCommand());
Artisan::add(new App\Core\Commands\MoveBusinessDescriptionCommand());
Artisan::add(new App\Core\Commands\ConnectConsumersCommand());
Artisan::add(new App\Core\Commands\MappingServicesMasterCategories());

Artisan::add(new App\Cart\Commands\UnlockCartItemsCommand());

Artisan::add(new App\Appointment\NAT\Commands\ScheduledBuild());

Artisan::add(new App\Search\Commands\BuildSearchIndecesCommand());

Artisan::add(new App\Consumers\Commands\MergeComsumersCommand());

Artisan::add(new App\LoyaltyCard\Commands\MoveLcCommand());
