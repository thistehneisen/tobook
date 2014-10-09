from fabric.api import cd, run, local, task, hosts, env
import os

HOME = os.getenv('HOME')


def _deploy():
    env.user = 'root'
    with cd('/srv/varaa/src'):
        # pull latest source
        run('git pull')
        # install dependencies
        run('composer install')
        # run migration
        run('php artisan migrate')
        for mod in ['co', 'modules', 'as', 'fd', 'mt']:
            run('php artisan migrate --path=app/database/migrations/{}'.format(
                mod
            ))
        # chmod storage again
        run('chmod -Rf 777 app/storage')


@task(alias='ds')
@hosts('dev.varaa.co')
def deploy_stag():
    _deploy()


@task
@hosts('klikkaaja.com')
def deploy_prod():
    _deploy()


@task(alias='rc')
@hosts('dev.varaa.co')
def run_command(command='ls'):
    '''
    Run any command in server
    '''
    env.user = 'root'
    with cd('/srv/varaa/src'):
        run(command)


@task(alias='ss')
def sync_server_settings():
    '''
    Sync the latest templates to the server app
    '''
    local(
        'ansible-playbook devops/app.yml -i devops/hosts --ask-vault-pass '
        '--tags templated_settings'
    )


@task(alias='t')
def test(suite='', group=''):
    '''
    Run the test suite
    Usage:
        fab t => run all tests (including unit, functional, acceptance and API)
        fab t:suite_name,module_name => run tests in specific suite and module
        Example:
            fab t:unit => run all unit tests
            fab t:unit,as => run all AS unit tests
            fab t:,as => run all AS tests
    '''
    if group != '':
        group = '-g {}'.format(group)
    local('./vendor/bin/codecept run {} {}'.format(suite, group))


@task(alias='cm')
def create_migrate(table='table'):
    local('php artisan migrate:make create_{} --create={}'.format(table))


@task(alias='m')
def migrate(module=''):
    '''
    Run migration
    '''
    if module == '':
        local('php artisan migrate')
    else:
        local(
            'php artisan migrate --path=app/database/migrations/{}'
            .format(module)
        )
