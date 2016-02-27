<?php namespace Model;

class Suppliers extends Models
{
    protected $fillable = [
        'name',
        'description',
        'contact_person',
        'contact_person1',
        'contact_person2',
        'email',
        'email1',
        'email2',
        'phone',
        'phone1',
        'phone2',
        'post_code',
        'city',
        'street_number',
        'gmap_lat',
        'gmap_lng',
        'gmap_zoom',
        'warning_min',
        'warning_max',
        'valid_min',
        'valid_max',
        'products',
        'unit_id'
    ];


    public $rules = [
        'city'            => 'max:200|required',
        'contact_person'  => 'max:200|required',
        'contact_person1' => 'max:200',
        'contact_person2' => 'max:200',
        'email'           => 'max:200|email|required',
        'email1'          => 'max:200|email',
        'email2'          => 'max:200|email',
        'gmap_lat'        => 'max:200',
        'gmap_lng'        => 'max:200',
        'gmap_zoom'       => 'max:200',
        'name'            => 'max:200|required',
        'phone'           => 'max:200',
        'phone1'          => 'max:200',
        'phone2'          => 'max:200',
        'post_code'       => 'max:10|required',
        'street_number'   => 'max:200|required',
        'products'        => 'required',
        'warning_min'     => 'required|numeric|between:-50,100',
        'warning_max'     => 'required|numeric|between:-50,100',
        'valid_min'       => 'required|numeric|between:-50,100',
        'valid_max'       => 'required|numeric|between:-50,100',
    ];

    public $import = [
        'rules' => [

            'name'              => 'max:50|required',
            'post_code'         => 'max:10|required',
            'city'              => 'max:20|required',
            'street_number'     => 'max:30|required',
            'products'          => 'required',

            'warning_min'       => 'numeric|between:-50,100|required',
            'valid_min'         => 'numeric|between:-50,100|required',
            'valid_max'         => 'numeric|between:-50,100|required',
            'warning_max'       => 'numeric|between:-50,100|required',

            'contact_person'    => 'max:30|required',
            'phone'             => 'max:20',
            'email'             => 'max:30|email|required',

        ],
        'columns' => [
            'A'=>'name',
            'B'=>'post_code',
            'C'=>'city',
            'D'=>'street_number',
            'E'=>'products',

            'F'=>'warning_min',
            'G'=>'valid_min',
            'H'=>'valid_max',
            'I'=>'warning_max',

            'J'=>'contact_person',
            'K'=>'phone',
            'L'=>'email'
        ]
    ];

    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }

    public function units()
    {
        return $this->belongsTo('\Model\Units', 'unit_id');
    }

    public function goodsIn()
    {
        return $this->hasMany('\Model\TemperaturesForGoodsIn', 'supplier_id');
    }

    public function unitGoodsIn()
    {
        return $this->goodsIn()->where('unit_id', '=', $this -> unit_id)->get();
    }

    public function getEvents()
    {
        return \Model\Events::
              where('unit_id', '=', $this -> unit_id)
            ->where('target_type', '=', 'suppliers')
            ->where('target_id', '=', $this -> id)
            ->get();
    }

    public function products()
    {
        return $this->toString($this->getProducts());
    }

    public function getProducts()
    {
        $supplierProducts = [];
        if(!empty($this->products))
            $supplierProducts = @unserialize($this->products);
            if(!$supplierProducts){
                $this->products = serialize([]);
                $this->update();
            }

        if(!empty($supplierProducts))
            return  \Model\ProductsList::select('name')->whereIn('id',$supplierProducts)->get()->toArray();
        return [];
    }

    public function logo(){
        return $this -> logo ? : '/assets/images/user_blank.jpg';
    }

    public function toString($products)
    {
        $out = [];
        foreach($products as $product){
            $out[] = $product['name'];
        }
        return implode(', ',$out);
    }

    public function getUrl( $type = 'item' )
    {
        switch ($type){
            case 'item' : $url = '/details/'.$this->id; break;
            case 'section' :  $url = ''; break;
            default : $url = ''; break;
        }
        return 'suppliers' . $url ;
    }
}