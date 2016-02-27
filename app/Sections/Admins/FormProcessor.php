<?php namespace Sections\Admins;

class FormProcessor extends AdminsSection {

    public $types = [
        'input'      => 'fa-arrows',
        'textarea'   => 'fa-align-justify',
        'paragraph'  => 'fa-align-left',
        'radio'      => 'fa-dot-circle-o',
        'checkbox'   => 'fa-check-square-o',
        'select'     => 'fa-caret-square-o-down',
        'multiselect'=> 'fa-level-down',
        'datepicker' => 'fa-calendar'
    ];

    public function __construct(){
        parent::__construct();
    }

    public function getUpload($idForm,$idItem)
    {
        $form   = \Model\Forms::find($idForm);
        $item  = \Model\FormsItems::find($idItem);
        $user   = \Auth::user();
        $files = \Model\FormsFiles::whereFormLogId($form->id)->whereItemLogId($item->id)->whereUserId($user->id)->whereNull('answer_id')->get();
        $html = '';
        foreach($files as $file) {
            $html .= $this->getFileThumb($file);
        }
        return $html;
    }

    public function getFileDownload($id) //ajax
    {
        $file = \Model\FormsFiles::find($id);
        if(!$file || !$file -> checkAccess())
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);
        else{
            $destinationPath = public_path() . "/$file->file_path/";
            $file = $destinationPath . $file->file_name;
            return \Response::download($file);
        }
        return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);
    }

    public function getFileDisplay($id) //ajax
    {
        $file = \Model\FormsFiles::find($id);
        if(!$file || !$file -> checkAccess())
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);
        else{
            $file = $file->file_path . $file->file_name;
            return \URL::to($file);
        }
        return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);
    }

    public function getFileDelete($id)
    {
        $file = \Model\FormsFiles::find($id);
        if(!$file || !$file -> checkAccess())
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);
        \File::delete(public_path().$file -> file_path.$file -> file_name);
        if($file -> delete())
            return \Response::json(['type'=>'success', 'msg'=>\Lang::get('/common/messages.delete_success')]);
        else
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.delete_fail')]);
    }

    public function getFileThumb($file)
    {
        $name = $file->file_name;
        $ext = pathinfo($name, PATHINFO_EXTENSION);

        $href = '#';
        $images = false;
        $short = (strlen($name) > 20) ? substr($name, 0, 20) . '_.'.$ext : $name;
        switch ($ext){
            case 'jpg': case 'jpeg': case 'png': case 'gif': case 'bmp':
                $ico = 'fa-file-image-o';
                $href = \URL::to($file->file_path.$name);
                $images = true;
            break;

            case 'doc': case 'docx':  case 'odt': case 'rtf':
                $ico = 'fa-file-word-o';
            break;

            case 'xls': case 'xlsx':
                $ico = 'fa-file-excel-o';
            break;
            case 'pdf':
                $ico = 'fa-file-pdf-o';
            break;
            case 'txt':
                $ico = 'fa-file-text-o';
            break;
            default :  return '';   break;
        }
        return '
        <div class="col-sm-3 m-b">
            <div data-toggle="dropdown" class="btn btn-default tooltip-link" href="'.$href.'" title = "'.$short.'">
                <i class="fa fa-5x '.$ico.'"></i>
            </div>
            <ul class="dropdown-menu">'.
                ($images ? '<li><a  class="form-file-display" href="'.\URL::to($href).'"><span  class="text-primary"><i class="fa fa-search m-r"></i></span>Display</a></a></li>':'').
                '<li><a  class="form-file-download" href="/form-processor/file/download/'.$file->id.'"><span  class="text-success"><i class="fa fa-download m-r"></i></span>Download</a></a></li>
                <li class="divider"></li>
                <li><a  class="form-file-delete" href="/form-processor/file/delete/'.$file->id.'"><span  class="text-danger"><i class="fa fa-times-circle-o m-r"></i></span>Delete</a></li>
            </ul>
        </div>';
    }


    public function postUpload($idForm,$idItem)
    {
        return \Response::json(['type'=>'success']);
    }

    public function postData()
    {
        return \Response::json(['type'=>'success']);
    }


    public function getFormFromInputs($inputs)
    {
        if(isset($inputs['_token']))
            unset($inputs['_token']);
        $id = isset($inputs['form_base_id']) ? $inputs['form_base_id'] : NULL;
        if(!$id)
        foreach($inputs as $key => $data1){
            if(is_array($data1)){
                foreach($data1 as $formId => $data2){
                    $id = is_int($formId) ? $formId : 0;
                    if($id > 0) continue;
                }
            }
        }
        return $id ? \Model\Forms::find($id) : null;
    }
}