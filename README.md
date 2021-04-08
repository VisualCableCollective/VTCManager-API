# VTCManager API
This is the source code of the VTCManager API server. It is based on the [Laravel PHP Framework](https://laravel.com/).

## Setting up the API for development
1. Make sure that the latest version of [PHP Composer](https://getcomposer.org/) is installed.
2. Install the composer packages by running `composer install` in the API project directory.
3. After that, create a copy of the [.env.example](.env.example) and rename it to `.env`.
4. Then open the `.env` file and write your databse credentials in all variables with a `DB` prefix. More information can be found [here](https://laravel.com/docs/8.x/database).
5. Now you have to write the oauth credentials in all variables with a `VCC_CLIENT` or `VCC_VTCM_CLIENT` prefix. The oauth credentials can be found in the VCC cloud.
6. Now you are ready to start the server locally. Run `php artisan serve` in one terminal window and `php artisan websockets:serve` in another terminal window.
