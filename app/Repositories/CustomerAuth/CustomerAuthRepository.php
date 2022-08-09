<?php


namespace App\Repositories\CustomerAuth;

use App\Models\Customers\CustomerDevice;
use App\Services\ResponseService;
use App\Models\Customers\Customer;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use App\Jobs\WelcomeMailJob;

class CustomerAuthRepository implements CustomerAuthInterface
{
    protected $customer, $responseService;

    public function __construct(Customer $customer, ResponseService $responseService)
    {
        $this->customer = $customer;
        $this->responseService = $responseService;
    }

    public function create(array $params)
    {
        $model = $this->customer;
        $this->setModelProperties($model, $params);
        $model->save();

        $data = [
            'email' => $model->email,
            'name' => $model->first_name,
            'account_number' => $model->nuban
        ];
        dispatch(new WelcomeMailJob($data));
        return $model;
    }

    //Api authentication
    public function apiLogin($params)
    {

        $customer = $this->customer::where('username', $params['username'])->with('transactions')->first();

        if (!$customer) {
            return $this->responseService->getErrorResource([
                'message' => 'Invalid Username or Password' //Not to display exact error message to prevent attackers from guessing
            ]);
        }

        //check if customer is not blocked
        if ($customer->blocked == 1) {
            return $this->responseService->getErrorResource([
                'message' => 'Please contact system administrator' // Account has been blocked
            ]);
        }

        // If a customer with the user was found - check if the specified password
        // belongs to the customer
        if (!Hash::check($params['password'], $customer->password)) {
            return $this->responseService->getErrorResource([
                'message' => 'Invalid Username or Password' // To prevent attackers from detecting if the email is valid or not.
            ]);
        }

        $routeName = Route::currentRouteName();

        if ($routeName == 'password.reset') {
            $this->resetPassword($params);
        }
        //skip if platform is web
        if ($params['platform'] != 'WEB') {

            $device = $customer->device->where('device_id', $params['device_id'])->where('active', true)->first();

            if (empty($device)) {
                if ($customer->username != usernameToSkipDeviceDetach($customer->username)) {
                    return $this->responseService->getErrorResource([
                        'message' => 'Sorry you cannot login with this device, please detach your previous device.'
                    ]);
                }
            }
        }

        $access = $this->getAccessToken($params);

        //Get user details
        $customerDetails = $this->customerDetails($access, $params);

        if ($params['platform'] != 'WEB') {

            $device = $customer->device->where('device_id', $params['device_id'])->where('active', true)->first();

            if ($device){
                $device->last_login = now();
                $device->save();
            }

        }

        return $customerDetails;
    }

    private function resetPassword($params)
    {
        CustomerDevice::where('customer_id', $params['customer_id'])->update(['active' => false]);

        //save new device id
        $customerDevice = new CustomerDevice();
        $customerDevice->customer_id = $params['customer_id'];
        $customerDevice->device_name = $params['device_name'];
        $customerDevice->device_id = $params['device_id'];
        $customerDevice->active = true;
        $customerDevice->save();
    }

    private function customerDetails($token, $headers)
    {
        //Get customer details

        $http = new Client;

        $customerDetails = $http->get(env('APP_URL') . '/api/v1/customer/details', [
            'headers' => [
                'Accept' => 'application/json',
                'Platform-Token' => $headers['platformToken'],
                'Platform' => $headers['platform'],
                'Authorization' => 'Bearer ' . $token['access_token'],
                'device-id' => $headers['device_id']
            ],
        ]);

        $customerDetailsData = json_decode((string)$customerDetails->getBody(), true);

        $customerDetailsData['data']['token'] = $token['access_token'];

        $customerDetailsData['data']['refresh_token'] = $token['refresh_token'];

        return $customerDetailsData;
    }

    private function getAccessToken($params)
    {
        try {
            $http = new Client;
            $response = $http->request('POST', env('APP_URL') . '/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => env('PASSPORT_CLIENT_ID'),
                    'client_secret' => env('PASSPORT_CLIENT_SECRET'),
                    'username' => $params['username'],
                    'password' => $params['password'],
                    'scope' => '',
                ],
            ]);

            return json_decode((string)$response->getBody(), true);
        } catch (ClientException $exception) {
            report($exception);
            return $this->responseService->getErrorResource([
                'message' => 'Please try again',
                'status_code' => '401'
            ]);
        } catch (ConnectException $exception) {
            report($exception);
            return $this->responseService->getErrorResource([
                'message' => 'Please try again',
                'status_code' => '401'
            ]);
        } catch (Exception $e) {
            report($e);
            return $this->responseService->getErrorResource([
                'message' => 'Please try again ',
                'status_code' => '401'
            ]);
        }
    }

    public function refreshToken(array $params)
    {

        try {

            $http = new Client;

            //Get access token and refresh token
            $response = $response = $http->post(env('APP_URL') . '/oauth/token', [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $params['refresh_token'],
                    'client_id' => env('PASSPORT_CLIENT_ID'),
                    'client_secret' => env('PASSPORT_CLIENT_SECRET'),
                    'scope' => '',
                ],
            ]);

            //Format response
            $result = json_decode((string)$response->getBody(), true);

            return $this->customerDetails($result, $params);

        } catch (ClientException $exception) {
            report($exception);
            return $this->responseService->getErrorResource([
                'message' => 'Please login',
                'status_code' => '401'
            ]);
        } catch (ConnectException $exception) {
            report($exception);
            return $this->responseService->getErrorResource([
                'message' => 'Please login',
                'status_code' => '401'
            ]);
        }
    }

    public function getAll()
    {
        return $this->customer::latest();
    }

    public function logout()
    {
        $accessToken = auth()->user()->token();

        $accessToken->revoke();

        return $this->responseService->getSuccessResource([
            'message' => 'Logout Successful'
        ]);
    }

    public function findById(int $id)
    {
        return $this->customer::find($id);
    }


    public function findByColumn(array $params)
    {
        return $this->customer::where($params);
    }

    public function authCustomer()
    {
        return Auth::user();
    }

    private function setModelProperties($model, $params)
    {
        $model->first_name = strtoupper($params['first_name']);
        $model->last_name = strtoupper($params['last_name']);
        $model->phone = $params['phone'];
        $model->email = $params['email'];
        $model->nuban = $params['nuban'];
        $model->cba_id = $params['cba_id'];
        $model->username = $params['username'];
        $model->gender = $params['gender'];
        $model->referral_code = generateCustomerReferral();
        $model->referred_by = $params['referred_by'] ?? null;
        $model->password = bcrypt($params['password']);
        $model->email_verified_at = Carbon::now();
        $model->registration_method = $params['registration_method'] ?? 'New';
    }
}
