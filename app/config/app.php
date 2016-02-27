<?php
return array(

	/*
	|--------------------------------------------------------------------------
	| Application Debug Mode
	|--------------------------------------------------------------------------
	|
	| When your application is in debug mode, detailed error messages with
	| stack traces will be shown on every error that occurs within your
	| application. If disabled, a simple generic error page is shown.
	|
	*/

	'debug' => false,

	/*
	|--------------------------------------------------------------------------
	| Application URL
	|--------------------------------------------------------------------------
	|
	| This URL is used by the console to properly generate URLs when using
	| the Artisan command line tool. You should set this to the root of
	| your application so that it is used when running Artisan tasks.
	|
	*/

	'url' => 'http://navitasys.eu',

	/*
	|--------------------------------------------------------------------------
	| Application Timezone
	|--------------------------------------------------------------------------
	|
	| Here you may specify the default timezone for your application, which
	| will be used by the PHP date and date-time functions. We have gone
	| ahead and set this to a sensible default for you out of the box.
	|
	*/

	'timezone' => 'UTC',

	/*
	|--------------------------------------------------------------------------
	| Application Locale Configuration
	|--------------------------------------------------------------------------
	|
	| The application locale determines the default locale that will be used
	| by the translation service provider. You are free to set this value
	| to any of the locales which will be supported by the application.
	|
	*/

	'locale' => 'en',

	/*
	|--------------------------------------------------------------------------
	| Encryption Key
	|--------------------------------------------------------------------------
	|
	| This key is used by the Illuminate encrypter service and should be set
	| to a random, long string, otherwise these encrypted values will not
	| be safe. Make sure to change it before deploying any application!
	|
	*/

	'key' => 'RkouFKiXZEmz2kiPEONAkQrb1EmUVbU8',

	/*
	|--------------------------------------------------------------------------
	| Autoloaded Service Providers
	|--------------------------------------------------------------------------
	|
	| The service providers listed here will be automatically loaded on the
	| request to your application. Feel free to add your own services to
	| this array to grant expanded functionality to your applications.
	|
	*/

    'providers' => array(
        /* Laravel Base Providers */
		'Illuminate\Foundation\Providers\ArtisanServiceProvider',
		'Illuminate\Auth\AuthServiceProvider',
		'Illuminate\Cache\CacheServiceProvider',
		'Illuminate\Foundation\Providers\CommandCreatorServiceProvider',
		'Illuminate\Session\CommandsServiceProvider',
		'Illuminate\Foundation\Providers\ComposerServiceProvider',
		'Illuminate\Routing\ControllerServiceProvider',
		'Illuminate\Cookie\CookieServiceProvider',
		'Illuminate\Database\DatabaseServiceProvider',
		'Illuminate\Encryption\EncryptionServiceProvider',
		'Illuminate\Filesystem\FilesystemServiceProvider',
		'Illuminate\Hashing\HashServiceProvider',
		'Illuminate\Html\HtmlServiceProvider',
		'Illuminate\Foundation\Providers\KeyGeneratorServiceProvider',
		'Illuminate\Log\LogServiceProvider',
		'Illuminate\Mail\MailServiceProvider',
		'Illuminate\Foundation\Providers\MaintenanceServiceProvider',
		'Illuminate\Database\MigrationServiceProvider',
		'Illuminate\Foundation\Providers\OptimizeServiceProvider',
		'Illuminate\Pagination\PaginationServiceProvider',
		'Illuminate\Foundation\Providers\PublisherServiceProvider',
		'Illuminate\Queue\QueueServiceProvider',
		'Illuminate\Redis\RedisServiceProvider',
		'Illuminate\Auth\Reminders\ReminderServiceProvider',
		'Illuminate\Foundation\Providers\RouteListServiceProvider',
		'Illuminate\Database\SeedServiceProvider',
		'Illuminate\Foundation\Providers\ServerServiceProvider',
		'Illuminate\Session\SessionServiceProvider',
		'Illuminate\Foundation\Providers\TinkerServiceProvider',
		'Illuminate\Translation\TranslationServiceProvider',
		'Illuminate\Validation\ValidationServiceProvider',
		'Illuminate\View\ViewServiceProvider',
		'Illuminate\Workbench\WorkbenchServiceProvider',
		'Illuminate\Remote\RemoteServiceProvider',
		
        /* Additional Providers */
        'Zizaco\Confide\ServiceProvider', // Confide Provider
        'Zizaco\Entrust\EntrustServiceProvider', // Entrust Provider for roles
        'Basset\BassetServiceProvider',

        //'Barryvdh\DomPDF\ServiceProvider',
        'Barryvdh\Snappy\ServiceProvider',

        'Barryvdh\Debugbar\ServiceProvider',
        'Barryvdh\Elfinder\ElfinderServiceProvider',
		'Jenssegers\Agent\AgentServiceProvider',
        'Jenssegers\Raven\RavenServiceProvider',
        /*'Websocket\Pods\PodsServiceProvider',*/
        /* Uncomment for use in development */
        'Daursu\Xero\XeroServiceProvider',
        'Lavary\Menu\ServiceProvider',
        'yajra\Datatables\DatatablesServiceProvider',

		'Tymon\JWTAuth\Providers\JWTAuthServiceProvider',
		'Firebase\Integration\Laravel\FirebaseServiceProvider',
//
//      'Way\Generators\GeneratorsServiceProvider', // Generators
//      'Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider', // IDE Helpers

		'Libraries\FormBuilder\FormBuilderServiceProvider',



    ),

    /*
    |--------------------------------------------------------------------------
    | Service Provider Manifest
    |--------------------------------------------------------------------------
    |
    | The service provider manifest is used by Laravel to lazy load service
    | providers which are not needed for each request, as well to keep a
    | list of all of the services. Here, you may set its storage spot.
    |
    */

    'manifest' => storage_path() . '/meta',

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => array(
        /* Laravel Base Aliases */
		'App'             => 'Illuminate\Support\Facades\App',
		'Artisan'         => 'Illuminate\Support\Facades\Artisan',
		'Auth'            => 'Illuminate\Support\Facades\Auth',
		'Blade'           => 'Illuminate\Support\Facades\Blade',
		'Cache'           => 'Illuminate\Support\Facades\Cache',
		'ClassLoader'     => 'Illuminate\Support\ClassLoader',
		'Config'          => 'Illuminate\Support\Facades\Config',
		'Controller'      => 'Illuminate\Routing\Controller',
		'Cookie'          => 'Illuminate\Support\Facades\Cookie',
		'Crypt'           => 'Illuminate\Support\Facades\Crypt',
		'DB'              => 'Illuminate\Support\Facades\DB',
		'Eloquent'        => 'Illuminate\Database\Eloquent\Model',
		'Event'           => 'Illuminate\Support\Facades\Event',
		'File'            => 'Illuminate\Support\Facades\File',
		'Form'            => 'Illuminate\Support\Facades\Form',
		'Hash'            => 'Illuminate\Support\Facades\Hash',
		'HTML'            => 'Illuminate\Support\Facades\HTML',
		'Input'           => 'Illuminate\Support\Facades\Input',
		'Lang'            => 'Illuminate\Support\Facades\Lang',
		'Log'             => 'Illuminate\Support\Facades\Log',
		'Mail'            => 'Illuminate\Support\Facades\Mail',
		'Paginator'       => 'Illuminate\Support\Facades\Paginator',
		'Password'        => 'Illuminate\Support\Facades\Password',
		'Queue'           => 'Illuminate\Support\Facades\Queue',
		'Redirect'        => 'Illuminate\Support\Facades\Redirect',
		'Redis'           => 'Illuminate\Support\Facades\Redis',
		'Request'         => 'Illuminate\Support\Facades\Request',
		'Response'        => 'Illuminate\Support\Facades\Response',
		'Route'           => 'Illuminate\Support\Facades\Route',
		'Schema'          => 'Illuminate\Support\Facades\Schema',
		'Seeder'          => 'Illuminate\Database\Seeder',
		'Session'         => 'Illuminate\Support\Facades\Session',
		'SSH'             => 'Illuminate\Support\Facades\SSH',
		'Str'             => 'Illuminate\Support\Str',
		'URL'             => 'Illuminate\Support\Facades\URL',
		'Validator'       => 'Illuminate\Support\Facades\Validator',
		'View'            => 'Illuminate\Support\Facades\View',

        /* Additional Aliases */
        'Confide'         => 'Zizaco\Confide\Facade', // Confide Alias
        'Entrust'         => 'Zizaco\Entrust\EntrustFacade', // Entrust Alias
        'String'          => 'Andrew13\Helpers\String', // String
        'Carbon'          => 'Carbon\Carbon', // Carbon
        'Basset'          => 'Basset\Facade',

        //'PDF'           => 'Barryvdh\DomPDF\Facade',
        'PDF'             => 'Barryvdh\Snappy\Facades\SnappyPdf',
        'Image'           => 'Barryvdh\Snappy\Facades\SnappyImage',

        'Debugbar'        => 'Barryvdh\Debugbar\Facade',
		'Agent'           => 'Jenssegers\Agent\Facades\Agent',
        'Raven'           => 'Jenssegers\Raven\Facades\Raven',

        'Menu'            => 'Lavary\Menu\Facade',
        'Datatables'      => 'yajra\Datatables\Datatables',

		'JWTAuth' 		  => 'Tymon\JWTAuth\Facades\JWTAuth',
		'JWTFactory'      => 'Tymon\JWTAuth\Facades\JWTFactory',
		'Firebase' 		  => 'Firebase\Integration\Laravel\Firebase',

		'FormExt'	  => 'Libraries\FormBuilder\FormBuilderFacade'
     ),

    'available_language' => array('en', 'pt', 'es'),

    'new_cleaning_schedules' => [
        'complete_limit' => [
            'days' => 1,
            'time' => '03:00:00']
    ],

    'backgrounds'=>[
        '/assets/images/backgrounds/1.jpg',
        '/assets/images/backgrounds/2.jpg',
        '/assets/images/backgrounds/3.jpg',
        '/assets/images/backgrounds/4.jpg',
        '/assets/images/backgrounds/5.jpg',
        '/assets/images/backgrounds/6.jpg',
        '/assets/images/backgrounds/7.jpg',
        '/assets/images/backgrounds/8.jpg',
        '/assets/images/backgrounds/9.jpg',
    ],
    'error' => [
        '403' => [
            'We couldn\'t find the page you requested on our servers. We\'re really sorry about that. It\'s our fault, not yours. We\'ll work hard to get this page back online as soon as possible.',
        ],
        '404' => [
            'We need a map.',
            'I think we\'re lost.',
            'We took a wrong turn.',
            'It\'s looking like you may have taken a wrong turn.<br />Don\'t worry... it happens to the best of us',
            'We couldn\'t find the page you requested on our servers. We\'re really sorry about that. It\'s our fault, not yours. We\'ll work hard to get this page back online as soon as possible.',
        ],
        '500' => [
            'Something went wrong on our servers while we were processing your request. We\'re really sorry about this, and will work hard to get this resolved as soon as possible.',
        ],
    ],
);