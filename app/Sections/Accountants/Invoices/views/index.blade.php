@section('title') Home :: @parent Invoices @stop
@section('content')
    <div id="page_content" class="hidden-print">
        <div id="page_content_inner">
            <h4 class="heading_a uk-margin-bottom">Invoices list</h4>
            <div class="md-card uk-margin-medium-bottom">
                <div class="md-card-content">
                    <table id="dt_ajax" data-src="/invoices/datatable.json" class="uk-table" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Reference</th>
                            <th>Client</th>
                            <th>Status</th>
                            <th>Due</th>
                            <th>Due date</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>Invoice</th>
                            <th>Reference</th>
                            <th>Client</th>
                            <th>Status</th>
                            <th>Due</th>
                            <th>Due date</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <ul class="md-list md-list-outside invoices_list" id="invoices_list">
            <li>
                <a href="#" class="md-list-content" data-invoice-id="1">
                    <span class="md-list-heading uk-text-truncate">Invoice 21/2015 <span class="uk-text-small uk-text-muted">(13 May)</span></span>
                    <span class="uk-text-small uk-text-muted">Hessel-Reichert</span>
                </a>
            </li>
        </ul>
    </div>
    <script id="invoice_template" type="text/x-handlebars-template">
        <div class="md-card-toolbar">
            <div class="md-card-toolbar-actions hidden-print">
                <i class="md-icon material-icons" id="invoice_print">&#xE8ad;</i>

                <div class="md-card-dropdown" data-uk-dropdown>
                    <i class="md-icon material-icons">&#xE5D4;</i>
                    <div class="uk-dropdown uk-dropdown-flip uk-dropdown-small">
                        <ul class="uk-nav">
                            <li><a href="#">Archive</a></li>
                            <li><a href="#" class="uk-text-danger">Remove</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <h3 class="md-card-toolbar-heading-text large">
                Invoice @{{invoice_id.invoice_number}}
            </h3>
        </div>
        <div class="md-card-content">
            <div class="uk-margin-medium-bottom">
                <span class="uk-text-muted uk-text-small uk-text-italic">Date:</span> @{{invoice_id.invoice_date}}
                <br/>
                <span class="uk-text-muted uk-text-small uk-text-italic">Due Date:</span> @{{invoice_id.invoice_due_date}}
            </div>
            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-small-3-5">
                    <div class="uk-margin-bottom">
                        <span class="uk-text-muted uk-text-small uk-text-italic">From:</span>
                        <address>
                            <p><strong>@{{invoice_id.invoice_from_company}}</strong></p>
                            <p>@{{invoice_id.invoice_from_address_1}}</p>
                            <p>@{{invoice_id.invoice_from_address_2}}</p>
                        </address>
                    </div>
                    <div class="uk-margin-medium-bottom">
                        <span class="uk-text-muted uk-text-small uk-text-italic">To:</span>
                        <address>
                            <p><strong>@{{invoice_id.invoice_to_company}}</strong></p>
                            <p>@{{invoice_id.invoice_to_address_1}}</p>
                            <p>@{{invoice_id.invoice_to_address_2}}</p>
                        </address>
                    </div>
                </div>
                <div class="uk-width-small-2-5">
                    <span class="uk-text-muted uk-text-small uk-text-italic">Total:</span>
                    <p class="heading_b uk-text-success">@{{invoice_id.invoice_total_value}}</p>
                    <p class="uk-text-small uk-text-muted uk-margin-top-remove">Incl. VAT - @{{invoice_id.invoice_vat_value}}</p>
                </div>
            </div>
            <div class="uk-grid uk-margin-large-bottom">
                <div class="uk-width-1-1">
                    <table class="uk-table">
                        <thead>
                        <tr class="uk-text-upper">
                            <th>Description</th>
                            <th>Rate</th>
                            <th class="uk-text-center">Hours</th>
                            <th class="uk-text-center">Vat</th>
                            <th class="uk-text-center">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @{{#each invoice_id.invoice_services}}
                        <tr class="uk-table-middle">
                            <td>
                                <span class="uk-text-large">@{{ service_name }}</span><br/>
                                <span class="uk-text-muted uk-text-small">@{{ service_description }}</span>
                            </td>
                            <td>
                                @{{ service_rate }}
                            </td>
                            <td class="uk-text-center">
                                @{{ service_hours }}
                            </td>
                            <td class="uk-text-center">
                                @{{ service_vat }}
                            </td>
                            <td class="uk-text-center">
                                @{{ service_total }}
                            </td>
                        </tr>
                        @{{/each}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </script>
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
    <script src="/newassets/packages/handlebars/handlebars.min.js"></script>
    <script src="/newassets/js/custom/handlebars_helpers.min.js"></script>
    <!--  invoices functions -->
    <script src="/newassets/js/pages/page_invoices.min.js"></script>
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
                            {data: 0, name:'xero_invoices.InvoiceNumber'},
                            {data: 1, name:'xero_invoices.Reference'},
                            {data: 2, name:'xero_contacts.Name'},
                            {data: 3, name:'xero_invoices.Status'},
                            {data: 4, name:'xero_invoices.Total'},
                            {data: 5, name:'xero_invoices.DueDate'},
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