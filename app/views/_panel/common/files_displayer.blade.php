<?php
$html = '';
if($files->count()) {
    foreach ($files as $file) {
        $name = $file->file_name;
        $ext = pathinfo($name, PATHINFO_EXTENSION);

        $href = '#';
        $short = (strlen($name) > 20) ? substr($name, 0, 20) . '_' : $name;
        $images = false;
        switch ($ext) {
            case 'jpg':case 'jpeg':case 'png':case 'gif':case 'bmp':
            $ico = 'fa-file-image-o';
            $href = \URL::to($file->file_path . $name);
            $images = true;
            break;
            case 'doc':
            case 'docx':
            case 'odt':
            case 'rtf':
            $ico = 'fa-file-word-o';
            break;
            case 'xls':
            case 'xlsx':
            $ico = 'fa-file-excel-o';
            break;
            case 'txt':
            $ico = 'fa-file-text-o';
            break;
            case 'pdf':
            $ico = 'fa-file-pdf-o';
            break;
            default :
            return '';
            break;
        }

        $html .= '
        <div class="col-sm-2 m-b">
            <a data-toggle="dropdown" class="btn btn-default tooltip-link" href="' . $href . '" title = "' . $short . '.' . $ext . '">
                <i class="fa fa-5x ' . $ico . '"></i>
            </a>
            <ul class="dropdown-menu">'.
                ($images ? '<li><a  class="form-file-display" href="'.\URL::to($href).'"><span  class="text-primary"><i class="fa fa-search m-r"></i></span>Display</a></a></li>':'').
                '<li><a id="form-file-download" href="/sys-files-uploader/file/download/' . $file->id . '"><span  class="text-success"><i class="fa fa-download m-r"></i></span>Download</a></a></li>
            </ul>
        </div>';
    }
}
else
{
    $html = '<h4>No files</h4>';
}
?>

<div class="row">
    <div class="col-sm-12 b-b">
        {{$html}}
    </div>
</div>

@section("js")
    @parent
    {{ Basset::show("package_gallery.js") }}
    <script>
        $(document).ready(function(){
            $( 'a.form-file-display' ).imageLightbox();
        })
    </script>
@endsection