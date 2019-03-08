<?php

namespace Deployer;

require 'recipe/symfony4.php';

// Project name
set('application', 'deployed_community');

// Project repository
set('repository', 'git@github.com:ibou/community.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);
set('keep_releases', 3);
// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
 add('writable_dirs', []);

// Hosts
// set('http_user', 'www-data');
// set('writable_mode', 'chmod');

host('root@51.38.234.212')
    ->set('deploy_path', '/var/www/{{application}}');

// Tasks

task('build', function () {
    run('cd {{release_path}} && composer install --no-dev --optimize-autoloader && npm install && ./node_modules/.bin/encore production');
});
desc('Clear cache bis');
task('deploy:cache2', function () {
    run('APP_ENV=prod APP_DEBUG=0 {{bin/console}} cache:clear --no-warmup');
});


// Migrate database before symlink new release.
task('database:migrate', function () {
    run('{{bin/php}} {{bin/console}} doctrine:schema:update --force');
})->desc('Migrate database');

before('deploy:symlink', 'database:migrate');


task('runbuild', [
    'build',
    'deploy:cache2',
]);
after('deploy:symlink', 'runbuild');
// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');