@extends('_visitor.layouts.visitor')
@section('content')

<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3"></i> Food Incidents</h3>
</div>
<ul class="breadcrumb">
    <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
    <li><a href="{{URL::to('/')}}"><i class="fa fa-list-ul"></i> Food Incidents</a></li>
    <li class="active">Details</li>
</ul>
<div class="row">
    <div class="col-lg-12">
        <section class="panel panel-default">
            <header class="panel-heading-navitas">
                Food Incidents Details
            </header>
            <div class="panel-body">
                <div class="col-sm-12">
                    <h4 class="text-navitas">Category</h4>
                    <div class="row">
                        <div class="col-sm-5"><label>Category</label></div>
                        <div class="col-sm-7">{{$item->s1_s1}}</div>
                    </div>
                    <h4 class="text-navitas">About the Complaint</h4>
                    <div class="row">
                        <div class="col-sm-5"><label>Food Item</label></div>
                        <div class="col-sm-7">{{$item->s1_i1}}</div>
                    </div>

                    <div class="row">
                        <div class="col-sm-5"><label>Date time of Purchase</label></div>
                        <div class="col-sm-7">{{$item->s1_i2}}</div>
                    </div>

                    <div class="row">
                        <div class="col-sm-5"><label>Incident Details</label></div>
                        <div class="col-sm-7">{{$item->s1_t1}}</div>
                    </div>
                    <h4 class="text-navitas">Complaint</h4>
                    <div class="row">
                        <div class="col-sm-5"><label>Name</label></div>
                        <div class="col-sm-7">{{$item->s1_i3}}</div>
                    </div>

                    <div class="row">
                        <div class="col-sm-5"><label>Address</label></div>
                        <div class="col-sm-7">{{$item->s1_i4}}</div>
                    </div>

                    <div class="row">
                        <div class="col-sm-5"><label>Telephone</label></div>
                        <div class="col-sm-7">{{$item->s1_i5}}</div>
                    </div>

                    <div class="row">
                        <div class="col-sm-5"><label>Email address</label></div>
                        <div class="col-sm-7">{{$item->s1_i6}}</div>
                    </div>

                    <h4 class="text-navitas">Supplier of Food</h4>
                    <div class="row">
                        <div class="col-sm-5"><label>Batch number/Coding</label></div>
                        <div class="col-sm-7">{{$item->s2_i1}}</div>
                    </div>

                    <div class="row">
                        <div class="col-sm-5"><label>Date Coding</label></div>
                        <div class="col-sm-7">{{$item->s2_i2}}</div>
                    </div>

                    <div class="row">
                        <div class="col-sm-5"><label>Delivery Date</label></div>
                        <div class="col-sm-7">{{$item->s2_i3}}</div>
                    </div>
                    <h4 class="text-navitas">Complete if Foreign body found</h4>
                    <div class="row">
                        <div class="col-sm-5"><label>Has customer Surrendered Food/Object</label></div>
                        <div class="col-sm-7">{{$item->s2_s1}}</div>
                    </div>

                    <div class="row">
                        <div class="col-sm-5"><label>Object Colour</label></div>
                        <div class="col-sm-7">{{$item->s2_i4}}</div>
                    </div>

                    <div class="row">
                        <div class="col-sm-5"><label>Object Size:</label></div>
                        <div class="col-sm-7">{{$item->s2_i5}}</div>
                    </div>

                    <div class="row">
                        <div class="col-sm-5"><label>Object Weight</label></div>
                        <div class="col-sm-7">{{$item->s2_i6}}</div>
                    </div>

                    <div class="row">
                        <div class="col-sm-5"><label>Possible Identity</label></div>
                        <div class="col-sm-7">{{$item->s2_i7}}</div>
                    </div>

                    <div class="row">
                        <div class="col-sm-5"><label>Where Exactly Found in food</label></div>
                        <div class="col-sm-7">{{$item->s2_i8}}</div>
                    </div>
                    <h4 class="text-navitas">Complete if Alleged illness occurred</h4>
                    <div class="row">
                        <div class="col-sm-5"><label> Food Suspected to have caused illness </label></div>
                        <div class="col-sm-7">{{$item->s3_i1}}</div>
                    </div>

                    <div class="row">
                        <div class="col-sm-5"><label> Any other food purchased and eaten during the visit </label></div>
                        <div class="col-sm-7">{{$item->s3_i2}}</div>
                    </div>

                    <div class="row">
                        <div class="col-sm-5"><label> Symptoms of complaint </label></div>
                        <div class="col-sm-7">{{$item->s3_i3}}</div>
                    </div>

                    <div class="row">
                        <div class="col-sm-5"><label> Date Symptoms Began </label></div>
                        <div class="col-sm-7">{{$item->s3_i4}}</div>
                    </div>

                    <div class="row">
                        <div class="col-sm-5"><label> Date Symptoms Ceased </label></div>
                        <div class="col-sm-7">{{$item->s3_i5}}</div>
                    </div>

                    <div class="row">
                        <div class="col-sm-5"><label> Have they visited the doctors? </label></div>
                        <div class="col-sm-7">{{$item->s3_s1}}</div>
                    </div>

                    <div class="row">
                        <div class="col-sm-5"><label> Have they give a stool sample? </label></div>
                        <div class="col-sm-7">{{$item->s3_s2}}</div>
                    </div>

                    <div class="row">
                        <div class="col-sm-5"><label>If Stool sample result available, Please state:</label></div>
                        <div class="col-sm-7">{{$item->s3_t1}}</div>
                    </div>
                </div>
            </div>
        </section>
     </div>
</div>
@endsection