from invoke import run, task
import threading


def grunt():
    run('grunt')


def es():
    run('elasticsearch')


def queue():
    run('php artisan queue:work --daemon')


def redis():
    run('redis-server')


def prepare_acceptance():
    run('fab ta')


@task(default=True)
def main():
    threads = map(
        lambda x: threading.Thread(target=x), (
            grunt, es, queue, redis, prepare_acceptance
        )
    )
    # Kick off
    [x.start() for x in threads]
    # Wait for completion - maybe pending KeyboardInterrupt or similar
    [x.join() for x in threads]
