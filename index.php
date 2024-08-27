<?php
require_once 'vendor/autoload.php'; // Composer autoload
require_once 'lib/Env.php'; // Environment variables
require_once 'lib/helpers.php'; // Helper functions
require_once 'lib/ErrorHandler.php'; // Error handler
require_once 'lib/Logger.php'; // Logger

// Set custom error and exception handlers
set_error_handler(['Lib\ErrorHandler', 'handleError']);
set_exception_handler(['Lib\ErrorHandler', 'handleException']);
register_shutdown_function(['Lib\ErrorHandler', 'handleShutdown']);

require_once 'config/database.php'; // Database
require_once 'routes/web.php'; // Routes
