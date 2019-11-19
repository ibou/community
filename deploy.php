<?php

namespace Deployer;

require 'recipe/symfony4.php';

// Project name
set('application', 'dev_community');

// Project repository
set('repository', 'git@github.com:ibou/community.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);
set('keep_releases', 3);
// Shared files/dirs between deploys
add('shared_files', ['.env']);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);


host('root@51.38.234.212')
    ->set('deploy_path', '/opt/www/{{application}}');

// Tasks

task('build', function () {
    run('cd {{release_path}} && APP_ENV=prod composer install --no-dev --optimize-autoloader');
    run('cd {{release_path}} && npm install');
    run('cd {{release_path}} && ./node_modules/.bin/encore production');
    // run('cd {{release_path}} && composer install --no-dev --optimize-autoloader && npm install && ./node_modules/.bin/encore production');
});


// Migrate database before symlink new release.
task('database:migrate', function () {
    run('{{bin/php}} {{bin/console}} doctrine:schema:update --force');
})->desc('Migrate database');

// before('deploy:symlink', 'database:migrate');
task('tag_version', function () {
    run('echo ------------------ > version.txt');
    run('echo ------ `TZ="Europe/Paris" date -R` ------ >> version.txt');
    run('echo ------------------ >> version.txt');
    run('echo ---------COUCOU IBOU---------');

    run('echo "Author Name  : `git config --get user.name`" >> version.txt');
    run('echo "Author Email : `git config --get user.email`" >> version.txt');
    run('echo "Author Machine : `hostname -f`" >> version.txt');
    run('echo "Author IP : `hostname -I`" >> version.txt');
    run('echo "Revision : `git name-rev --name-only $(git rev-parse HEAD)`" >> version.txt');
    run('git log --pretty=format:"%h%x09%an%x09%ad%x09%s" | head -n 5 >> version.txt');
})->local();

task('upload', function () {
    upload(__DIR__ . '/version.txt', '{{release_path}}');
});
task('release', [
    'build',
    'tag_version',
    'upload',
]);

after('deploy:symlink', 'release');
task('database:migrate', function () {
    run('{{bin/console}} doctrine:schema:update --force');
    // run('{{bin/console}} elastic:reindex');
})->desc('Migrate database');
// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
after('deploy:symlink', 'database:migrate');
