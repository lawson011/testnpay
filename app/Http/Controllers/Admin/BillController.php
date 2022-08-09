<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use App\Repositories\BillTransaction\BillTransactionInterface;


class BillController extends Controller
{
    public $bills;

    public function __construct(BillTransactionInterface $bills)
    {
        $this->bills = $bills;
    }

    public function index(Request $request, Builder $builder)
    {
        $data = $this->bills->all()->get();

        if ($request->ajax()){
            return $this->allBillsData($data);
        }

        $html = $builder->columns($this->billColumns());

        return view('bills.index', compact('data','html'));
    }

    private function allBillsData($data)
    {
        try {
            return DataTables::of($data)
                ->editColumn('reference', function ($field) {
                    return $field->reference;
                })
                ->editColumn('trx_reference', function ($field) {
                    return $field->trx_reference;
                })
                ->editColumn('etrazanct_reference', function ($field) {
                    return $field->etrazanct_reference;
                })
                ->editColumn('amount', function ($field) {
                    return $field->amount;
                })
                ->editColumn('account', function ($field) {
                    return $field->account;
                })
                ->editColumn('message', function ($field) {
                    return $field->message;
                })
                ->editColumn('status', function ($field) {
                    return $field->status === 1 ? 'Success' :  'Failed';
                })
                ->editColumn('created_at', function ($field) {
                    return formatDate($field->created_at)->format('d/m/Y');
                })
                ->removeColumn(['update_at'])
                ->make(true);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function billColumns()
    {
        return [
            [
                'title' => 'Reference',
                'name'  => 'reference',
                'data'  => 'reference'
            ],
            [
                'title' => 'Transaction Reference(GL)',
                'name'  => 'trx_reference',
                'data'  => 'trx_reference'
            ],
            [
                'title' => 'Etrazanct Reference',
                'name'  => 'etrazanct_reference',
                'data'  => 'etrazanct_reference'
            ],
            [
                'title' => 'Amount',
                'name'  => 'amount',
                'data'  => 'amount'
            ],
            [
                'title' => 'Account',
                'name'  => 'account',
                'data'  => 'account'
            ],
            [
                'title' => 'Message',
                'name'  => 'message',
                'data'  => 'message'
            ],
            [
                'title' => 'Status',
                'name'  => 'status',
                'data'  => 'status'
            ],
            [
                'title' => 'Created At',
                'name'  => 'created_at',
                'data'  => 'created_at'
            ],
        ];
    }
}
