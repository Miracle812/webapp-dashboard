<?php namespace Sections\LocalManagers;

class Suppliers extends LocalManagersSection {

    public function __construct(){
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('suppliers', 'Suppliers');
    }

    public function getIndex()
    {
        $suppliers = \Model\Suppliers::where('unit_id', '=', $this -> auth_user -> unit() -> id) -> get();
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('index'), compact('suppliers','breadcrumbs'));
    }

    public function getCreate()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('create') );
        return \View::make($this->regView('create'), compact('breadcrumbs'));
    }

    public function getImport()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('import') );
        return \View::make($this->regView('import'), compact('breadcrumbs'));
    }

    public function postImport()
    {
        $file = \Input::file('import');
        $model = new \Model\Suppliers();
        $rules = $model->import['rules'];
        $columns = [$model->getTable() => $model->import['columns']];
        $import = \Services\FilesImport::import($rules, $columns, $file);
        if(isset($import['errors'])){
            return \Redirect::back()->withErrors($import['errors']);
        }
        else{
            return \Redirect::back()->with('success', \Lang::get('/common/messages.import_success'));
        }
    }

    public function postCreate()
    {
        $new = new \Model\Suppliers();
        $rules = $new -> rules;
        $input = \Input::all();
        $validator = \Validator::make($input, $rules);
        if($input['warning_min'] > $input['valid_min']){
            $validator->getMessageBag()->add('warning_min', 'Danger min is greater then Warning min');
            $validator->getMessageBag()->add('valid_min', 'Warning max is less then Danger min');
        }
        if($input['warning_max'] < $input['valid_max']){
            $validator->getMessageBag()->add('warning_max', 'Danger max is less then Warning max');
            $validator->getMessageBag()->add('valid_max', 'Warning max is greater then Danger max');
        }
        if(!$validator -> errors() -> count()) {
            $products = serialize($this->getProductList(\Input::get('products')));
            $new -> fill($input);
            $new -> unit_id = \Auth::user() -> unit() -> id;
            $new -> products = $products;
            $save= $new -> save();
            $type = $save ? 'success' : 'fail';
            $msg  = $save ? \Lang::get('/common/messages.create_success') : \Lang::get('/common/messages.update_fail');
            return \Redirect::to('/suppliers')->with($type, $msg);
        }
        else{
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function getDatatable()
    {
        if(!\Request::ajax())
            return $this -> redirectIfNotExist();
        $suppliers = \Model\Suppliers::where('unit_id', '=',  $this -> auth_user -> unit() -> id) -> get();
        $suppliers = ($filter = \Input::get('datatable')) ? \Mapic::datatableFilter($filter, $suppliers) : $suppliers -> take(100);
        $options = [];
        if (count($suppliers)){
            foreach ($suppliers as $row)
            {
                $options[] = [
                    $row->name,
                    $row->name,
                    $row->city,
                    \HTML::mdOwnOuterBuilder(\HTML::mdOwnButton($row->id, 'suppliers','details','search', 'md-btn-small md-btn-action')),
                    \HTML::mdOwnOuterBuilder(\HTML::mdOwnButton($row->id, 'suppliers','edit/logo','image', 'md-btn-small md-btn-action')),
                    \HTML::mdOwnOuterBuilder(
                        \HTML::mdActionButton($row->id, 'suppliers','edit/general', 'edit', 'Edit').' '.
                        \HTML::mdActionButton($row->id, 'suppliers','delete', 'delete', 'Delete')
                    )
                ];
            }
            if($options)
                return \Response::json(['aaData' => $options]);
            else
                return \Response::json(['aaData' => []]);
        }
        return \Response::json(['aaData' => []]);
    }


    public function getIncidents($id)
    {
        $supplier = \Model\Suppliers::find($id);
        if(!$supplier || !$supplier -> checkAccess())
            return $this -> redirectIfNotExist();

        $this -> breadcrumbs -> addCrumb('/suppliers/details/'.$supplier -> id, $supplier -> name, null);
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('incidents') );
        return \View::make($this->regView('incidents'), compact('supplier', 'incidents', 'breadcrumbs'));
    }

    public function getDetails($id)
    {
        $supplier = \Model\Suppliers::find($id);
        if(!$supplier || !$supplier -> checkAccess())
            return $this -> redirectIfNotExist();

        $this -> breadcrumbs -> addCrumb('/suppliers/details/'.$supplier -> id, $supplier -> name, null);
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('details') );
        return \View::make($this->regView('details'), compact('supplier', 'breadcrumbs'));
    }

    public function getEditGeneral($id)
    {
        $supplier = \Model\Suppliers::find($id);
        if(!$supplier || !$supplier -> checkAccess())
            return $this -> redirectIfNotExist();

        $this -> breadcrumbs -> addCrumb('/suppliers/details/'.$supplier -> id, $supplier -> name, null);
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit_general') );
        return \View::make($this->regView('edit_general'), compact('supplier', 'breadcrumbs'));
    }

    public function postEditGeneral($id)
    {
        $supplier = \Model\Suppliers::find($id);
        if(!$supplier || !$supplier -> checkAccess())
            return $this -> redirectIfNotExist();
        $rules = $supplier -> rules;
        $input = \Input::all();
        $validator = \Validator::make($input, $rules);
        if($input['warning_min'] > $input['valid_min']){
            $validator->getMessageBag()->add('warning_min', 'Danger min is greater then Warning min');
            $validator->getMessageBag()->add('valid_min', 'Warning max is less then Danger min');
        }
        if($input['warning_max'] < $input['valid_max']){
            $validator->getMessageBag()->add('warning_max', 'Danger max is less then Warning max');
            $validator->getMessageBag()->add('valid_max', 'Warning max is greater then Danger max');
        }
        if(!$validator -> errors() -> count()) {
            $products = serialize($this->getProductList(\Input::get('products')));
            $supplier -> fill($input);
            $supplier -> products = $products;
            $update = $supplier -> update();
            $type = $update ? 'success' : 'fail';
            $msg  = $update ? \Lang::get('/common/messages.update_success') : \Lang::get('/common/messages.update_fail');
            return \Redirect::back()->with($type, $msg);
        }
        else{
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function getEditLogo($id)
    {
        $supplier = \Model\Suppliers::find($id);
        if(!$supplier || !$supplier -> checkAccess())
            return $this -> redirectIfNotExist();
        $this -> breadcrumbs -> addCrumb('/suppliers/details/'.$supplier -> id, $supplier -> name, null);
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit_logo') );
        return \View::make($this->regView('edit_logo'), compact('supplier', 'breadcrumbs'));
    }

    public function postEditLogo($id)
    {
        if(!\Request::ajax())
            return $this -> redirectIfNotExist();
        $supplier = \Model\Suppliers::find($id);
        if(!$supplier || !$supplier -> checkAccess())
            return \Response::json(['type'=>'warning', 'msg'=>\Lang::get('common/messages.not_exist')]);
        $postData = \Input::get();
        $photo = new \Services\FilesUploader('suppliers');
        $path  = $photo -> getUploadPath($supplier->id);
        $logo  = $photo -> avatarUploader($postData,$path);
        \File::delete(public_path().$supplier -> logo);
        $supplier -> logo = $logo;
        $supplier -> update();
        return \Response::json(['url'=>$logo]);
    }

    public function getProductsAutocomplete($tag)
    {
        if(!\Request::ajax())
            return $this -> redirectIfNotExist();
        return \Response::json(\Model\ProductsList::select('name')->where('name','LIKE','%'.$tag.'%')->get()->toArray());
    }

    public function getProductList($input){

        $products = explode(',',$input);
        $prodArr = [];
        if(count($products)) {
            foreach ($products as $product) {
                $product = trim($product);
                $productList = \Model\ProductsList::whereName($product)->first();
                if ($productList)
                    $prodArr[] = $productList->id;
                else {
                    $productList = new \Model\ProductsList();
                    $productList->name = $product;
                    $productList->save();
                    $prodArr[] = $productList->id;
                }
            }
        }
        return $prodArr;
    }

    public function getDelete($id)
    {
        $supplier = \Model\Suppliers::find($id);
        if(!$supplier || !$supplier -> checkAccess())
            return $this -> redirectIfNotExist();
        $delete = $supplier -> delete();
        $type = $delete ? 'success' : 'fail';
        $msg  = $delete ? \Lang::get('/common/messages.delete_success') : \Lang::get('/common/messages.delete_fail');
        return \Redirect::to('/suppliers')->with($type, $msg);
    }
}