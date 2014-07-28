from fabric.api import cd, run, task, hosts, env
import os

HOME = os.getenv('HOME')


def _deploy():
    env.user = 'root'
    with cd('/srv/varaa/src'):
        # pull latest source
        run('git pull')
        # install dependencies
        run('composer install')


@task(alias='ds')
@hosts('dev.varaa.co')
def deploy_stag():
    _deploy()


@task(alias='dp')
@hosts('varaa.co')
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
