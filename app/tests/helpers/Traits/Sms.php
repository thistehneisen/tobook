<?php namespace Test\Traits;

use Mockery as m;

trait Sms
{
    /**
     * @param callable $closure should expect message as an array
     * @return m\Expectation
     */
    protected function _mockSmsSend(\Closure $closure)
    {
        return \Sms::shouldReceive('send')->andReturnUsing(function($from, $to, $message) use($closure) {
            $messageArray = [
                'from' => $from,
                'to' => $to,
                'content' => $message,
            ];

            $closure($messageArray);
        });
    }
}
