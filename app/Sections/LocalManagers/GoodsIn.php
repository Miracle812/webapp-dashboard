<?php namespace Sections\LocalManagers;

class GoodsIn extends LocalManagersSection
{

    public function __construct()
    {
        parent::__construct();
        $this->breadcrumbs->addCrumb('goods-in', 'Goods In');
    }

    public function getIndex()
    {
        $breadcrumbs = $this->breadcrumbs->addLast($this->setAction('list'));
        return \View::make($this->regView('index'), compact('breadcrumbs'));
    }

    public function getDetails($id)
    {
        $item = \Model\TemperaturesForGoodsIn::find($id);
        if (!$item || !$item->checkAccess())
            return $this->redirectIfNotExist();

        $breadcrumbs = $this->breadcrumbs->addLast($this->setAction('details'));
        return \View::make($this->regView('details'), compact('breadcrumbs', 'item'));
    }

    public function getDatatable($id = null)
    {
        if (!\Request::ajax())
            return $this->redirectIfNotExist();

        if ($id) {
            $supplier = \Model\Suppliers::find($id);
            if (!$supplier || !$supplier->checkAccess())
                return \Response::json(['aaData' => []]);
            $goodsIn = $supplier->unitGoodsIn();

        } else {
            $goodsIn = \Model\TemperaturesForGoodsIn::where('unit_id', '=', $this->auth_user->unit()->id)->get();
        }

        $goodsIn = ($filter = \Input::get('datatable')) ? \Mapic::datatableFilter($filter, $goodsIn) : $goodsIn->take(100);

        if (!$goodsIn->count())
            return \Response::json(['aaData' => []]);
        $tempService = new \Services\Temperatures();

        foreach ($goodsIn as $row) {
            $options[] = [
                $row->date_time,
                $row->date_time(),
                $row->staff_name,
                $row->products_name,
                $tempService->getPopoverButton($row, ['in_title' => true]),
                (\HTML::mdOwnOuterBuilder(\HTML::mdOwnIcoStatus($row->package_accept))),
                (\HTML::mdOwnOuterBuilder(\HTML::mdOwnIcoStatus($row->date_code_valid))),
                (\HTML::mdOwnOuterBuilder(\HTML::mdOwnIcoStatus($row->compliant))),
                (\HTML::mdOwnOuterBuilder(\HTML::mdActionButton($row->id, 'goods-in', 'edit', 'edit', 'Edit'))),
                (\HTML::mdOwnOuterBuilder(\HTML::mdActionButton($row->id, 'goods-in', 'details', 'search', 'Details')))
            ];
        }
        return \Response::json(['aaData' => $options]);
    }

    public function getEdit($id)
    {
        $item = \Model\TemperaturesForGoodsIn::find($id);
        if (!$item || !$item->checkAccess())
            return $this->redirectIfNotExist();
        $this->breadcrumbs->addCrumb('/goods-in/details/' . $item->id, \Lang::get('common/sections.' . $this->section . '.title') . '-' . $item->id, null);
        $breadcrumbs = $this->breadcrumbs->addLast($this->setAction('edit'));
        return \View::make($this->regView('edit'), compact('item', 'breadcrumbs'));
    }

    public function postEdit($id)
    {
        $item = \Model\TemperaturesForGoodsIn::find($id);
        if (!$item || !$item->checkAccess())
            return $this->redirectIfNotExist();
        $rules = [
            'compliant' => 'required'
        ];
        $input = \Input::all();
        $validator = \Validator::make($input, $rules);
        $update = false;
        if (!$validator->fails()) {
            $item->action_todo = \Input::get('action_todo');
            $item->compliant = \Input::get('compliant') ? 1 : 0;
            $update = $item->update();
        }
        $type = $update ? 'success' : 'fail';
        $msg = $update ? \Lang::get('/common/messages.update_success') : \Lang::get('/common/messages.update_fail');
        return \Redirect::to('/goods-in/details/' . $item->id)->with($type, $msg);
    }

    public function getCreate()
    {
        //$staff = \Model\Staffs::whereIn('unit_id',$this->getHqUnitsId())->get();
        $staffs = \Model\Staffs::where('unit_id', $this->auth_user->unit()->id)->get();
        $staffArr = [];
        foreach ($staffs as $row) {
            $staffArr[$row->id] = $row->fullname();
        }
        $staffs = $staffArr;
        $suppliers = \Model\Suppliers::where('unit_id', $this->auth_user->unit()->id)->lists('name', 'id');

        $breadcrumbs = $this->breadcrumbs->addLast($this->setAction('create'));
        return \View::make($this->regView('create'), compact('staffs', 'suppliers', 'breadcrumbs'));
    }

