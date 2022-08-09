<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Advert\AdvertInterface;
use App\Services\ResponseService;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdvertController extends Controller
{
    protected $advert;

    public function __construct(AdvertInterface $advert)
    {
        $this->advert = $advert;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $datas = $this->advert->getAll();
        return view('advert.index', compact('datas'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
           'name' => ['required'],
           'image' => ['required','max:1000']
        ]);

        $params = $request->all();

        $photo = Storage::putFile('public\advert', new File($params['image'])); //save image

        $params['url'] = asset(Storage::url(str_replace('public','',$photo))); // saved photo absolute path
        $this->advert->create($params);
        return redirect()->route('admin.advert');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){
        return view('advert.add');
    }

    public function update($id)
    {
        $data = $this->advert->findById($id);
        $data->active = $data->active == 1 ? false : true;
        $data->save();

        return redirect()->back()->with('success','Service charge updated successfully');
    }

    public function active(){
        $data = $this->advert->findByColumn(
            [
                ['active','=',1]
            ]
        )->select(['name','url'])->get();
        return (new ResponseService())->getSuccessResource([
          'data' => $data
        ]);
    }
}
