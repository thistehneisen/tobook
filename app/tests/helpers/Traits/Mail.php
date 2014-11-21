<?php namespace Test\Traits;

use Mockery as m;

trait Mail
{
    /**
     * @param callable $closure should expect message as an array
     * @return m\Expectation
     */
    protected function _mockMailSend(\Closure $closure)
    {
        \Mail::pretend(false);

        // idea from http://stackoverflow.com/questions/18406497/how-to-test-mail-facade-in-laravel-4
        $swiftMailer = m::mock('Swift_Mailer');
        \App::make('mailer')->setSwiftMailer($swiftMailer);

        return $swiftMailer->shouldReceive('send')->andReturnUsing(function(\Swift_Message $message) use($closure) {
            $messageSimple = [];

            $messageSimple['subject'] = $message->getSubject();

            $messageSimple['to'] = [];
            $to = $message->getTo();
            if (is_string($to)) {
                $messageSimple['to'][$to] = '';
            } else {
                $messageSimple['to'] = $to;
            }

            $addresses = array_keys($messageSimple['to']);
            $messageSimple['toFirstAddress'] = $addresses[0];

            $closure($messageSimple);
        });
    }
}