    public function postCreate()
    {
        $supplier = \Model\Suppliers::find(\Input::get('supplier_id'));

        if ($supplier && $supplier->checkAccess())
            \Input::merge(array('supplier_name' => $supplier->name));
        else
            \Input::merge(array('supplier_name' => ''));
        $staff = \Model\Staffs::find(\Input::get('staff_id'));
        if ($staff && $staff->checkAccess())
            \Input::merge(array('staff_name' => $staff->fullname()));
        else
            \Input::merge(array('staff_name' => ''));

        $createdByUser = \Lang::get('/common/messages.created_by_user');
        \Input::merge(array('device_name' => $createdByUser));
        \Input::merge(array('device_identifier' => $createdByUser));
        $new = new \Model\TemperaturesForGoodsIn();
        $rules = $new->rules;
        $input = \Input::all();
        $validator = \Validator::make($input, $rules);
        $save = false;
        $now = \Carbon::now();
        if (!$validator->fails()) {
            $new->unit_id = $supplier->unit_id;
            $new->fill($input);
            $new->date_time = $now->timestamp;
            $save = $new->save();
            $supplier = $new->supplier;
            $tempService = new \Services\Temperatures();
            $isValid = $tempService->commonVerifier($supplier, $new);

            if ((!$new->package_accept || !$new->date_code_valid || !$isValid) && $save) {
                $issues = [];
                if (!$new->package_accept)
                    $issues[] = 'Package';
                if (!$new->date_code_valid)
                    $issues[] = 'Date code';
                if ($new->invalid_id)
                    $issues[] = 'Temperature';
                $message = 'A Goods In Record has non-compliant / not-valid ' . implode(' and ', $issues) . '.';

            }
        }
        $type = $save ? 'success' : 'fail';
        $msg = $save ? \Lang::get('/common/messages.create_success') : \Lang::get('/common/messages.create_fail');
        if ($save)
            return \Redirect::to('/goods-in/details/' . $new->id)->with($type, $msg);
        else
            return \Redirect::back()->withInput()->withErrors($validator);
    }

    public function getCompliant($id) //ajax from dashboard
    {
        if (!\Request::ajax())
            return $this->redirectIfNotExist();

        $item = \Model\TemperaturesForGoodsIn::find($id);
        if (!$item || !$item->checkAccess())
            return $this->redirectIfNotExist();

        $item->compliant = 1;
        $update = $item->update();

        $type = $update ? 'success' : 'fail';
        $msg = $update ? \Lang::get('/common/messages.update_success') : \Lang::get('/common/messages.update_fail');
        return \Redirect::to('/goods-in/details/' . $item->id)->with($type, $msg);

        $breadcrumbs = $this->breadcrumbs->addLast($this->setAction('details'));
        return \View::make($this->regView('details'), compact('breadcrumbs', 'item'));
    }

    public function getDeliveries() //ajax from dashboard
    {
        $unitId = $this->auth_user->unitId();
        $suppliers = \Model\Suppliers::whereUnitId($unitId)->lists('name', 'id');;
        $breadcrumbs = $this->breadcrumbs->addLast($this->setAction('deliveries'));
        return \View::make($this->regView('deliveries'), compact('breadcrumbs', 'suppliers'));
    }

    public function getDeliveriesDatatable() //ajax from dashboard
    {
        if (!\Request::ajax())
            return $this->redirectIfNotExist();
        $unitId = $this->auth_user->unitId();
        $query = new \Model\TemperaturesForGoodsIn();
        $query = $query->whereUnitId($unitId);
        $goodsIn = $query->get();

        if ($filter = \Input::get('datatable')) {
            $supId = \Input::get('datatable.supplier_id');
            if ($supId && $supId !== 'select' && is_numeric($supId)) {
                $supplier = \Model\Suppliers::find($supId);
                if ($supplier && $supplier->checkAccess()) {
                    $goodsIn->filter(function ($item) use ($supId) {
                        return $item->supplier_id == $supId;
                    });
                }
            }
            $goodsIn = \Mapic::datatableFilter($filter, $goodsIn);
        } else {
            $goodsIn->take(100);
        }

        if (!$goodsIn->count())
            return \Response::json(['aaData' => []]);
        $tempService = new \Services\Temperatures();
        foreach ($goodsIn as $row) {
            $options[] = [
                $row->date_time,
                $row->date_time(),
                $row->supplier_name,
                $row->products_name,
                $tempService->getPopoverButton($row),
                \HTML::mdOwnOuterBuilder(\HTML::mdOwnIcoStatus($row->package_accept)),
                \HTML::mdOwnOuterBuilder(\HTML::mdOwnIcoStatus($row->date_code_valid)),
                \HTML::mdOwnOuterBuilder(\HTML::mdOwnIcoStatus($row->compliant)),
                \HTML::mdOwnOuterBuilder(\HTML::mdOwnButton($row->id, 'goods-in', 'edit', 'edit')),
                \HTML::mdOwnOuterBuilder(\HTML::mdOwnButton($row->id, 'goods-in', 'details', 'search'))
            ];
        }
        return \Response::json(['aaData' => $options]);
    }
}