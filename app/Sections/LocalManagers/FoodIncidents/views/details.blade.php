@extends('newlayout.base')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')

    <div id="page_content">
        <div id="page_content_inner">

            <h2 class="heading_b uk-margin-bottom clearfix">{{$sectionName}}
                <span class="panel-action">
                    <a class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to('/food-incidents')}}"><i class="material-icons">search</i> {{Lang::get('common/general.list')}} </a>
                    <a class="md-btn md-bg-green-600 md-color-white md-btn-wave-light waves-effect waves-button waves-light" href="{{URL::to('/food-incidents/create')}}"><i class="material-icons">add</i> {{Lang::get('common/general.create')}} </a>
                </span>
            </h2>
    
            <?php /*@include('breadcrumbs',['data' => $breadcrumbs]) */?>

            <div class="md-card">

                <div class="md-card-toolbar">
                    <div class="md-card-toolbar-actions">
                        <a href="{{URL::to('/food-incidents/edit/'.$item->id)}}"><i class="md-icon material-icons">edit</i></a>
                    </div>
                    <h3 class="md-card-toolbar-heading-text large">
                        {{$sectionName}} - {{$actionName}}
                    </h3>
                </div>

                <div class="md-card-content">

                    <h4 class="navitas-text">Created & Updated</h4>
                    <div class="uk-grid">
                        <div class="uk-width-medium-1-3"><label>Created at</label></div>
                        <div class="uk-width-medium-2-3">{{$item->created_at()}}</div>
                    </div>
                    <div class="uk-grid">
                        <div class="uk-width-medium-1-3"><label>Updated at</label></div>
                        <div class="uk-width-medium-2-3">{{$item->updated_at()}}</div>
                    </div>
                    <h4 class="navitas-text">Category</h4>
                    <div class="uk-grid">
                        <div class="uk-width-medium-1-3"><label>Category</label></div>
                        <div class="uk-width-medium-2-3">{{$item->s1_s1}}</div>
                    </div>
                    <h4 class="navitas-text">About the complaint</h4>
                    <div class="uk-grid">
                        <div class="uk-width-medium-1-3"><label>Food complained about</label></div>
                        <div class="uk-width-medium-2-3">{{$item->s1_i1}}</div>
                    </div>

                    <div class="uk-grid">
                        <div class="uk-width-medium-1-3"><label>Date/time of purchase</label></div>
                        <div class="uk-width-medium-2-3">{{$item->s1_i2}}</div>
                    </div>

                    <div class="uk-grid">
                        <div class="uk-width-medium-1-3"><label>Incident details</label></div>
                        <div class="uk-width-medium-2-3">{{$item->s1_t1}}</div>
                    </div>
                    <h4 class="navitas-text">Complainant</h4>
                    <div class="uk-grid">
                        <div class="uk-width-medium-1-3"><label>Name</label></div>
                        <div class="uk-width-medium-2-3">{{$item->s1_i3}}</div>
                    </div>

                    <div class="uk-grid">
                        <div class="uk-width-medium-1-3"><label>Address</label></div>
                        <div class="uk-width-medium-2-3">{{$item->s1_i4}}</div>
                    </div>

                    <div class="uk-grid">
                        <div class="uk-width-medium-1-3"><label>Telephone</label></div>
                        <div class="uk-width-medium-2-3">{{$item->s1_i5}}</div>
                    </div>

                    <div class="uk-grid">
                        <div class="uk-width-medium-1-3"><label>Email address</label></div>
                        <div class="uk-width-medium-2-3">{{$item->s1_i6}}</div>
                    </div>

                    <h4 class="navitas-text">Supplier of food</h4>

                    <div class="uk-grid">
                        <div class="uk-width-medium-1-3"><label>In house</label></div>
                        <div class="uk-width-medium-2-3">{{$item->s2_s0}}</div>
                    </div>

                    <div class="uk-grid">
                        <div class="uk-width-medium-1-3"><label>Batch number/coding</label></div>
                        <div class="uk-width-medium-2-3">{{$item->s2_i1}}</div>
                    </div>

                    <div class="uk-grid">
                        <div class="uk-width-medium-1-3"><label>Date coding</label></div>
                        <div class="uk-width-medium-2-3">{{$item->s2_i2}}</div>
                    </div>

                    <div class="uk-grid">
                        <div class="uk-width-medium-1-3"><label>Delivery date</label></div>
                        <div class="uk-width-medium-2-3">{{$item->s2_i3}}</div>
                    </div>

                    <h4 class="navitas-text">Complete if foreign body found</h4>

                    <div class="uk-grid">
                        <div class="uk-width-medium-1-3"><label>Has customer surrendered food/object</label></div>
                        <div class="uk-width-medium-2-3">{{$item->s2_s1}}</div>
                    </div>

                    <div class="uk-grid">
                        <div class="uk-width-medium-1-3"><label>Object colour</label></div>
                        <div class="uk-width-medium-2-3">{{$item->s2_i4}}</div>
                    </div>

                    <div class="uk-grid">
                        <div class="uk-width-medium-1-3"><label>Object size:</label></div>
                        <div class="uk-width-medium-2-3">{{$item->s2_i5}}</div>
                    </div>

                    <div class="uk-grid">
                        <div class="uk-width-medium-1-3"><label>Object weight</label></div>
                        <div class="uk-width-medium-2-3">{{$item->s2_i6}}</div>
                    </div>

                    <div class="uk-grid">
                        <div class="uk-width-medium-1-3"><label>Possible identity</label></div>
                        <div class="uk-width-medium-2-3">{{$item->s2_i7}}</div>
                    </div>

                    <div class="uk-grid">
                        <div class="uk-width-medium-1-3"><label>Where exactly found in food</label></div>
                        <div class="uk-width-medium-2-3">{{$item->s2_i8}}</div>
                    </div>
                    <h4 class="navitas-text">Complete if alleged illness occurred</h4>

                    <div class="uk-grid">
                        <div class="uk-width-medium-1-3"><label>Has alleged illness occurred?</label></div>
                        <div class="uk-width-medium-2-3">{{$item->s3_s0}}</div>
                    </div>

                    <div class="uk-grid">
                        <div class="uk-width-medium-1-3"><label> Food suspected to have caused illness </label></div>
                        <div class="uk-width-medium-2-3">{{$item->s3_i1}}</div>
                    </div>

                    <div class="uk-grid">
                        <div class="uk-width-medium-1-3"><label> Any other food purchased and eaten during the visit </label></div>
                        <div class="uk-width-medium-2-3">{{$item->s3_i2}}</div>
                    </div>

                    <div class="uk-grid">
                        <div class="uk-width-medium-1-3"><label> Symptoms of complaint </label></div>
                        <div class="uk-width-medium-2-3">{{$item->s3_i3}}</div>
                    </div>

                    <div class="uk-grid">
                        <div class="uk-width-medium-1-3"><label> Date symptoms began </label></div>
                        <div class="uk-width-medium-2-3">{{$item->s3_i4}}</div>
                    </div>

                    <div class="uk-grid">
                        <div class="uk-width-medium-1-3"><label> Date symptoms ceased </label></div>
                        <div class="uk-width-medium-2-3">{{$item->s3_i5}}</div>
                    </div>

                    <div class="uk-grid">
                        <div class="uk-width-medium-1-3"><label> Have they visited the doctors? </label></div>
                        <div class="uk-width-medium-2-3">{{$item->s3_s1}}</div>
                    </div>

                    <div class="uk-grid">
                        <div class="uk-width-medium-1-3"><label>Have they given a stool sample? </label></div>
                        <div class="uk-width-medium-2-3">{{$item->s3_s2}}</div>
                    </div>

                    <div class="uk-grid">
                        <div class="uk-width-medium-1-3"><label>If a stool sample is available, please state:</label></div>
                        <div class="uk-width-medium-2-3">{{$item->s3_t1}}</div>
                    </div>
                </div>
            </div>
        </section>
     </div>
</div>
@endsection