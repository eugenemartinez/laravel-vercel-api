<?php

// Vercel entry point for the Laravel application.
// This script is executed by Vercel, and its job is to correctly
// bootstrap the Laravel application.

// The root of the Laravel project.
// Assumes this api/index.php file is in project_root/api/index.php
$projectRoot = dirname(__DIR__);

// Change the current working directory to the Laravel project root.
// This is crucial so that Laravel's own index.php and subsequent
// bootstrapping (like loading .env, config files, vendor/autoload.php)
// work as expected.
chdir($projectRoot);

// Now, require Laravel's actual public entry point.
// All paths within Laravel's public/index.php will be resolved
// relative to the project root (due to the chdir above).
require $projectRoot . '/public/index.php';
