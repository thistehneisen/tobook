from fabric.api import cd, run, task, hosts, env
import os

HOME = os.getenv('HOME')


def _deploy():
    env.user = 'root'
    with cd('/var/www'):
        # pull latest source
        run('git pull')


@task(alias='ds')
@hosts('varaa.co')
def deploy_stag():
    _deploy()


@task(alias='rc')
@hosts('varaa.co')
def run_command(command='ls'):
    '''
    Run any command in server
    '''
    env.user = 'root'
    with cd('/var/www'):
        run(command)
