<?php namespace Modules;

class Unit extends Modules
{
    public function __construct()
    {
        $this->activateUserSection();
        $this->user = \Auth::user();
    }

    public function getEdit(){
        return \View::make($this->regView('edit_modal'));
    }

    public function getEditRatingStars($scores)
    {
        $scores = $scores ? : 0;
        $user = \Auth::user();
        $unit = $user -> unit();
        $rating = $unit->rating();
        return \View::make($this->regView('edit_rating_stars'), compact('rating','scores'));
    }

    public function postEditRatingStars()
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();

        $user = \Auth::user();
        $unit = $user -> unit();
        $input = \Input::all();
        $rules = array(
            'stars' => 'required|min:1|max:5',
            'description'  => 'required'
        );
        $validator = \Validator::make($input, $rules);
        $errors = $validator->messages()->toArray();
        $msgFail = \Lang::get('/common/messages.update_fail');
        if(empty($errors) )
        {
            $rating = new \Model\RatingStarsLogs();
            $rating -> fill ($input);
            $rating -> unit_id = $unit->id;
            $save = $rating -> save();
            \Services\Notifications::create($rating);
            if($save){
                \Services\FilesUploader::updateAfterCreate(['units_rating_stars',$user->id,$unit->id,$rating->id]);
                return \Response::json(['type'=>'success', 'msg' => \Lang::get('/common/messages.update_success')]);
            }
            else
                return \Response::json(['type'=>'error', 'msg' => $msgFail]);
        }
        else
        {
            return \Response::json([
                'type'   => 'error',
                'msg'    => $msgFail,
                'errors' => $this -> ajaxErrors($errors,[])
            ]);
        }
    }

    public function getEditLogo()
    {
        if(!\Request::ajax()) {
            return $this->redirectIfNotExist();
        }
        return \View::make($this->regView('edit_logo_modal'));
    }

    public function postEditLogo(){

        if(!\Request::ajax())
            return $this->redirectIfNotExist();

        $unit = \Auth::user()->unit();
        $photo    = new \Services\FilesUploader('unit');
        $path     = $photo -> getUploadPath($unit -> id);
        $photo -> checkCreatePath($path);
        $file     = \Input::file('image');
        $uploaded = $photo->Uploadify($file,$path);
        \File::delete(public_path().$unit -> logo);
        $unit -> logo = $path.$uploaded;
        $unit -> update();
        return \Response::json(['url' => $unit -> logo]);
    }

    public function postEdit()
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $unit = \Auth::user() -> unit();
        $input = \Input::all();
        $rules = array(
            'name'         => 'required|min:4',
            'city'         => 'required|min:4',
            'street_number'=> 'required|min:4',
            'post_code'    => 'required|min:4',
            'email'        => 'required|email|unique:units,email,'.$unit -> id
        );
        $validator = \Validator::make($input, $rules);
        $errors = $validator->messages()->toArray();
        $msgFail = \Lang::get('/common/messages.update_fail');
        if(empty($errors) )
        {
            $unit -> fill ($input);
            $update = $unit -> update();
            if($update)
                return \Response::json(['type'=>'success', 'msg' => \Lang::get('/common/messages.update_success')]);
            else
                return \Response::json(['type'=>'error', 'msg' => $msgFail]);
        }
        else
        {
            return \Response::json([
                'type'   => 'error',
                'msg'    => $msgFail,
                'form_errors' => $this -> ajaxErrors($errors,[])
            ]);
        }
    }

    public function getLogo()
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        return \Response::json(['image' => \Auth::user() -> unit() -> logo]);
    }
}