<?php

namespace Deployer;

require 'recipe/laravel.php';

// Config
set('repository', 'git@github.com:nsommer89/joys.dk.git');

add('shared_files', []);
add('shared_dirs', [
    'storage',
]);
add('writable_dirs', [
    'bootstrap/cache',
    'storage',
]);

// Hosts
host('joys.dk')
    ->set('remote_user', 'joys')
    ->set('deploy_path', '/var/www/joys.dk');

// Custom Tasks
task('artisan:filament:upgrade', function () {
    run('{{bin/php}} {{release_path}}/artisan filament:upgrade');
});

// Tasks
task('build', function () {
    cd('{{release_path}}');
    run('npm install');
    run('npm run build');
});

desc('Deploy your project');
task('deploy', [
    'deploy:prepare',
    'deploy:vendors',
    'artisan:storage:link',
    'artisan:migrate',
    'artisan:optimize',
    'artisan:filament:upgrade',
    'build',
    'deploy:publish',
]);

// Hooks
after('deploy:failed', 'deploy:unlock');

// Restart Horizon after deployment
task('horizon:restart', function () {
    run('{{bin/php}} {{release_path}}/artisan horizon:terminate');
});
after('deploy:publish', 'horizon:restart');
