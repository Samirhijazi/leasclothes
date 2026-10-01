@extends('layout.main') 
@section('content')

@if(empty($product_name))
<div class="alert alert-danger alert-dismissible text-center">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    {{'No Data exists within this date range!'}}
</div>
@endif

<section class="forms">
    <div class="container-fluid">
        <div class="card" style='padding-bottom:10px'>
            <div class="card-header mt-2">
                <h3 class="text-center">{{trans('file.Sale Report')}}</h3>
            </div>

            {!! Form::open(['route' => 'report.sale', 'method' => 'post']) !!}
            {!! Form::close() !!}

            <div class="table-responsive">
                <table id="report-table" class="table table-hover">
                    <thead>
                        <tr>
                            <th class="not-exported"></th>
                            <th>{{trans('file.Product Name')}}</th>
                            <th>Cost Product</th>
                            <th>{{trans('file.Sold Qty')}}</th>
                            <th>{{trans('file.Sold Amount')}}</th>
                            <th>{{trans('file.In Stock')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($product_name))
                        @foreach($product_id as $key => $pro_id)
                        <?php
                            $cost_price = DB::table('products')->where('id', $pro_id)->first();
                            $sold_price = DB::table('product_sales')
                                ->where('product_id', $pro_id)
                                ->whereBetween('created_at', [$start_date, $end_date])
                                ->sum('total');

                            $product_sale_data = DB::table('product_sales')
                                ->where('product_id', $pro_id)
                                ->whereBetween('created_at', [$start_date, $end_date])
                                ->get();

                            $sold_qty = 0;
                            foreach ($product_sale_data as $product_sale) {
                                $unit = DB::table('units')->find($product_sale->sale_unit_id);
                                if ($unit) {
                                    $sold_qty += ($unit->operator == '*') ? $product_sale->qty * $unit->operation_value : $product_sale->qty / $unit->operation_value;
                                } else {
                                    $sold_qty += $product_sale->qty;
                                }
                            }
                        ?>
                        <tr>
                            <td>{{$key}}</td>
                            <td>{{$product_name[$key]}}</td>
                            <td>{{number_format((float)$cost_price->cost, 2, '.', '')}}</td>
                            <td>{{$sold_qty}}</td>
                            <td>{{number_format((float)$sold_price, 2, '.', '')}}</td>
                            <td>{{$product_qty[$key]}}</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>Total</th>
                            <th id="cost">0.00</th>
                            <th id="total_qty">0</th>
                            <th id="price">0.00</th>
                            <th>0</th>
                        </tr>
                        <!--<tr>-->
                        <!--    <th></th>-->
                        <!--    <th>Revenue</th>-->
                        <!--    <th colspan="2"></th>-->
                        <!--    <th id="revenue">0.00</th>-->
                        <!--    <th></th>-->
                        <!--</tr>-->
                        <tr style="background:#f5b928">
                            <th></th>
                            <th>Profit (Gain)</th>
                            <th colspan="2"></th>
                            <th id="gain">0.00</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $("ul#report").siblings('a').attr('aria-expanded','true');
    $("ul#report").addClass("show");
    $("ul#report #sale-report-menu").addClass("active");

    $('#warehouse_id').val($('input[name="warehouse_id_hidden"]').val());
    $('.selectpicker').selectpicker('refresh');

    $('#report-table').DataTable({
        "order": [],
        'language': {
            'lengthMenu': '_MENU_ {{trans("file.records per page")}}',
            "info": '{{trans("file.Showing")}} _START_ - _END_ (_TOTAL_)',
            "search": '{{trans("file.Search")}}',
            'paginate': {
                'previous': '{{trans("file.Previous")}}',
                'next': '{{trans("file.Next")}}'
            }
        },
        'columnDefs': [{
                "orderable": false,
                'targets': 0
            },
            {
                'checkboxes': {
                    'selectRow': true
                },
                'targets': 0
            }
        ],
        'select': {
            style: 'multi',
            selector: 'td:first-child'
        },
        'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
        dom: '<"row"lfB>rtip',
        buttons: [
            {
                extend: 'pdf',
                text: '{{trans("file.PDF")}}',
                exportOptions: { columns: ':visible:not(.not-exported)', rows: ':visible' },
                action: function(e, dt, button, config) {
                    datatable_sum(dt);
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, button, config);
                },
                footer: true
            },
            {
                extend: 'csv',
                text: '{{trans("file.CSV")}}',
                exportOptions: { columns: ':visible:not(.not-exported)', rows: ':visible' },
                action: function(e, dt, button, config) {
                    datatable_sum(dt);
                    $.fn.dataTable.ext.buttons.csvHtml5.action.call(this, e, dt, button, config);
                },
                footer: true
            },
            {
                extend: 'print',
                text: '{{trans("file.Print")}}',
                exportOptions: { columns: ':visible:not(.not-exported)', rows: ':visible' },
                action: function(e, dt, button, config) {
                    datatable_sum(dt);
                    $.fn.dataTable.ext.buttons.print.action.call(this, e, dt, button, config);
                },
                footer: true
            }
        ],
        drawCallback: function () {
            var api = this.api();
            datatable_sum(api);
        }
    });

    function datatable_sum(dt_selector) {
        let totalCost = dt_selector.column(2, { page: 'current' }).data().sum().toFixed(2);
        let totalQty = dt_selector.column(3, { page: 'current' }).data().sum();
        let totalPrice = dt_selector.column(4, { page: 'current' }).data().sum().toFixed(2);
        
        let revenue = (totalPrice - totalCost).toFixed(2);
        let gain = revenue; 

        $("#cost").html(totalCost);
        $("#total_qty").html(totalQty);
        $("#price").html(totalPrice);
        $("#revenue").html(revenue);
        $("#gain").html(gain);
    }

    $(".daterangepicker-field").daterangepicker({
        callback: function(startDate, endDate, period){
            var start_date = startDate.format('YYYY-MM-DD');
            var end_date = endDate.format('YYYY-MM-DD');
            var title = start_date + ' To ' + end_date;
            $(this).val(title);
            $('input[name="start_date"]').val(start_date);
            $('input[name="end_date"]').val(end_date);
        }
    });
</script>

@endsection
