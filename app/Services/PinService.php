<?php


namespace App\Services;

use App\Repositories\OtpCode\OtpCodeInterface;
use App\Repositories\CustomerAuth\CustomerAuthInterface;
use App\Services\PinServiceTrait;
use App\Jobs\NewTokenGenerated;
use App\Jobs\TransactionPin;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PinService extends ResponseService
{
    use PinServiceTrait;

    public $customer;

    /**
     * @param $user
     * @param $params
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function create($user, $params)
    {
        $pin = $this->decodePinRequest($params['pin']);

        if (!$pin) return $this->getErrorResource([
            'message' => 'Pin is invalid'
        ]);

        DB::beginTransaction();
        app(CustomerAuthInterface::class)->findByColumn(['id' => $user->id])
            ->update(['transaction_pin' => bcrypt($pin)]);
        DB::commit();

        return $this->getSuccessResource([
            'message' => 'Pin created'
        ]);
    }

    public function update($user, $params)
    {
        $pin = $this->decodePinRequest($params['pin']);

        if (!$pin) return $this->getErrorResource([
            'message' => 'Pin is invalid'
        ]);

        DB::beginTransaction();
        app(CustomerAuthInterface::class)->findByColumn(['id' => $user->id])
            ->update(['transaction_pin' => bcrypt($pin)]);
        DB::commit();

        return $this->getSuccessResource([
            'message' => 'Pin Updated'
        ]);
    }

    public function validate($request_pin, $user_pin)
    {
        $pin = $this->decodePinRequest($request_pin);

        if (!$pin) return $this->getErrorResource([
            'message' => 'Pin is invalid'
        ]);

        if (Hash::check($pin, $user_pin)) {
            return $this->getSuccessResource([
                'data' => 'Pin Valid'
            ]);
        }

        return $this->getErrorResource([
            'message' => 'Invalid user pin'
        ]);
    }

    public function decodePinRequest($validatedPin)
    {
        try {
            $encoded_pin = base64_decode($validatedPin);
            $explode = explode(':', $encoded_pin);

            if (Carbon::createFromTimestamp($explode[2])->isToday()) {
                return $explode[1];
            }
            return false;
        } catch (\ErrorException $e) {
            logger()->erro($e->getMessage());

            return $this->getErrorResource([
                'message' => 'Pin Validation Failed'
            ]);
        }
    }

}
