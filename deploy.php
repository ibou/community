<?php

namespace Deployer;

require 'recipe/symfony4.php';

// Project name
set('application', 'deployed_community');

// Project repository
set('repository', 'git@github.com:ibou/community.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

set('ssh_type', 'native');
set('ssh_multiplexing', true);
set('symfony_env', 'prod');
set('shared_dirs', ['var/log', 'var/sessions']);
set('shared_files', ['.env']);
// set('writable_dirs', ['var']);
set('bin_dir', 'bin');
set('var_dir', 'var');
set('keep_releases', 2);
set('bin/console', function () {
    return sprintf('{{release_path}}/%s/console', trim(get('bin_dir'), '/'));
});
set('console_options', function () {
    $options = '--no-interaction --env={{symfony_env}}';

    return get('symfony_env') !== 'prod' ? $options : sprintf('%s --no-debug', $options);
});
set('env', function () {
    return [
        'SYMFONY_ENV' => get('symfony_env'),
    ];
});
set('clear_paths', [
    '.git',
    '.gitignore',
]);

set('http_user', 'www-data');
set('writable_mode', 'chmod');
// Hosts

host('root@51.77.201.108')
    ->set('deploy_path', '/home/debian/ww-dev/{{application}}');

// Tasks

task('build', function () {
    run('cd {{release_path}} && composer install --no-dev --optimize-autoloader && npm install && ./node_modules/.bin/encore production');
});

after('deploy:update_code', 'deploy:clear_paths');
after('deploy:vendors', 'deploy:writable');
// after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.
task('database:migrate', function () {
    run('{{bin/php}} {{bin/console}} doctrine:schema:update --force {{console_options}}');
})->desc('Migrate database');

task('runbuild', [
    'build',
]);
after('deploy:vendors', 'runbuild');
before('deploy:symlink', 'database:migrate');
