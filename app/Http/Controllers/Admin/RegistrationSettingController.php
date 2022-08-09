<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Repositories\CustomerRegistrationSetting\CustomerRegistrationSettingInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class RegistrationSettingController extends Controller
{
    protected $customerRegistrationSetting;

    public function __construct(CustomerRegistrationSettingInterface $customerRegistrationSetting)
    {
        $this->customerRegistrationSetting = $customerRegistrationSetting;
    }

    public function settings(Request $request, Builder $builder)
    {

        $data = $this->customerRegistrationSetting->getAll()->with(['user'])->select('customer_registration_settings.*')->latest();

        if ($request->ajax()) {
            return $this->allSettingsData($data);
        }

        $html = $builder->columns($this->settingsColumns());
        return view('registration.settings', compact('data', 'html'));
    }

    public function newSettings()
    {
        return view('registration.add-settings');
    }

    public function addNewSettings(Request $request)
    {

        $request->validate([
            'product_code' => 'required|unique:customer_registration_settings,product_code',
            'account_officer_code' => 'required'
        ]);

        $this->customerRegistrationSetting->create($request->all());

        return redirect('/admin/registration/settings')->with('success', 'New Customer Registration Settings Successful!');

    }

    public function activeSettings(Request $request)
    {
        $id = $request->id;

        $this->customerRegistrationSetting->findByColumn([
            ['active', '=', true]
        ])->update(['active' => false]);

        $this->customerRegistrationSetting->findByColumn([
            ['id', '=', decrypt($id)]
        ])->update(['active' => true]);

        return redirect()->back()->with('success', 'Product now active, new registered customers will be assigned to this product!');
    }

    private function settingsColumns()
    {
        return [
            [
                'title' => 'Product Code',
                'name' => 'product_code',
                'data' => 'product_code'
            ],
            [
                'title' => 'Account Officer Code',
                'name' => 'account_officer_code',
                'data' => 'account_officer_code'
            ],
            [
                'title' => 'Active',
                'name' => 'active',
                'data' => 'active'
            ],
            [
                'title' => 'Admin',
                'name' => 'user_id',
                'data' => 'user_id'
            ],
            [
                'title' => 'Date',
                'name' => 'created_at',
                'data' => 'created_at'
            ],
            [
                'title' => 'Action',
                'name' => 'action',
                'data' => 'action'
            ]
        ];
    }

    private function allSettingsData($data)
    {
        try {

            return DataTables::of($data)
                ->editColumn('user_id', function ($field) {
                    return $field->user->full_name ?? '';
                })->editColumn('created_at', function ($field) {
                    return $field->created_at->format('d-m-Y') ?? '';
                })->editColumn('active', function ($field) {
                    return $field->active == true ? 'Yes' : 'No';
                })->addColumn('action', function ($field) {

                    if ($field->active == false)
                        return "<button id='makeActive' href='" . route('admin.registration.settings-active') . "'
                                   data-id='" . encrypt($field->id) . "'     class='btn btn-success ml-2'>Make Active</button>";

                })
                ->removeColumn(['update_at', 'deleted_at'])
                ->make(true);
        } catch (\Exception $e) {
            return $e->getMessage();
        }


    }
}
