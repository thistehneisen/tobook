from fabric.api import cd, run, task, hosts, env
import os

HOME = os.getenv('HOME')


def _deploy():
    env.user = 'root'
    with cd('/srv/varaa'):
        # pull latest source
        run('git pull')


@task(alias='ds')
@hosts('dev.varaa.co')
def deploy_stag():
    _deploy()


@task(alias='rc')
@hosts('dev.varaa.co')
def run_command(command='ls'):
    '''
    Run any command in server
    '''
    env.user = 'root'
    with cd('/srv/varaa'):
        run(command)
