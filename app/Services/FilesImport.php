<?php
namespace Services;

class FilesImport extends \BaseController
{

    public static function import($rules, $columns, $file)
    {
        if(empty($file)){
            $response = ['type'=>'error', 'errors' => ['empty_file_input' => trans('common/messages.empty_file_input')]];
        }
        else {
            require_once app_path() . '/../bundles/laravel-phpexcel/PHPExcel/IOFactory.php';
            $path = $file->getRealPath();
            $ext = $file->getClientOriginalExtension();
            $model = key($columns);
            $columns = $columns[$model];
            switch ($ext) {
                case 'xls':
                case 'xlsx':
                    $response = self::processXLSData($path, $columns, $rules, $model);
                    break;
                default:
                    $response = ['type' => 'error', 'errors' => ['invalid_filetype' => trans('common/messages.invalid_filetype', ['attribute' => $ext])]];
                    break;
            }
        }
        return $response;
    }

    public static function processXLSData($path,$columns,$rules,$model)
    {
        $objPHPExcel = \PHPExcel_IOFactory::load($path);
        $rows = $objPHPExcel -> getActiveSheet() -> toArray(null,true,true,true);
        $create = false;
        foreach($rows as $key => &$row) {
            $row = array_filter($row,
                function ($cell) {
                    return !is_null($cell);
                }
            );
            if (count($row) == 0) {
                unset($rows[$key]);
            }
        }
        unset($rows[1],$rows[2]);
        switch ($model){
            case 'staff':; break;
            case 'suppliers': unset($rows[3]); break;
        }
        if($rows) {
            foreach ($rows as $row_num => $row) {
                $data = [];
                foreach ($row as $key => $value) {
                    $column = isset($columns[$key]) ? $columns[$key] : false;
                    if ($column) {
                        switch ($column) {
                            case 'products' :
                                $value = serialize(self::getProductsListIds($value));
                                break;
                            default:
                                ;
                                break;
                        }
                        $data[$column] = $value;
                    }
                }

                $create = self::create($data, $rules, $model, $row_num);
                if (isset($create['errors'])) {
                    return $create;
                }
            }

            $type = $create ? 'success' : 'error';
            $msg  = $create ? trans('/common/messages.import_success') : trans('/common/messages.import_fail');
            $text = ' Added / updated records: '.count($create) . ' from file';
            return ['type'=>$type, 'msg' => $msg.$text];
        }
        else{
            return ['errors' => ['Nothing to import']];
        }
    }

    public static function create($data,$rules,$model,$row)
    {
        $validator = \Validator::make($data, $rules);
        if(!$validator -> errors() -> count()) {
            if (count($data)) {
                $data['unit_id'] = \Auth::user()->unit()->id;
                $targetModel = '\Model\\'.ucfirst(camel_case($model));
                return $targetModel::firstOrCreate($data);
            }
        }
        else{
            $validator->errors()->add( 'error_appears_at_row', trans('common/messages.error_appears_at_row',['row'=>$row]) );
            return ['type'=>'error', 'errors'=>$validator -> errors()];
        }
    }

    public static function getProductsListIds($products){
        $array =  explode(',',$products);
        $data = [];
        if($array){
            foreach($array as $value){
                $value = trim($value);
                if($value){
                    $product = \Model\ProductsList::firstOrCreate(['name'=>$value]);
                    $data[] = $product->id;
                }
            }
        }
        return $data;
    }
}