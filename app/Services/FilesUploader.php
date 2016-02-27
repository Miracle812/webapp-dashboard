<?php
namespace Services;

class FilesUploader extends \BaseController {
    
    protected $path = 'upload/';
    public function __construct($section = null) {
        $this -> section = $section;
    }

    public function getSectionUploadPath(){
        if($this -> section)
            return $this -> path . $this -> section;
        return false;
    }
    
    public function getUploadPath($id = null, $target = 'photos'){
        $path = $id ? '/' . $id . '/' : '/';
        $sectionPath = $this -> getSectionUploadPath();
        if (!$sectionPath) return false;
        return $this -> checkCreatePath($sectionPath . $path . $target);
    }

    public function checkCreatePath($path){
        $ds = '/';
        $publicPath = public_path().$ds;
        $fullPath = $publicPath.$path;

        if (file_exists($fullPath)) return $ds.$path.$ds;
        $expPath = explode('/', $path);
        if(count($expPath) > 0)
        {
            for($i = 0; $i < count($expPath); $i++)
            {
                $publicPath .= $expPath[$i] . $ds;
                if (!is_file($publicPath) && !is_dir($publicPath))
                {
                    \File::makeDirectory($publicPath, $mode = 0777, true, true);
                }
            }
        }
        $returnPath = str_replace('//', '/', $ds.$path.$ds);
        return $returnPath;
    }
    
    public function createFilename($ext = '.jpg'){
        return sha1(uniqid(mt_rand(), true)).$ext;        
    }


    public function avatarUploader($data,$url)
    {
        $image='';
        $name = $this->createFilename();
        $url = $url.$name;
        $dst_x = 0;
        $dst_y = 0;
        $src_x = $data['x'];
        $src_y = $data['y'];
        $src_w = $data['w'];
        $src_h = $data['h'];
        $dst_w = $data['dw'];
        $dst_h = $data['dh'];
        $base64 = $data['image'];

        if (substr($base64, 0, 5) == 'data:') {
            $base64 = preg_replace('#^data:image/[^;]+;base64,#', '', $base64);
            $base64 = base64_decode($base64);
            $source = imagecreatefromstring($base64);
        }
        else {
            $base64 = strtok($base64, '?');
            list($height, $width, $type) = getimagesize($base64);
            if ($type == 1)
                $source = imagecreatefromgif($base64);
            else if ($type == 2)
                $source = imagecreatefromjpeg($base64);
            else if ($type == 3) {
                $source = imagecreatefrompng($base64);
                imagealphablending($image, FALSE);
                imagesavealpha($image, TRUE);
            }
            else die();
        }
        $image = imagecreatetruecolor($dst_w, $dst_h);
        imagecopyresampled($image, $source, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
        imagejpeg($image, public_path().$url, 100);
        return $url;
    }

    public function Uploadify($file, $url, $fileTypes = ['jpg', 'jpeg', 'gif', 'png'])
    {
        $nameOrg = $file -> getClientOriginalName();
        $name    = $this -> checkFilename($nameOrg,$url);

        if (in_array(strtolower($file -> getClientOriginalExtension()), $fileTypes))
        {
            $file -> move(public_path().$url, $name = \Str::ascii($name));
            return $name;
        }
        else
        {
            return false;
        }
    }
    public function checkFilename($org_name, $url, $name = null, $i = 0)
    {
        $filename = $name ? : $org_name;

        if(\File::exists(public_path().$url.$filename))
        {
            $i++;
            $path_parts = pathinfo($org_name);
            $ext    = $path_parts['extension'];
            $fn     = $path_parts['filename'];
            $name   = $fn.'('.$i.').'.$ext;

            $filename = $this->checkFilename($org_name, $url, $name, $i);
        }
        return $filename;
    }

    public function fileExist($fullPath)
    {
        return \File::exists($fullPath) ? true : false;
    }

    public static function updateAfterCreate(array $options)//$targetType, $userId, $unitId, $objectId
    {
        if(count($options) == 4) {
            list($targetType, $userId, $unitId, $objectId) = $options;
            $photos = \Model\Files::where('target_type', '=', $targetType)
                ->where('target_id', '=', 'create.' . $userId)
                ->where('unit_id', '=', $unitId)
                ->where('user_id', '=', $userId)
                ->get();

            if ($photos->count()) {
                $self = new self();
                $self->section = $targetType;
                $file_path = $self->getUploadPath($objectId, null);
                $oldPath = public_path() . '/upload/' . $targetType . '/create.' . $userId . '/';
                if (\File::exists($oldPath)) {
                    \File::move($oldPath, public_path() . $file_path);
                    foreach ($photos as $photo) {
                        $photo->target_id = $objectId;
                        $photo->file_path = $file_path . 'files/';
                        $photo->update();
                    }
                } else {
                    $photos->delete();
                }
            }
        }
    }
}