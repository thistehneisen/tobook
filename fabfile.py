from fabric.api import cd, run, local, task, hosts, env, settings
from fabric.colors import red, blue
from fabric.contrib import files
from fabric import utils
import os
import subprocess
import threading
import random
import semver

def get_random_word(wordLen):
    word = ''
    for i in range(wordLen):
        word += random.choice('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789')
    return word

@task
def check_branch(instance=''):
    env.user = 'root'
    instance_dict = {
        'stag': '46.101.49.100',
        'prod': '178.62.37.23',
        'clearbooking': '178.62.52.193',
        'tobook': '178.62.41.125',
        'yellowpage': '178.62.123.243'
    }
    if instance in instance_dict:
        host = instance_dict[instance]
        with settings(host_string=host):
            with cd('/srv/varaa/src'):
                current_branch = run('git symbolic-ref --short -q HEAD')
                print("Current branch on {0} is {1}".format(red(instance), blue(current_branch)))
    else:
        print(red("Instance not found!"))

def _deploy(environment, host):
    env.user = 'root'
    with settings(host_string=host):
        with cd('/srv/varaa/src'):
            # set it to maintenance mode
            run('php artisan down')
            # pull latest source
            branch = 'develop' if environment == 'stag' else 'master'
            run('git pull')
            #check the current branch on server
            current_branch = run('git symbolic-ref --short -q HEAD')
            run('git checkout {}'.format(branch))
            #if the new branch is different to current branch, we need to pull again
            if current_branch != branch:
                run('git pull')
            # update composer
            run('composer self-update')
            # install dependencies
            run('composer install')
            run('npm install')
            run('ENV={} npm run build'.format(environment))
            # run migration
            run('php artisan migrate --env={}'.format(environment))
            # run seeder
            run('php artisan db:seed')
            # chmod storage again
            run('chmod -Rf 777 app/storage')
            # chown uploads and tmp folder
            run('chown www-data:www-data public/tmp -R')
            run('chown www-data:www-data public/uploads -R')
            # chmod 644 for files, 755 for folder
            run('chmod ug+rwX,go+rX public/uploads -R')
            run('chmod ug+rwX,go+rX public/tmp -R')
            # clear all application cache
            run('php artisan cache:clear')
            #-------------------------------------------------------------------
            # These commands are run once and will be removed in next release
            #-------------------------------------------------------------------
            print(red('Deployment notes:', True))
            #-------------------------------------------------------------------
            # restart supervisor processes
            run('supervisorctl restart all')
            # set it to live mode again
            run('php artisan up')
            # notify everyone for fun
            run('php artisan varaa:deployed {} {}'.format(environment, branch))
            # run CI
            if environment == 'stag': run('/srv/phpci/console phpci:rebuild')

@task
def deploy(instance=''):
    instance_dict = {
        'stag': '46.101.49.100',
        'prod': '178.62.37.23',
        'clearbooking': '178.62.52.193',
        'tobook': '178.62.41.125',
        'yellowpage': '178.62.123.243'
    }
    if instance in instance_dict:
        _deploy(instance, instance_dict[instance])
    elif instance == 'all':
        for inst in instance_dict:
            _deploy(inst, instance_dict[inst])

@task
def bump_patch(branch):
    '''
    Merge the branch into `master` and `develop` and bump current release version
    '''
    # To make sure the branch exists
    local('git checkout {}'.format(branch))

    # Bump version
    current_version = read_version()
    bumped_version = semver.bump_patch(current_version)
    msg = 'Release v{}'.format(bumped_version)
    # Merge into `master`
    local('git checkout master')
    local('git merge --no-ff {} -m "{}"'.format(branch, msg))
    # Write VERSION
    write_version(bumped_version)
    # Add new version file
    local('git add VERSION')
    local('git commit --amend -m "{}"'.format(msg))
    # Create a tag
    local('git tag -a v{} -m "{}"'.format(bumped_version, msg))

    # Merge into `develop`
    local('git checkout develop')
    local('git merge --no-ff {} -m "Merge branch `{}`.\n{}"'.format(branch, branch, msg))

    print('Current version: '+red(bumped_version, True))

