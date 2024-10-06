<?php

use Lib\Route;
use PHPUnit\Framework\TestCase;
use App\Middleware\RoleMiddleware;
use App\Models\User;
use Mockery as m;

class RouteTest extends TestCase
{
    /**
     * Configura el entorno de prueba antes de ejecutar cada test.
     */
    protected function setUp(): void
    {
        // Definir la constante BASE_URL
        if (!defined('BASE_URL')) {
            define('BASE_URL', getenv('BASE_URL'));
        }

        // Iniciar la sesión si no está iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        Route::resetRoutes();
    }

    /**
     * Cierra la sesión y cierra Mockery después de cada test.
     */
    protected function tearDown(): void
    {
        m::close();
    }

    /**
     * Test para registrar una ruta GET.
     */
    public function testGetRouteRegistration()
    {
        Route::get('/test', function () {
            return 'test';
        });

        $routes = $this->getRoutes();

        $this->assertArrayHasKey('GET', $routes);
        $this->assertArrayHasKey('test', $routes['GET']);
    }

    /**
     * Test para registrar una ruta POST.
     */
    public function testPostRouteRegistration()
    {
        Route::post('/test', function () {
            return 'test';
        });

        $routes = $this->getRoutes();

        $this->assertArrayHasKey('POST', $routes);
        $this->assertArrayHasKey('test', $routes['POST']);
    }

    /**
     * Test para registrar una ruta PUT.
     */
    public function testPutRouteRegistration()
    {
        Route::put('/test', function () {
            return 'test';
        });

        $routes = $this->getRoutes();

        $this->assertArrayHasKey('PUT', $routes);
        $this->assertArrayHasKey('test', $routes['PUT']);
    }

    /**
     * Test para registrar una ruta DELETE.
     */
    public function testDeleteRouteRegistration()
    {
        Route::delete('/test', function () {
            return 'test';
        });

        $routes = $this->getRoutes();

        $this->assertArrayHasKey('DELETE', $routes);
        $this->assertArrayHasKey('test', $routes['DELETE']);
    }

    /**
     * Test para despachar una ruta.
     */
    public function testDispatchRoute()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/test';

        Route::get('/test', function () {
            return 'test';
        });

        ob_start();
        try {
            Route::dispatch();
            $output = ob_get_clean();
        } catch (\Exception $e) {
            while (ob_get_level() > 0) {
                ob_end_clean();
            }
            throw $e;
        }

        $this->assertEquals('test', $output);
    }

    /**
     * Test para manejar una ruta no encontrada.
     */
    public function testRouteNotFound()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/not-found';

        // Registrar una ruta que no se utilizará
        Route::get('/unused-route', function () {
            return 'unused';
        });

        ob_start();
        try {
            Route::dispatch();
            $output = ob_get_clean();
        } catch (\Exception $e) {
            while (ob_get_level() > 0) {
                ob_end_clean();
            }
            throw $e;
        }

        $this->assertEquals('404 - Not Found', $output);
    }

    /**
     * Obtiene las rutas registradas.
     *
     * @return array Las rutas registradas.
     */
    private function getRoutes()
    {
        return Route::getRegisteredRoutes();
    }
}
