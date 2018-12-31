<?php

namespace Deployer;

require 'recipe/symfony4.php';

// Project name
set('application', 'deployed_community');

// Project repository
set('repository', 'git@github.com:ibou/community.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);

// Hosts

host('root@51.77.201.108')
    ->set('deploy_path', '/home/debian/ww-dev/{{application}}');

// Tasks

task('build', function () {
    run('cd {{release_path}} && composer install --no-dev --optimize-autoloader && npm install && ./node_modules/.bin/encore production');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.
// Migrate database before symlink new release.
task('database:migrate', function () {
    run('{{bin/php}} {{bin/console}} doctrine:schema:update --force {{console_options}}');
})->desc('Migrate database');

before('deploy:symlink', 'database:migrate');
