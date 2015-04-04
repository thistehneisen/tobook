from fabric.api import cd, run, local, task, hosts, env, settings
from fabric import utils
import os
import subprocess
import threading


def _deploy(environment, host):
    env.user = 'root'
    with settings(host_string=host):
        with cd('/srv/varaa/src'):
            # set it to maintenance mode
            run('php artisan down')
            # pull latest source
            branch = 'develop' if environment == 'stag' else 'master'
            run('git pull origin {}'.format(branch))
            # install dependencies
            run('composer install')
            # run migration
            run('php artisan migrate --env={}'.format(environment))
            # run seeder
            run('php artisan db:seed')
            # chmod storage again
            run('chmod -Rf 777 app/storage')
            # clear all application cache
            run('php artisan cache:clear')
            # Connect incomplete consumers account
            # This is run only once and will be removed in next release
            run('php artisan varaa:connect-consumers')
            # restart supervisor processes
            run('supervisorctl restart all')
            # set it to live mode again
            run('php artisan up')


@task
def deploy(instance=''):
    instance_dict = {
        'stag': '46.101.49.100',
        'prod': '178.62.37.23',
        'clearbooking': '178.62.52.193',
        'tobook': '188.166.43.60'
    }
    if instance in instance_dict:
        _deploy(instance, instance_dict[instance])
    elif instance == 'all':
        for inst in instance_dict:
            _deploy(inst, instance_dict[inst])


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
def test(suite='', group='', debug=''):
    '''
    Run the test suite
    Usage:
        fab t => run all tests (including unit, functional, acceptance and API)
        fab t:suite,module,debug=> run tests in specific suite and module
        Example:
            fab t:unit => run all unit tests
            fab t:unit,as => run all AS unit tests
            fab t:,as => run all AS tests
            fab t:unit,,1 => run all unit tests in debug mode
    '''
    if group != '':
        group = '-g {}'.format(group)
    if debug != '':
        debug = '--debug -vvv'
    # always run migrate before test
    migrate('testing')
    # rebuild the tester classes first
    local('./vendor/bin/codecept build')
    # then run the tests
    local('./vendor/bin/codecept run {} {} {}'.format(suite, group, debug))


@task(alias='cm')
def create_migrate(table='table'):
    local('php artisan migrate:make create_{} --create={}'.format(table))


@task(alias='m')
def migrate(environment='local'):
    '''
    Run migration
    '''
    local('php artisan migrate --env={}'.format(environment))
    if environment == 'testing':
        local('php artisan db:seed --class=TestingSeeder --env=testing')


@task(alias='cs')
def code_sniffer(path='app'):
    '''
    Run PHP Code Sniffer
    '''
    local('./vendor/bin/phpcs -s --standard=./ruleset.xml {}'.format(path))


@task(alias='f')
def fix(path='app'):
    '''
    Run PHP Code Sniffer Fixer
    '''
    local('./vendor/bin/php-cs-fixer fix {}'.format(path))


@task(alias='i')
def index_business(command='index'):
    '''
    Shortcut for command index business
    '''
    local('php artisan varaa:es-index-business {}'.format(command))


def _which(bin):
    path, err = subprocess.Popen(
        ['which', bin], stdout=subprocess.PIPE
    ).communicate()
    return path


@task(alias='ta')
def test_acceptance_prepare(headless=1):
    '''
    Prepare environment to run acceptance tests.
    Usage:
        fab ta => download and run Xvfb and Selenium (headless server)
        fab ta:0 => setup Selenium only
    '''
    # if _which('firefox') == '':
    #     utils.abort(
    #         'Please install Firefox\nIf you are using Ubuntu/Homestead,'
    #         ' execute `sudo apt-get install firefox -y`'
    #     )

    if _which('java') == '':
        utils.abort(
            'Please install Java Runtime\nIf you are using Ubuntu/Homestead,'
            ' execute `sudo apt-get install default-jre -y`'
        )

    if headless == 1:
        if _which('Xvfb') == '':
            utils.abort(
                'Please install Xvfb\nIf you are using Ubuntu/Homestead,'
                ' execute `sudo apt-get install Xvfb -y`\nRun `fab ta:0`'
                ' if you are not testing with a headless server.'
            )

        if os.getenv('DISPLAY') == '':
            utils.abort(
                'Please pick a diplay ID (for example, 10) and execute'
                ' `export DISPLAY=:10` before running `fab ta`'
            )

    ver = '2.44'
    output = 'vendor/bin/selenium-{}.jar'.format(ver)
    if os.path.isfile(output) is False:
        if _which('curl') != '':
            local(
                'curl http://selenium-release.storage.googleapis.com/'
                '{}/selenium-server-standalone-{}.0.jar -o {}'.format(
                    ver, ver, output
                )
            )
        elif _which('wget') != '':
            local(
                'wget http://selenium-release.storage.googleapis.com/'
                '{}/selenium-server-standalone-{}.0.jar -O {}'.format(
                    ver, ver, output
                )
            )

    if headless == 1:
        local('Xvfb {} -ac &'.format(os.getenv('DISPLAY')))

    local('java -jar {}'.format(output))


@task(alias='r')
def run_all():
    threads = map(
        lambda x: threading.Thread(target=x), (
            lambda: local('gulp'),
            lambda: local('elasticsearch'),
            lambda: local('php artisan queue:listen --tries=5'),
            lambda: local('redis-server'),
            test_acceptance_prepare
        )
    )
    # Start
    [x.start() for x in threads]
    # Wait for Ctrl+C
    [x.join() for x in threads]
