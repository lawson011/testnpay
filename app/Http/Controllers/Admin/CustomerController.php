<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\CustomerAccountTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlockCustomerRequest;
use App\Http\Requests\BlockRequest;
use App\Repositories\CustomerAuth\CustomerAuthInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;


class CustomerController extends Controller
{
    use CustomerAccountTrait;

    protected $customerAuth;

    public function __construct(CustomerAuthInterface $customerAuth)
    {
        $this->customerAuth = $customerAuth;
    }

    public function customerDetails($id){
        $data = $this->customerAuth->findById(decrypt($id));
        $data['bioData'] = $data->bioData->first();
        $data['nextOfKin'] = $data->nextOfKin->first();
        $data['bankInfo'] = $data->bankInfo;
        $data['loan'] = $data->loan;
        $data['device'] = $data->device;
        $data['identityCard'] = $data->identityCard;
        $data['utility'] = $data->utility;

        return view('customer.profile', compact('data'));
    }

    public function allCustomers(Request $request,Builder $builder){

        $data = $this->customerAuth->getAll()->with(['bioData'])->select('customers.*')->latest();

        if ($request->ajax()){
            return $this->allCustomerData($data);
        }

        $html = $builder->columns($this->customerColumns());

        return view('customer.all', compact('data','html'));
    }

    private function customerColumns()
    {
        return [
            [
                'title' => 'Photo',
                'name' => 'bioData.photo',
                'data' => 'photo'
            ],
            [
                'title' => 'Staff',
                'name' => 'is_staff',
                'data' => 'is_staff'
            ],
            [
                'title' => 'Full Name',
                'name' => 'first_name',
                'data' => 'first_name'
            ],
//            [
//                'title' => 'Last Name',
//                'name' => 'last_name',
//                'data' => 'last_name'
//            ],
            [
                'title' => 'Phone',
                'name' => 'phone',
                'data' => 'phone'
            ],
//            [
//                'title' => 'Phone Verification Date',
//                'name' => 'phone_verified_at',
//                'data' => 'phone_verified_at'
//            ],
            [
                'title' => 'Email',
                'name' => 'email',
                'data' => 'email'
            ],
            [
                'title' => 'NUBAN',
                'name' => 'nuban',
                'data' => 'nuban'
            ],
//            [
//                'title' => 'Gender',
//                'name' => 'gender',
//                'data' => 'gender'
//            ],
            [
                'title' => 'Referral Code',
                'name' => 'referral_code',
                'data' => 'referral_code'
            ],
            [
                'title' => 'Referred By',
                'name' => 'referred_by',
                'data' => 'referred_by'
            ],
            [
                'title' => 'Date',
                'name' => 'customers.created_at',
                'data' => 'created_at'
            ],
            [
                'title' => 'Action',
                'name' => 'action',
                'data' => 'action'
            ]
        ];
    }

    private function allCustomerData($data)
    {
        try {

            return DataTables::of($data)
                ->editColumn('is_staff', function ($field) {
                    return $field->is_staff == 1 ? 'Yes' : 'No';
                })
                ->editColumn('first_name', function ($field) {
                    return $field->full_name;
                })
                ->editColumn('photo', function ($field) {

                    return "<img width='150' height='150' class='rounded-circle' src='".$field->bioData->first()['photo']."'/>";
                })
                ->editColumn('created_at', function ($field) {
                    return formatDate($field->created_at)->format('d/m/Y');
                })
                ->filterColumn('created_at', function ($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
                })
                ->addColumn('action', function ($field) {
                    return "<a href='".route('admin.customer.details',encrypt($field->id))."'
                    class='btn btn-success ml-2'>View Details</a>";

                })
                ->rawColumns(['action','photo'])
                ->removeColumn(['update_at'])
                ->make(true);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function block(BlockCustomerRequest $request)
    {

        try {
            DB::beginTransaction();
            $user = $this->customerAuth->findById(decrypt($request->id));
            $user->blocked = true;
            $user->save();
            blockCustomerStatus(array("customer_id" => decrypt($request->id), "reason" => $request->reason, "status" => 1));
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            return $e;
        }

    }

    public function unblock(BlockCustomerRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = $this->customerAuth->findById(decrypt($request->id));
            $user->blocked = false;
            $user->save();
            blockCustomerStatus(array("customer_id" => decrypt($request->id), "reason" => $request->reason, "status" => 0));
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            return $e;
        }
    }

    public function isStaff(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = $this->customerAuth->findById(decrypt($request->id));
            $user->is_staff = true;
            $user->save();
            blockCustomerStatus(array("customer_id" => decrypt($request->id), "reason" => 'Is a Staff', "status" => 1));
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            return $e;
        }
    }

    public function isNotAStaff(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = $this->customerAuth->findById(decrypt($request->id));
            $user->is_staff = false;
            $user->save();
            blockCustomerStatus(array("customer_id" => decrypt($request->id), "reason" => 'No longer a staff', "status" => 0));
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            return $e;
        }
    }

    public function isAgent(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = $this->customerAuth->findById(decrypt($request->input('id')));
            $user->is_agent = true;
            $user->save();
            blockCustomerStatus(array("customer_id" => decrypt($request->id), "reason" => 'Is an Agent', "status" => 1));
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            return $e;
        }
    }

    public function isNotAgent(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = $this->customerAuth->findById(decrypt($request->id));
            $user->is_agent = false;
            $user->save();
            blockCustomerStatus(array("customer_id" => decrypt($request->id), "reason" => 'No longer an agent', "status" => 0));
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            return $e;
        }
    }

    /**
     * Sync customer account with cba
     *
     * @param Request $request
     *
     * @return bool|mixed
     */
    public function syncAccount(Request $request)
    {
        $customer = $this->customerAuth->findById(decrypt($request->id));

        return self::updateCustomerAccount($customer);
    }
}
