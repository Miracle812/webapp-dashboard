@section('content')
<div class="m-b-md">
    <h3 class="m-b-none"><i class="i i-arrow-down3 m-r"></i>{{$sectionName}}</h3>
</div>
@include('breadcrumbs',['data' => $breadcrumbs])
<div class="row">
    <div class="col-sm-12">
        <section class="panel panel-default">
            <header class="panel-heading">
                {{$sectionName}} - {{$actionName}}
                <div class="pull-right">
                <span class="label bg-danger m-t-xs">{{$clients->count()}} Navitas clients</span>
                <span class="label bg-danger m-t-xs">{{$contacts->count()}} Xero contacts</span>
                </div>
            </header>
            <table class="table table-striped m-b-none">
                <thead>
                <tr>
                    <th width="230px">Xero contacts</th>
                    <th>Navitas clients (Headquarters)</th>
                </tr>
                </thead>
                <tbody>
                @foreach($contacts as $contact)
                    {{($contact->client() ? $contact->client()->id : null)}}
                <tr>
                    <td>{{$contact->Name}}</td>
                    <td>
                        {{Form::select('contact-'.$contact->id,([null=>'']+$clients->lists('name','id')), ($contact->clients ? implode(',',$contact->clients->lists('id')) : null), ['class'=>'form-control'])}}
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </section>
    </div>
</div>
@endsection
@section('js')
    {{ Basset::show('package_select2.js') }}
    <script>
        $(document).ready(function(){
            $('select[name^=contact-]').select2(
                {
                    placeholder: 'Please assign Navitas client',
                    allowClear: true
                }
            ).on('change',function($e){
                $target = $e.target;
                $id = $target.name.match(/\d+/);
                $.ajax({
                    url: "/billing/assigning/"+$id,
                    type: "post",
                    dataType: "json",
                    data: {client:$($target).select2('val')}
                });
            });
        });
    </script>
@endsection
@section('css')
    {{ Basset::show('package_select2.css') }}
@endsection