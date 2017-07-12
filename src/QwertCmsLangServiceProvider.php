<?php 
namespace alooze\QwertCms;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

/**
 * Class QwertCmsLangServiceProvider
 * @package alooze\QwertCms
 */
class QwertCmsLangServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Настройки
         */
        // $this->publishes([
        //     __DIR__ . '/config/qwertcms.php' => base_path('config/qwertcms.php'),
        // ], 'qwertcms_config');

        /**
         * Роуты
         */
        $this->publishes([
            __DIR__ . '/routes' => base_path('routes/'),
        ], 'ql_routes');

        /**
         * Шаблоны для админки
         */
        // $this->loadViewsFrom(__DIR__.'/views', 'qwertcms-lang');

        $this->publishes([
            __DIR__ . '/public' => public_path('vendor/qwertcms-lang/'),
        ], 'ql_public');

        $this->publishes([
            __DIR__.'/views'  => base_path('resources/views/admin/'),
        ], 'ql_view');

        /**
         * Миграции и seeds
         */
        $this->publishes([
            __DIR__ . '/migrations/' => database_path('migrations/')
        ], 'ql_migrations');

        // $this->publishes([
        //     __DIR__ . '/database/seeds/' => database_path('seeds')
        // ], 'qwertcms_seeds');

        /**
         * Базовые контроллеры
         */
        $this->publishes([
            __DIR__ . '/controllers/' => app_path('Http/controllers/Admin')
        ], 'ql_controllers');

        /**
         * Модели
         */
        $this->publishes([
            __DIR__ . '/Models/' => app_path()
        ], 'ql_models');

        /**
         * Функции хелперы
         */
        $this->publishes([
            __DIR__ . '/Helpers/' => app_path('Helpers')
        ], 'ql_helpers');

        /**
         * Языковые файлы
         */        
        // $this->publishes([
        //     __DIR__ . '/lang/adminlte/ru' => base_path('resources/lang/vendor/adminlte/ru/')
        // ], 'qwertcms_adminlang');
        // $this->publishes([
        //     __DIR__ . '/lang/laravel/ru' => base_path('resources/lang/ru/')
        // ], 'qwertcms_lang');

        /**
         * Middleware для мультиязычности
         */
        // $this->publishes([
        //     __DIR__ . '/Middleware' => app_path('Http/Middleware/')
        // ], 'qwertcms_lang');
        
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('qwertcms-lang', function () {
            return true;
        });
    }
}
