<?php

namespace App\Http\Controllers\V1;

use App\Http\Resources\VersionResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Version\VersionInterface;
use App\Services\ResponseService;

class VersionController extends Controller
{
    //
    protected $versionrepo, $responseService;
    public function __construct(VersionInterface $versionrepo, ResponseService $responseService){
        $this->versionrepo = $versionrepo;
        $this->responseService = $responseService;
    }

//    public function add(Request $request){
//        $param = array("active"=>true,"platform"=>$request->platform);
//        $oldmodel = $this->versionrepo->findByColumn($param)->update(["active"=>false]);
//        $model = $this->versionrepo->create($request->all());
//        return $this->responseService->getSuccessResource(['message'=>'Version added','data'=>$model]);
//    }


    public function getVersion(Request $request){
        $param = array("active"=>true,"platform"=>$request->header('platform'));
        // dd($param);
        $model = $this->versionrepo->findByColumn($param)->first();

        $resource = new VersionResource($model);
        return $this->responseService->getSuccessResource([
            'message'=>'Version data gotten',
            'data'=>$resource
        ]);
    }


}
