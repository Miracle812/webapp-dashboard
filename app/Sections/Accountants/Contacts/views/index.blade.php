@section('title') Home :: @parent Clients @stop
@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <h4 class="heading_a uk-margin-bottom">Clients list</h4>
            <div class="md-card uk-margin-medium-bottom">
                <div class="md-card-content">
                    <table id="dt_ajax" data-src="/contacts/datatable.json" class="uk-table" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>City</th>
                            <th>DefaultCurrency</th>
                            <th>ContactStatus</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>City</th>
                            <th>DefaultCurrency</th>
                            <th>ContactStatus</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <!-- page specific plugins -->
    <!-- datatables -->
    <script src="/newassets/packages/datatables/media/js/jquery.dataTables.min.js"></script>
    <!-- datatables colVis-->
    <script src="/newassets/packages/datatables-colvis/js/dataTables.colVis.js"></script>
    <!-- datatables tableTools-->
    <script src="/newassets/packages/datatables-tabletools/js/dataTables.tableTools.js"></script>
    <!-- datatables custom integration -->
    <script src="/newassets/js/custom/datatables_uikit.min.js"></script>
    <!--  datatables functions
    <script src="newassets/js/pages/plugins_datatables.min.js"></script>
    -->
    <script>
        $(function() {
            // datatables
            altair_datatables.dt_ajax();

        });
        altair_datatables = {
            dt_ajax: function() {
                var $dt_ajax = $('#dt_ajax');
                if($dt_ajax.length) {

                    $dt_ajax.find('tfoot th').each( function() {
                        var title = $dt_ajax.find('tfoot th').eq( $(this).index() ).text();
                        $(this).html('<input type="text" class="md-input" placeholder="' + title + '" />');
                    } );
                    // reinitialize md inputs
                    altair_md.inputs();
                    var $src = $dt_ajax.data('src');
                    var dt_ajax = $dt_ajax.DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: $src,
                        columns: [
                            {data: 0, name:'xero_contacts.Name'},
                            {data: 1, name:'xero_contacts_addresses.City'},
                            {data: 2, name:'xero_contacts.DefaultCurrency'},
                            {data: 3, name:'xero_contacts.ContactStatus'}
                        ]
                    } );
                    dt_ajax.columns().every(function() {
                        var that = this;

                        $('input', this.footer()).on('keyup change', function() {
                            that
                                    .search( this.value )
                                    .draw();
                        } );
                    });
                    var tt = new $.fn.dataTable.TableTools( dt_ajax, {
                        "sSwfPath": "/newassets/packages/datatables-tabletools/swf/copy_csv_xls_pdf.swf"
                    });
                    $( tt.fnContainer() ).insertBefore( $dt_ajax.closest('.dt-uikit').find('.dt-uikit-header'));
                    $body.on('click',function(e) {
                        if($body.hasClass('DTTT_Print')) {
                            if ( !$(e.target).closest(".DTTT").length && !$(e.target).closest(".uk-table").length) {
                                var esc = $.Event("keydown", { keyCode: 27 });
                                $body.trigger(esc);
                            }
                        }
                    })
                }
            }
        }
    </script>
@endsection
@stop