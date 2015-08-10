<?php namespace App\Payment\Gateways;

use App;
use App\Payment\Models\Transaction;
use Carbon\Carbon;
use CheckoutFinland\Client;
use CheckoutFinland\Exceptions\MacMismatchException;
use CheckoutFinland\Exceptions\UnsupportedAlgorithmException;
use CheckoutFinland\Payment;
use CheckoutFinland\Response;
use Config;
use Event;
use Exception;
use Input;
use Log;
use Redirect;

class Checkout extends AbstractGateway
{
    /**
     * @{@inheritdoc}
     */
    protected function getOmnipayGateway()
    {
        // We don't have the Omnipay plugin for Checkout yet
        return null;
    }

    /**
     * @{@inheritdoc}
     */
    public function success($response)
    {
        // Do nothing
    }

    /**
     * @{@inheritdoc}
     */
    public function purchase(Transaction $transaction, $args = [])
    {
        $merchantId = Config::get('services.checkout.id');
        $merchantSecret = Config::get('services.checkout.secret');

        $payment = new Payment($merchantId, $merchantSecret);
        $payment->setReturnUrl(route('payment.notify', ['gateway' => 'checkout']));
        $payment->setCancelUrl(route('payment.cancel', ['id' => $transaction->id]));
        $payment->setDelayedUrl(route('payment.notify', ['gateway' => 'checkout']));
        $payment->setRejectUrl(route('payment.notify', ['gateway' => 'checkout']));

        $payment->setData([
            // stamp is the unique id for this transaction
            'stamp' => time(),
            // amount is in cents
            'amount' => ($transaction->amount * 100),
            // some reference id (perhaps order id)
            'reference' => $transaction->id,
            // some short description about the order
            'message' => '',
            // approximated delivery date, this is shown to customer service in Checkout Finland but not to the buyer
            'deliveryDate' => Carbon::today(),
            // country affects what payment options are shown FIN for all, others for credit cards
            'country' => 'FIN',
            'language' => 'EN',
        ]);

        $client = new Client();
        $response = $client->sendPayment($payment);
        Log::debug('Response receive from Checkout', ['response' => $response]);
        $xml = simplexml_load_string($response);
        Log::debug('Payment URL', ['url' => (string) $xml->paymentURL]);

        // Update transation ID of Checkout
        $transaction->paygate = 'Checkout';
        $transaction->code = (string) $xml->status;
        $transaction->reference = (string) $xml->id;
        $transaction->save();

        return Redirect::to((string) $xml->paymentURL);
    }

    /**
     * Receive POST request from Checkout and process order
     *
     * @return void
     */
    public function notify()
    {
        Log::debug('Incoming Checkout notify request', Input::all());
        $merchantSecret = Config::get('services.checkout.secret');
        $response = new Response($merchantSecret);
        $response->setRequestParams(Input::all());

        try {
            if ($response->validate()) {
                // We have a valid response, now check the status
                // The status codes are listed in the api documentation of Checkout Finland
                switch ($response->getStatus()) {
                    case '2':
                    case '5':
                    case '6':
                    case '8':
                    case '9':
                    case '10':
                        // These are paid and we can ship the product
                        $transaction = Transaction::find($response->getReference());
                        if ($transaction !== null) {
                            $transaction->status = Transaction::STATUS_SUCCESS;
                            $transaction->save();

                            //To display thank you message for tobook
                            $cart = $transaction->cart;
                            $cartId = null;
                            if ($cart !== null) {
                                $cartId = $cart->id;
                            }
                            // Fire success event, so that related data know how to update
                            // themselves
                            Event::fire('payment.success', [$transaction]);

                            Log::info('Payment from Checkout succeeded');

                            return Redirect::route('payment.success', ['cartId', $cartId]);
                        }
                    case '7':
                    case '3':
                    case '4':
                        // Payment delayed or it is not known yet if the payment was completed
                        Log::info('Checkout payment was delayed', Input::all());

                        $transaction = Transaction::find($response->getReference());
                        if ($transaction !== null) {
                            $transaction->status = Transaction::STATUS_DELAYED;
                            $transaction->save();
                        }
                    case '-1':
                        // Cancelled by user
                        return Redirect::route('payment.cancel', ['id' => $response->getReference()]);
                    case '-2':
                    case '-3':
                    case '-4':
                    case '-10':
                        // Cancelled by banks, Checkout Finland, time out e.g.
                        Log::info('Checkout payment was cancelled by banks, paygate, etc.', Input::all());
                        break;
                }
            } else {
                // Something went wrong with the validation, perhaps the user changed the return parameters
                Log::error('Invalid data from Checkout');
            }
        } catch (MacMismatchException $ex) {
            Log::error('MAC value from Checkout mismatched');
        } catch (UnsupportedAlgorithmException $ex) {
            Log::error('Checkout: Unsupported algorithm');
        }

        return Redirect::to('/');
    }
}
