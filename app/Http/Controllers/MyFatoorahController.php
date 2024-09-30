<?php

namespace App\Http\Controllers;

use App\Events\PaymentEvent;
use App\Models\MyFatoorah as ModelsMyFatoorah;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use MyFatoorah\Library\API\Payment\MyFatoorahPayment;
use MyFatoorah\Library\API\Payment\MyFatoorahPaymentStatus;
use MyFatoorah\Library\MyFatoorah;



class MyFatoorahController extends Controller
{

    /**
     * @var array
     */
    public $mfConfig = [];

    //-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Initiate MyFatoorah Configuration
     */
    public function __construct()
    {
        $this->mfConfig = [
            'apiKey'      => config('myfatoorah.api_key'),
            'isTest'      => config('myfatoorah.test_mode'),
            'countryCode' => config('myfatoorah.country_iso'),
        ];
    }

    //-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Redirect to MyFatoorah Invoice URL
     * Provide the index method with the order id and (payment method id or session id)
     *
     * @return Response
     */

    /**
     * @OA\Post(
     *     path="/api/Myfatoora",
     *     summary="Redirect to MyFatoorah Invoice URL",
     *     description="Initiate a payment through MyFatoorah and retrieve the invoice URL.",
     *     operationId="getMyFatoorahInvoice",
     *     tags={"Payments"},
     *     @OA\Parameter(
     *         name="pmid",
     *         in="query",
     *         description="Payment method ID (0 for MyFatoorah invoice, 1 for Knet in test mode).",
     *         required=true,
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\Parameter(
     *         name="total",
     *         in="query",
     *         description="Total amount for the invoice.",
     *         required=true,
     *         @OA\Schema(type="number", format="float", example=30)
     *     ),
     *     @OA\Parameter(
     *         name="currency_type",
     *         in="query",
     *         description="Currency type for the invoice.",
     *         required=true,
     *         @OA\Schema(type="string", example="USD")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response with the invoice URL.",
     *         @OA\Schema(
     *             type="object",
     *             @OA\Property(property="invoiceURL", type="string", example="https://example.com/invoice/12345")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error response.",
     *         @OA\Schema(
     *             type="object",
     *             @OA\Property(property="IsSuccess", type="string", example="false"),
     *             @OA\Property(property="Message", type="string", example="Invalid payment method.")
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            //For example: pmid=0 for MyFatoorah invoice or pmid=1 for Knet in test mode
            $paymentId = request('pmid') ?: 0;
            $sessionId = request('sid') ?: null;
            $User = Auth::user();
            // Check if user is authenticated
            if ($User['status'] == 'Manager' || $User['status'] == 'Teacher') {
                return response()->json(['IsSuccess' => false, 'Message' => __('Only Student')]);
            }
            $orderId  = $User->id;
            $curlData = $this->getPayLoadData($orderId);
            $mfObj   = new MyFatoorahPayment($this->mfConfig);
            $payment = $mfObj->getInvoiceURL($curlData, $paymentId, $orderId, $sessionId);

            return $payment['invoiceURL'];
        } catch (Exception $ex) {
            $exMessage = __('myfatoorah.' . $ex->getMessage());
            return response()->json(['IsSuccess' => 'false', 'Message' => $exMessage]);
        }
    }

    //-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Example on how to map order data to MyFatoorah
     * You can get the data using the order object in your system
     *
     * @param int|string $orderId
     *
     * @return array
     */
    private function getPayLoadData($orderId = null)
    {
        $callbackURL = route('myfatoorah.callback');



        $user = Auth::user();


        return [
            'CustomerName'       => $user->name,
            'InvoiceValue'       => request('total'),
            'DisplayCurrencyIso' => request('currency_type'),
            'CustomerEmail'      =>  $user->email,
            'CallBackUrl'        => $callbackURL,
            'ErrorUrl'           => $callbackURL,
            'CustomerMobile'     =>  $user->phoneNumber,
            'Language'           => 'en',
            'CustomerReference'  => $orderId,
            'SourceInfo'         => 'Laravel ' . app()::VERSION . ' - MyFatoorah Package ' . MYFATOORAH_LARAVEL_PACKAGE_VERSION
        ];
    }