@task
def bump_minor():
    '''
    Merge `develop` into `master` and bump release version
    '''

    # Bump version
    current_version = read_version()
    bumped_version = semver.bump_minor(current_version)
    msg = 'Release v{}'.format(bumped_version)

    # Merge into `master`
    local('git checkout master')
    local('git pull --rebase')
    local('git merge --no-ff develop -m "{}"'.format(msg))
    # Write VERSION
    write_version(bumped_version)
    # Add new version file
    local('git add VERSION')
    local('git commit --amend -m "{}"'.format(msg))
    # Create a tag
    local('git tag -a v{} -m "{}"'.format(bumped_version, msg))

    print('Current version: '+red(bumped_version, True))

def write_version(version):
    f = open('VERSION', 'w')
    f.write(version)
    f.close()

def read_version():
    f = open('VERSION')
    current_version = f.read().strip('\n')
    f.close()
    return current_version

@task
def new_server(name):
    print(red('We will run mysql_secure_installation. Take note of the MySQL root password below.'))
    mysql_root_password = get_random_word(16)
    print(red('Copy this new MySQL password: {}'.format(mysql_root_password)))
    run('cat /etc/motd.tail')
    run('mysql_secure_installation')
    if files.exists('~/.ssh/id_rsa.pub') == False:
        run('ssh-keygen')
        run('cat ~/.ssh/id_rsa.pub')

    print('Add that SSH key as deployment key on Github.')
    done = None
    while done != 'done':
        print(red('Enter [done] to continue:'))
        done = raw_input()

    print(blue('(Optional) Create a new API key in Mandrill'))
    # Add new host into the list
    replace = '{} ansible_ssh_host={} ansible_ssh_user={}'.format(name, env.host, env.user)
    hosts_path = 'devops/hosts'
    local("sed -i.bak 's:\[webservers\]:[webservers]\\n{}:g' {}".format(replace, hosts_path))
    local("sed -i.bak 's:\[dbservers\]:[dbservers]\\n{}:g' {}".format(replace, hosts_path))

    # Create ansible vault
    print("""
---------------------------------------------------------------------
We are going to create a new vault for the new server.
You will be asked a several questions.
So don't panic.
---------------------------------------------------------------------
""")
    print('Enter host name, e.g. tobook.lv')
    host_name = raw_input()
    print('Enter Mandrill API key')
    mandrill_api_key = raw_input()
    print('Enter secret key. Leave blank to auto-generate. ' + red('Be careful if you reinstall old server, better to keep the old secret key'))
    secret_key = raw_input()
    if secret_key == '': secret_key = get_random_word(32)

    vault = '''
Here is your vault configuration. You'll need it to create ansible vault, so copy it.
#--------------------------------------------------------------------
host_name: {}  # domain of new instance
env: {} # name of the new instance
mysql_user: root # keep this root
mysql_pw: {}
dbname: {}_db # db name
dbuser: {}_db_user # auto-generated
dbpassword: {} # auto-generated
mandrill_password: {}
secret_key: {}
#--------------------------------------------------------------------
'''.format(host_name, name, mysql_root_password, name, name, get_random_word(32), mandrill_api_key, secret_key)
    print(vault)
    done = None
    while done != 'done':
        print('Enter [done] when you copied it.')
        done = raw_input()

    local('ansible-vault create devops/host_vars/{}'.format(name))
    local('cp -r app/config/prod app/config/{}'.format(name))
    print('Configuration files were copied at app/config/{}'.format(name))
    local('ANSIBLE_NOCOWS=1 ansible-playbook devops/site.yml -i devops/hosts --ask-vault-pass --limit {}'.format(name))
    with cd('/srv/varaa'):
        run('git clone git@github.com:varaa/varaa.git src')
        run('cd src')
        run('composer install --no-scripts')
    local('ANSIBLE_NOCOWS=1 ansible-playbook devops/app.yml -i devops/hosts --ask-vault-pass --limit {}'.format(name))

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
