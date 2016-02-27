@if(isset($package['files_upload']))
    {{ Basset::show('package_uploadifive.css') }}
@endif
@if($form->signature)
    {{ Basset::show('package_signatures.css') }}
@endif
@if(isset($package['datetimepicker']))
    {{ Basset::show('package_datetimepicker.css') }}
@endif