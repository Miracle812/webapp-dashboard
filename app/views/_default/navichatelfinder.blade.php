<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>elFinder 2.0</title>

    {{ HTML::style("//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css") }}

    <link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/themes/smoothness/jquery-ui.css">

    {{ HTML::script("packages/barryvdh/laravel-elfinder/js/elfinder.min.js")}}
    {{ HTML::style("/packages/barryvdh/laravel-elfinder/css/elfinder.min.css") }}
    {{ HTML::style("/packages/barryvdh/laravel-elfinder/css/theme.css") }}

    @if(\File::exists(public_path().'packages/barryvdh/laravel-elfinder/js/i18n/elfinder.'.\Auth::user()->lang.'.js'))
        {{ HTML::script("packages/barryvdh/laravel-elfinder/js/i18n/elfinder.".\Auth::user()->lang.".js")}}
    @endif
    <script type="text/javascript">
        $(document).ready(function () {
            if(!ElfInstance){
                ElfInstance = $('#elfinder').elfinder({
                    url : '{{URL::action('Barryvdh\Elfinder\ElfinderController@showConnector')}}',
                    title: 'Navitas Filemanager',
                    height: 450,
                    resizable: 'no',
                    lang: '{{\Auth::user()->lang}}', // locale

                    uiOptions : {
                        toolbar : [
                            ['mkdir', 'upload'],
                            ['open', 'download', 'getfile', 'rename'],
                            ['rm']
                        ]},
                    contextmenu : {
                        files  : [
                            'getfile', '|','open', 'quicklook', 'editimage',
                        ]
                    },
                    closeOnEditorCallback: true,
                    customData: {
                        _token: '<?= csrf_token() ?>'
                    },
                    resizable: false,
                    getFileCallback: function (file) {
                         $('#ajaxModalElfinder').modal('hide');
                        processSelectedFile(file);
                    }
                }).elfinder('instance');
            }
        });
    </script>
</head>
<body>
<div id="elfinder"></div>
</body>
</html>