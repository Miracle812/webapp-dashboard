<?php namespace Api\Droid\V1;

use Api\Droid\DroidController as ParentDroidController;

class DroidController extends ParentDroidController
{
    public function getTimestamp(){
        return \Response::json(['status'=>'success','data'=>\Carbon::now('UTC')->timestamp]);
    }

    public function postAuth(){

        if(\Auth::check()){
            \Auth::logout();
            return \Response::json (['status'=>'error','message' => "Not logged."],400);
        }

        try{
            if(!($input = urldecode(\Input::get('data'))) || !(count($explode = explode(':',$input))==3))
                throw new \Exception('Invalid post data.');
        }
        catch(\Exception $e){
            return \Response::json (['status'=>'error','message' => $e -> getMessage()],400);
        }

        list($encUser,$encPass,$timestamp) = $explode;
        $diffSec = \Carbon::createFromTimestamp($timestamp)->diffInSeconds(\Carbon::now());
        if($diffSec > 10){
            return \Response::json (['status'=>'error','message' => 'Auth token has expired'],400);
        }
        $encrypter = new \MCrypt();
        $encrypter -> setTimestamp($timestamp);

        $decUser = $encrypter->decrypt($encUser);
        $decPass = $encrypter->decrypt($encPass);

        $token = \JWTAuth::attempt(['email'=>$decUser, 'password'=>$decPass, 'active' => 1]);
        if (!$token) {
            return \Response::json(['status'=>'error', 'message' => 'Auth unsuccessful'], 401);
        } else{
            $user = \Auth::user();
            $site = $user->unit();
            $client = $site->headquarter;
            return \Response::json ([
                'status'  => 'success',
                'message' => 'Auth successful',
                'data'    => [
                    'token'  =>  $token,
                    'user'   => ['name'=>$user->fullname(),'avatar'=>$user->avatar()],
                    'site'   => ['id'=>$site->id,'name'=>$site->name,'logo'=>$site->logo],
                    'client' => ['name'=>$client->name,'logo'=>$client->logo],
                ]
            ]);
        }
    }

    public function getCleaningSchedulesTasks()
    {
        $tasks = \Model\CleaningSchedulesTasks::
            select('id','unit_id','staff_id','form_id','title','description','type')->
            with(['items'=>function($query){
                $query->select('task_id','start','end','expiry');
            }])->
            whereIn('unit_id',\Auth::user()->units->lists('id'))->
            get();
        return \Response::json(['status'=>'success','data'=>$tasks->toArray()]);
    }

    public function restQueryData(){
        try{
            if(!($input = urldecode(\Request::header('x-data-request'))) || !(count($explode = explode(':',$input))==2))
                throw new \Exception('Invalid post data.');
        }
        catch(\Exception $e){
            return \Response::json (['status'=>'error','message' => $e -> getMessage()],400);
        }

        list($encQuery,$timestamp) = $explode;
        $diffSec = \Carbon::createFromTimestamp($timestamp)->diffInSeconds(\Carbon::now());
        if($diffSec > 10){
            return \Response::json (['status'=>'error','message' => 'Request data has expired'],400);
        }
        $encrypter = new \MCrypt();
        $encrypter -> setTimestamp($timestamp);
        return $encrypter->decrypt($encQuery);
    }

    public function getQueryData()
    {
        $decQuery = $this->restQueryData();
        $data = DB::select(DB::raw($decQuery));
        return $this->returnJson(['status' => 'success','data'=>$data]);
    }

    public function postQueryData()
    {
        $decQuery = $this->restQueryData();
        $data = DB::insert(DB::raw($decQuery));
        return $this->returnJson(['status' => 'success','data'=>$data]);
    }

    public function putQueryData()
    {
        $decQuery = $this->restQueryData();
        $data = DB::update(DB::raw($decQuery));
        return $this->returnJson(['status' => 'success','data'=>$data]);
    }

    public function deleteQueryData()
    {
        $decQuery = $this->restQueryData();
        $data = DB::delete(DB::raw($decQuery));
        return $this->returnJson(['status' => 'success','data'=>$data]);
    }
}