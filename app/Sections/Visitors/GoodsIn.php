<?php namespace Sections\Visitors;

class GoodsIn extends VisitorsSection
{

    public function getIndex()
    {
        return \View::make($this->regView('index'), compact('breadcrumbs'));
    }

    public function getDeliveries()
    {

        $unitId = $this->auth_user->unitId();
        $suppliers = \Model\Suppliers::whereUnitId($unitId)->lists('name', 'id');;
        $breadcrumbs = $this->breadcrumbs->addLast($this->setAction('deliveries'));
        return \View::make($this->regView('deliveries'), compact('breadcrumbs', 'suppliers'));

    }

    public function getDetails($id)
    {
        $item = \Model\TemperaturesForGoodsIn::find($id);
        if (!$item || !$item->checkAccess())
            return $this->redirectIfNotExist();

        $breadcrumbs = $this->breadcrumbs->addLast($this->setAction('details'));
        return \View::make($this->regView('details'), compact('breadcrumbs', 'item'));
    }

    public function getDatatable()
    {
        $user = \Auth::user();
        $goodsIn = \Model\TemperaturesForGoodsIn::where('unit_id', $user->unit()->id)->get();
        $goodsIn = ($filter = \Input::get('datatable')) ? \Mapic::datatableFilter($filter, $goodsIn) : $goodsIn -> take(100);
        foreach ($goodsIn as $row) {
            $temperature = $row->temperature;
            $invoice = $row->invoice_number;
            $packaging = $row->package_accept ? '<div class="btn-rounded btn-sm btn-icon btn-success"><i class="fa fa-check"></i></div>' : '<div class="btn-rounded btn-sm btn-icon btn-danger"><i class="fa fa-times"></i></div>';
            $date_code = $row->date_code_valid ? '<div class="btn-rounded btn-sm btn-icon btn-success"><i class="fa fa-check"></i></div>' : '<div class="btn-rounded btn-sm btn-icon btn-danger"><i class="fa fa-times"></i></div>';
            $compliant = $row->compliant ? '<div class="btn-rounded btn-sm btn-icon btn-success"><i class="fa fa-check"></i></div>' : '<div class="btn-rounded btn-sm btn-icon btn-danger"><i class="fa fa-times"></i></div>';
            $options[] = [strtotime($row->created_at), $row->created_at(), $row->unit->name, $row->device_name, $row->staff_name, $row->supplier_name, $row->products_name, $temperature . '<sup>o</sup>C', $invoice, $packaging, $date_code, $compliant];
        }

        if (isset($options))
            echo json_encode(['aaData' => $options]);
        else {
            echo json_encode(['aaData' => [], 'type' => 'warning', 'msg' => 'Empty Goods In']);
        }
    }

    public function getInRecordsDatatable()
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
                \HTML::ownOuterBuilder(\HTML::ownIcoStatus($row->package_accept)),
                \HTML::ownOuterBuilder(\HTML::ownIcoStatus($row->date_code_valid)),
                \HTML::ownOuterBuilder(\HTML::ownIcoStatus($row->compliant)),
                \HTML::ownOuterBuilder(\HTML::ownButton($row->id, 'goods-in', 'details', 'fa-search'))
            ];
        }
        return \Response::json(['aaData' => $options]);
    }

   public function getInSuppliersDatatable($id=null){
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
               \HTML::ownOuterBuilder(\HTML::ownIcoStatus($row->package_accept)),
               \HTML::ownOuterBuilder(\HTML::ownIcoStatus($row->date_code_valid)),
               \HTML::ownOuterBuilder(\HTML::ownIcoStatus($row->compliant)),
               \HTML::ownOuterBuilder(\HTML::ownButton($row->id, 'goods-in', 'details', 'fa-search'))
           ];
       }
       return \Response::json(['aaData' => $options]);
   }
}