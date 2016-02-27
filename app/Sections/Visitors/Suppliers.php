<?php namespace Sections\Visitors;

class Suppliers extends VisitorsSection
{

    public function getIndex()
    {
        $suppliers = \Model\Suppliers::where('unit_id', '=', $this -> auth_user -> unit() -> id) -> get();
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('index'), compact('suppliers','breadcrumbs'));
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
                    $row->created_at,
                    $row->created_at(),
                    $row->name,
                    $row->city,
                    \HTML::ownOuterBuilder(\HTML::ownButton($row->id, 'suppliers','details','fa-search', 'btn-default')),

                ];
            }
            if($options)
                return \Response::json(['aaData' => $options]);
            else
                return \Response::json(['aaData' => []]);
        }
        return \Response::json(['aaData' => []]);
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

}