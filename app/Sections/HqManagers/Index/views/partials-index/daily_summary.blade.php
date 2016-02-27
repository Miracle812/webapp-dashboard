<ul class="nav m-b">
@include('Sections\HqManagers\Index::partials-index.daily-summary-hexes._food-incidents')
@include('Sections\HqManagers\Index::partials-index.daily-summary-hexes._noncompliant-temps')
@include('Sections\HqManagers\Index::partials-index.daily-summary-hexes._navinotes')

@include('Sections\HqManagers\Index::partials-index.daily-summary-hexes._online-visitors')
@include('Sections\HqManagers\Index::partials-index.daily-summary-hexes._online-users')
@include('Sections\HqManagers\Index::partials-index.daily-summary-hexes._dormant-users')
</ul>
<style>
    .aside-xl .scrollable {max-height: 400px}
</style>