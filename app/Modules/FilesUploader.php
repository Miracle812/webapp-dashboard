<?php namespace Modules;

class FilesUploader extends Modules {

    public function __construct(){
        parent::__construct();
    }

    public function getUpload($targetType,$targetId)
    {
        $user  = \Auth::user();
        $files = \Model\Files::whereTargetType($targetType)->whereTargetId($targetId)->whereUserId($user->id)->get();
        $html = '';
        foreach($files as $file) {
            $html .= $this->getFileThumb($file);
        }
        return $html;
    }

    public function postUpload($targetType,$targetId)
    {
        $identifier = $targetId;
        $option = \Config::get('files_uploader.'.$targetType);
        $extensions = $option['extensions'];
        $fileSize = number_format($option['file_size'],1)*1024;

        $photo = new \Services\FilesUploader($targetType);
        $file_path = $photo -> getUploadPath($identifier,'files');
        $file = \Input::file('Filedata');

        if (!in_array($ext=strtolower($file -> getClientOriginalExtension()), $extensions))
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.create_fail'), 'errors' => ["files_upload[$targetType][$targetId]"=>['Not permitted file type (.'.$ext.'): '.$file -> getClientOriginalName().'.']]]);

        if( ($file->getSize()/1024) > $fileSize )
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.create_fail'), 'errors' => ["files_upload[$targetType][$targetId]"=>['Allowed file size exceeded for file: '.$file -> getClientOriginalName().'.']]]);

        $file_name = $photo -> Uploadify($file,$file_path,$extensions);
        $user   = \Auth::user();
        $create = ['user_id' => $user -> id,'unit_id' => $user -> unitId(),'target_type'=>$targetType,'target_id'=>$targetId,'file_name' => $file_name,'file_path' => $file_path];
        $files  = new \Model\Files();
        $files -> fill ($create);
        $save = $files -> save();
        if($save && $file_name)
            \Session::push('form_manager.files.items', [$file_path,$file_name]);
        return \Response::json(['type'=>'success']);
    }

    public function getFileThumb($file,$display = null)
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
            <div class="uk-button-dropdown" data-uk-dropdown="{mode:\'click\',pos:\'top-left\'}">
                <button class="uk-button uk-button-large" style="padding: 15px;">
                    '.($images? '<img src="'.$href.'" style="height:55px">' : '<i class="uk-icon-'.$ico.'" style="font-size: 300%;"></i>').'
                </button>
                <div class="uk-dropdown uk-dropdown-bottom">
                    <ul class="uk-nav uk-nav-dropdown">
                        <li class="uk-nav-header">'.$short.'</li>
                        <li class="uk-nav-divider"></li>'.
            ($images ? '<li><a data-uk-lightbox="{group:\'gallery\'}" href="'.\URL::to($href).'"><i class="material-icons uk-margin-right">search</i>Display</a></li>':'').'
                        <li><a class="form-file-download" href="/sys-files-uploader/file/download/'.$file->id.'"><i class="material-icons uk-margin-right">file_download</i>Download</a></li>'.
            (!$display?'<li class="uk-nav-divider"></li><li><a data-confirm="ajax-file-delete" href="/sys-files-uploader/file/delete/'.$file->id.'"><i class="material-icons uk-margin-right">delete</i>Delete</a></li>' : '').
                    '</ul>
                </div>
            </div>';



























    }

    public function getFileDownload($id) //ajax
    {
        $file = \Model\Files::find($id);
        if(!$file || !$file -> checkAccess())
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);
        else{
            $destinationPath = public_path() . "/$file->file_path/";
            $file = $destinationPath . $file->file_name;
            return \Response::download($file);
        }
        return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);
    }

    public function getFileDelete($id)
    {
        $file = \Model\Files::find($id);
        if(!$file || !$file -> checkAccess())
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);
        \File::delete(public_path().$file -> file_path.$file -> file_name);
        if($file -> delete())
            return \Response::json(['type'=>'success', 'msg'=>\Lang::get('/common/messages.delete_success')]);
        else
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.delete_fail')]);
    }
}