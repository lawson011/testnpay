<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\OtpCode\OtpCodeInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class OTPController extends Controller
{
    /**
     * @var $otpCode
     */
    protected $otpCode;

    /**
     * OTPController constructor.
     * @param OtpCodeInterface $otpCode
     */
    public function __construct(OtpCodeInterface $otpCode)
    {
        $this->otpCode = $otpCode;
    }

    /**
     * List all OTP
     *
     * @param Request $request
     * @param Builder $builder
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function index(Request $request,Builder $builder)
    {
        $data = $this->otpCode->getAll();

        if ($request->ajax()){
            return $this->allOtpData($data);
        }

        $html = $builder->columns($this->otpColumns());

        return view('Otp.index', compact('data','html'));
    }

    /**
     * OTP columns for datatable
     *
     * @return array
     */
    private function otpColumns()
    {
        return [
            [
                'title' => 'Email',
                'name' => 'email',
                'data' => 'email'
            ],
            [
                'title' => 'NUBAN/Phone',
                'name' => 'nuban',
                'data' => 'nuban'
            ],
            [
                'title' => 'Time',
                'name' => 'time',
                'data' => 'time'
            ],
            [
                'title' => 'OTP',
                'name' => 'token',
                'data' => 'token'
            ],
            [
                'title' => 'Used',
                'name' => 'used',
                'data' => 'used'
            ],
            [
                'title' => 'Expired',
                'name' => 'expire',
                'data' => 'expire'
            ],
            [
                'title' => 'Action',
                'name' => 'action',
                'data' => 'action'
            ]
        ];
    }

    /**
     * OTP data for datatable
     *
     * @param $data
     * @return string
     */
    private function allOtpData($data)
    {
        try {

            return DataTables::of($data)
                ->addColumn('used', function ($field) {
                    return $field->used == 1 ? 'YES' : 'NO';
                })
                ->addColumn('expire', function ($field) {
                    return $this->otpCode->checkExpiry($field->time) == 1 ? 'NO' : 'YES';
                })
                ->addColumn('action', function ($field) {
                    return "<a href='".route('admin.otp.delete',$field->id)."'
                    class='btn btn-success ml-2'>Delete</a>";

                })
                ->rawColumns(['action'])
                ->removeColumn(['update_at'])
                ->make(true);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->otpCode->findById($id)->delete();

        return redirect()->back();
    }
}
