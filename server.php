<?php
// Simple PHP development server configuration
$port = 8080;
$host = '0.0.0.0';
$documentRoot = __DIR__;

echo "Starting PHP development server...\n";
echo "Server: http://{$host}:{$port}\n";
echo "Document root: {$documentRoot}\n";

// Start the built-in PHP server
$command = "php -S {$host}:{$port} -t {$documentRoot}";
passthru($command);
?>