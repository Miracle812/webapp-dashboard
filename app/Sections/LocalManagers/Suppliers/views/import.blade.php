@extends('newlayout.base')
@section('title')
    @parent
    :: {{$sectionName}} - {{$actionName}}
@endsection
@section('content')

    <div id="page_content">
        <div id="page_content_inner">

            <h2 class="heading_b uk-margin-bottom">{{$sectionName}}</h2>

            <?php /*@include('breadcrumbs',['data' => $breadcrumbs]) */?>

            <div class="md-card">

                <div class="md-card-toolbar">
                    <h3 class="md-card-toolbar-heading-text large">
                        {{$sectionName}} - {{$actionName}}
                    </h3>
                </div>

                <div class="md-card-content">

                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="<?= csrf_token() ?>"/>

                        <div class="uk-grid">
                            <div class="uk-width-medium-1-6">
                                Get XLS scheme
                            </div>
                            <div class="uk-width-medium-5-6">
                                <a href="{{URL::to('/download/suppliers_data_scheme.xls')}}" class="font-bold">suppliers_data_scheme.xls</a>
                            </div>
                        </div>

                        <hr class="md-hr" />

                        <div class="uk-grid">
                            <div class="uk-width-medium-1-6">
                                Get XLSX scheme
                            </div>
                            <div class="uk-width-medium-5-6">
                                <a href="{{URL::to('/download/suppliers_data_scheme.xlsx')}}" class="font-bold">suppliers_data_scheme.xlsx</a>
                            </div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-medium-1-6">
                                <p class="uk-text-warning font-bold">Notice!</p>
                            </div>
                            <div class="uk-width-medium-5-6">
                                1) Don't edit rows with columns threads names (i.e.: name, description...)<br>
                                2) Replace "example data" with your data from second row.<br>
                                3) In products cell, please enter products separate by comma.
                            </div>
                        </div>

                        <hr class="md-hr" />

                        <div class="uk-grid">
                            <div class="uk-width-medium-1-6">
                                File input
                            </div>
                            <div class="uk-width-medium-5-6">
                                <div class="uk-form-file md-btn md-btn-primary">
                                    Select
                                    <input type="file" name="import" />
                                </div>
                                <p>Valid Files Types (extensions): xls, xlsx.</p>
                            </div>
                        </div>

                        <hr class="md-hr" />

                        <div class="uk-grid uk-text-right">
                            <div class="uk-width-1-1">
                                <button class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light" type="submit">Import data</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
@endsection