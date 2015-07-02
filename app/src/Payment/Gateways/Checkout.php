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
        $response_xml = @simplexml_load_string($response);

        return Redirect::to($response_xml->paymentURL);
    }

    /**
     * Receive POST request from Checkout and process order
     *
     * @return void
     */
    public function notify()
    {
        $merchantSecret = Config::get('services.checkout.secret');
        $response = new Response($merchantSecret);
        $response->setRequestParams(Input::all());
        $status_string = '';

        try {
            if ($response->validate()) {
                // we have a valid response, now check the status
                // the status codes are listed in the api documentation of Checkout Finland
                switch ($response->getStatus()) {
                    case '2':
                    case '5':
                    case '6':
                    case '8':
                    case '9':
                    case '10':
                        // These are paid and we can ship the product
                        $status_string = 'PAID';
                        break;
                    case '7':
                    case '3':
                    case '4':
                        // Payment delayed or it is not known yet if the payment was completed
                         $status_string = 'DELAYED';
                        break;
                    case '-1':
                         $status_string = 'CANCELLED BY USER';
                         break;
                    case '-2':
                    case '-3':
                    case '-4':
                    case '-10':
                        // Cancelled by banks, Checkout Finland, time out e.g.
                         $status_string = 'CANCELLED';
                        break;
                }
            } else {
                // something went wrong with the validation, perhaps the user changed the return parameters
            }
        } catch (MacMismatchException $ex) {
            echo 'Mac mismatch';
        } catch (UnsupportedAlgorithmException $ex) {
            echo 'Unsupported algorithm';
        }
    }
}
