var oTable,  oSettings = {
    "aoColumnDefs": [
        { "iDataSort": 0, "aTargets": [ 1 ] },
        { "bVisible": false, "aTargets": [0] }
    ],
    "sDom":  '<l<"toolbarx">f<t>ip>',
    "order": [[ 1, "desc" ]],
    "bProcessing": true,
    "bScrollCollapse": true,
    "bAutoWidth": false,
    'sPaginationType': 'full_numbers',
    'oLanguage':
    {
        'sSearch': 'Search all columns:',
        'oPaginate':
        {
            'sNext': '&gt;',
            'sLast': '&gt;&gt;',
            'sFirst': '&lt;&lt;',
            'sPrevious': '&lt;'
        }
    },
    "fnRowCallback": function( ) {
        $(this).tooltip({
            selector:'[data-toggle="tooltip"],[id="data-tooltip"]'
        });
        $(this).popover({
            selector:'[data-toggle="popover"]'
        }).on('click',function(){
            $('.type-warning').parent('.popover-title').addClass('bg-warning');
            $('.type-danger').parent('.popover-title').addClass('bg-danger');
            $('.type-valid').parent('.popover-title').addClass('bg-success');
        });
    }
};

$(document).ready(function() {
  $dataTable = $('.dataTable');
  $dataTable.each(function(){
    var ajaxSource = $(this).data('source');
      oSettings.sAjaxSource  = ajaxSource;
      oTable = $(this).dataTable(oSettings);
      if($(this).is("[date-filter]") && $(this).attr('date-filter') == 'true')
      {
        $("div.toolbarx").css('float', 'right');
        $("div.toolbarx").html('<button data-toggle="collapse" href="#datatable-filter-form" class="btn btn-icon btn-primary btn-sm m-t m-r tooltip-link"  title="Date filter"><i class="fa fa-search"></i></button>');
      }
  })
});