    //-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Get MyFatoorah Payment Information
     * Provide the callback method with the paymentId
     *
     * @return Response
     */
    public function callback()
    {
        try {
            $paymentId = request('paymentId');

            $mfObj = new MyFatoorahPaymentStatus($this->mfConfig);
            $data  = $mfObj->getPaymentStatus($paymentId, 'PaymentId');
            if ($data->InvoiceStatus == "Paid") {
                $my_fatoorah = ModelsMyFatoorah::create([
                    'user_id' => $data->CustomerReference,
                    'total' => $data->InvoiceValue,
                    'Payment_Status' => $data->InvoiceStatus,
                    'Country' =>  $data->InvoiceTransactions[0]->Country,
                    'Currency' =>  $data->InvoiceTransactions[0]->Currency,
                    'PaymentId' => $data->InvoiceTransactions[0]->PaymentId,
                ]);

                PaymentEvent::dispatch($data);
                $UserData =   $this->updateUserPaymentdate($data->CustomerReference, $data->CreatedDate);
                $invoice = "The Payment Request Is Successfully";
            }



            $message = $this->getTestMessage($data->InvoiceStatus, $data->InvoiceError);

            $response = ['IsSuccess' => true, 'Message' => $message, 'Data' => $data, "User_Data" => $UserData];
        } catch (Exception $ex) {
            $exMessage = __('myfatoorah.' . $ex->getMessage());
            $response  = ['IsSuccess' => 'false', 'Message' => $exMessage];
        }

        //---------------------------------Recording_the_payment_process_in_Database-------------------------


        return response()->json($response);
    }


    //-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Example on how the webhook is working when MyFatoorah try to notify your system about any transaction status update
     */
    public function webhook(Request $request)
    {
        try {
            //Validate webhook_secret_key
            $secretKey = config('myfatoorah.webhook_secret_key');
            if (empty($secretKey)) {
                return response(null, 404);
            }

            //Validate MyFatoorah-Signature
            $mfSignature = $request->header('MyFatoorah-Signature');
            if (empty($mfSignature)) {
                return response(null, 404);
            }

            //Validate input
            $body  = $request->getContent();
            $input = json_decode($body, true);
            if (empty($input['Data']) || empty($input['EventType']) || $input['EventType'] != 1) {
                return response(null, 404);
            }

            //Validate Signature
            if (!MyFatoorah::isSignatureValid($input['Data'], $secretKey, $mfSignature, $input['EventType'])) {
                return response(null, 404);
            }


            //Update Transaction status on your system
            $result = $this->changeTransactionStatus($input['Data']);

            return response()->json($result);
        } catch (Exception $ex) {
            $exMessage = __('myfatoorah.' . $ex->getMessage());
            return response()->json(['IsSuccess' => false, 'Message' => $exMessage]);
        }
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------
    private function changeTransactionStatus($inputData)
    {
        //1. Check if orderId is valid on your system.
        $orderId = $inputData['CustomerReference'];

        //2. Get MyFatoorah invoice id
        $invoiceId = $inputData['InvoiceId'];

        //3. Check order status at MyFatoorah side
        if ($inputData['TransactionStatus'] == 'SUCCESS') {
            $status = 'Paid';
            $error  = '';
        } else {
            $mfObj = new MyFatoorahPaymentStatus($this->mfConfig);
            $data  = $mfObj->getPaymentStatus($invoiceId, 'InvoiceId');

            $status = $data->InvoiceStatus;
            $error  = $data->InvoiceError;
        }

        $message = $this->getTestMessage($status, $error);

        //4. Update order transaction status on your system
        return ['IsSuccess' => true, 'Message' => $message, 'Data' => $inputData];
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------

    private function getTestMessage($status, $error)
    {
        if ($status == 'Paid') {


            return 'Invoice is paid.';
        } else if ($status == 'Failed') {
            return 'Invoice is not paid due to ' . $error;
        } else if ($status == 'Expired') {
            return $error;
        }
    }

    private function updateUserPaymentdate($inputData, $PaymentDate)
    {


        User::where('id', $inputData)->update(['payment_date' => $PaymentDate]);
        $user = User::find($inputData);

        return $user;
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------
}
