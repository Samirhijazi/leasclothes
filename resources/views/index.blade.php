@extends('layout.main')
@section('content')

@if(session()->has('not_permitted'))
<div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert"
    aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
@endif
@if(session()->has('message'))
<div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert"
    aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
@endif
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="row brand-text float-left mt-4">
        <h3>{{trans('file.welcome')}} <span>{{Auth::user()->name}}</span> </h3>
      </div>
      <div class="filter-toggle btn-group" style="z-index:1;left:17px">
        <button class="btn btn-secondary date-btn" data-start_date="{{date('Y-m-d')}}"
          data-end_date="{{date('Y-m-d')}}">{{trans('file.Today')}}</button>
        <button class="btn btn-secondary date-btn" data-start_date="{{date('Y-m-d', strtotime(' -7 day'))}}"
          data-end_date="{{date('Y-m-d')}}">{{trans('file.Last 7 Days')}}</button>
        <button class="btn btn-secondary date-btn active" data-start_date="{{date('Y').'-'.date('m').'-'.'01'}}"
          data-end_date="{{date('Y-m-d')}}">{{trans('file.This Month')}}</button>
        <button class="btn btn-secondary date-btn" data-start_date="{{date('Y').'-01'.'-01'}}"
          data-end_date="{{date('Y').'-12'.'-31'}}">{{trans('file.This Year')}}</button>
      </div>
    </div>
    <div class="col-md-12">
      <div class="row">
        <div class="col mt-2">
          <table border="0" cellspacing="5" cellpadding="5" class="p-2 my-2">
            <tbody>
              <tr>
                <td>{{ __('file.From') }}:</td>
                <td><input type="date" class="form-control" max="{{ now()->subDay()->format('Y-m-d') }}"
                    id="start_date"></td>
                <td>{{ __('file.To') }}:</td>
                <td><input type="date" class="form-control" id="end_date" max="{{ now()->format('Y-m-d') }}"></td>
                <td><input class="btn btn-primary filter" type="submit" value="{{ __('file.Filter') }}"></td>
              </tr>
            </tbody>
          </table>
        </div>
        {{-- <div class="col mt-3">
          <div class="form-group row">
            <label class="d-tc mt-2"><strong>{{trans('file.Choose Warehouse')}}</strong> &nbsp;</label>
            <div class="d-tc">
              <select id="warehouse_id" name="warehouse_id" class="form-control" data-live-search="true"
                data-live-search-style="begins">
                <option value="0">{{trans('file.All Warehouse')}}</option>
                @foreach(App\Warehouse::get() as $warehouse)
                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div> --}}
      </div>
    </div>
  </div>
</div>
<!-- Counts Section -->
<section class="dashboard-counts">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 form-group">
        <div class="row">
          <!-- Count item widget-->
          <div class="col-sm-3">
            <div class="wrapper count-title text-center">
              <div class="icon"><i class="ion-connection-bars" style="color: #733686"></i></div>
              <div class="name"><strong style="color: #733686">{{ trans('file.revenue') }}</strong></div>
              <div class="count-number revenue-data">{{number_format((float)$revenue, 2, '.', '')}}</div>
            </div>
          </div>
          <!-- Count item widget-->
          {{-- <div class="col-sm-3">
            <div class="wrapper count-title text-center">
              <div class="icon"><i class="ion-cash" style="color: #ffc107"></i></div>
              <div class="name"><strong style="color: #ffc107">Purchase</strong></div>
              <div class="count-number purchase-item-data">{{number_format((float)$purchase, 2, '.', '')}}</div>
            </div>
          </div> --}}
          <!-- Count item widget-->
          <!--<div class="col-sm-3">-->
          <!--  <div class="wrapper count-title text-center">-->
          <!--    <div class="icon"><i class="ion-arrow-return-left" style="color: #ff8952"></i></div>-->
          <!--    <div class="name"><strong style="color: #ff8952">{{trans('file.Sale Return')}}</strong></div>-->
          <!--    <div class="count-number return-data">{{number_format((float)$return, 2, '.', '')}}</div>-->
          <!--  </div>-->
          <!--</div>-->
          <!-- Count item widget-->
          <div class="col-sm-3">
            <div class="wrapper count-title text-center">
              <div class="icon"><i class="ion-minus" style="color: #ff0000"></i></div>
              <div class="name"><strong style="color: #ff0000">{{trans('file.Expense')}}</strong></div>
              <div class="count-number expense-data">{{number_format((float)$expense, 2, '.', '')}}</div>
            </div>
          </div>
          <!-- Count item widget-->
          {{-- <div class="col-sm-3">
            <div class="wrapper count-title text-center">
              <div class="icon"><i class="ion-star" style="color: #a7c700"></i></div>
              <div class="name"><strong style="color: #a7c700">Boost</strong></div>
              <div class="count-number boost-data">{{number_format((float)$boost, 2, '.', '')}}</div>
            </div>
          </div> --}}
          <!-- Count item widget-->
          {{-- <div class="col-sm-3">
            <div class="wrapper count-title text-center">
              <div class="icon"><i class="ion-cash" style="color: #28a745"></i></div>
              <div class="name"><strong style="color: #28a745">Paid</strong></div>
              <div class="count-number paid-item-data">{{number_format($paid, 2, '.', '')}}</div>
            </div>
          </div> --}}
          <!-- Count item widget-->
          <div class="col-sm-3">
            <div class="wrapper count-title text-center">
              <div class="icon"><i class="ion-pricetag" style="color: #4702e5"></i></div>
              <div class="name"><strong style="color: #4702e5">Cost items</strong></div>
              <div class="count-number cost-item-data">{{number_format((float)$cost_item, 2, '.', '')}}</div>
            </div>
          </div>
          <!-- Count item widget-->

          <div class="col-sm-3">
            <div class="wrapper count-title text-center">
              <div class="icon"><i class="ion-pie-graph" style="color: #297ff9"></i></div>
              <div class="name"><strong style="color: #297ff9">{{trans('file.profit')}}</strong></div>
              <div class="count-number profit-data">{{number_format((float)$profit, 2, '.', '')}}</div>
            </div>
          </div>

          {{-- <div class="col-sm-3">
            <div class="wrapper count-title text-center">
              <div class="icon"><i class="ion-pause" style="color: #d653ff"></i></div>
              <div class="name"><strong style="color: #d653ff">Returns</strong></div>
              <div class="count-number return-data">{{number_format((float)$return, 2, '.',
                '')}}</div>
            </div>
          </div> --}}
          <!-- Count item widget-->
          {{-- <div class="col-sm-3">
            <div class="wrapper count-title text-center">
              <div class="icon"><i class="ion-arrow-return-right" style="color: #00c689"></i></div>
              <div class="name"><strong style="color: #00c689">{{trans('file.Purchase Return')}}</strong></div>
              <div class="count-number purchase_return-data">{{number_format((float)$purchase_return, 2, '.', '')}}
              </div>
            </div>
          </div> --}}
          <!-- Count item widget-->
          {{-- <div class="col-sm-3">
            <div class="wrapper count-title text-center">
              <div class="icon"><i class="ion-pie-graph" style="color: #297ff9"></i></div>
              <div class="name"><strong style="color: #297ff9">{{trans('file.profit')}}</strong></div>
              <div class="count-number profit-data">{{number_format((float)$profit, 2, '.', '')}}</div>
            </div>
          </div> --}}
          <!-- Count item widget-->
          {{-- <div class="col-sm-3">
            <div class="wrapper count-title text-center">
              <div class="icon"><i class="ion-pie-graph" style="color: #297ff9"></i></div>
              <div class="name"><strong style="color: #297ff9">{{trans('file.Cash')}}</strong></div>
              <div class="count-number cash-data">{{number_format((float) $cash, 2, '.', '')}}</div>
            </div>
          </div> --}}
          {{--
        </div>
      </div> --}}
      {{-- <div class="col-md-7 mt-4">
        <div class="card line-chart-example">
          <div class="card-header d-flex align-items-center">
            <h4>{{trans('file.Cash Flow')}}</h4>
          </div>
          <div class="card-body">
            @php
            if($general_setting->theme == 'default.css'){
            $color = '#733686';
            $color_rgba = 'rgba(115, 54, 134, 0.8)';
            }
            elseif($general_setting->theme == 'green.css'){
            $color = '#2ecc71';
            $color_rgba = 'rgba(46, 204, 113, 0.8)';
            }
            elseif($general_setting->theme == 'blue.css'){
            $color = '#3498db';
            $color_rgba = 'rgba(52, 152, 219, 0.8)';
            }
            elseif($general_setting->theme == 'dark.css'){
            $color = '#34495e';
            $color_rgba = 'rgba(52, 73, 94, 0.8)';
            }
            @endphp

          </div>
        </div>
      </div> --}}
    </div>

    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header d-flex align-items-center">
              <h4>{{trans('file.yearly report')}}</h4>
            </div>
            <div class="card-body">
              <canvas id="saleChart" data-sale_chart_value="{{json_encode($yearly_sale_amount)}}"
                data-purchase_chart_value="{{json_encode($yearly_purchase_amount)}}"
                data-label1="{{trans('file.Purchased Amount')}}" data-label2="{{trans('file.Sold Amount')}}"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-7">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h4>{{trans('file.Recent Transaction')}}</h4>
              <div class="right-column">
                <div class="badge badge-primary">{{trans('file.latest')}} 5</div>
              </div>
            </div>
            <ul class="nav nav-tabs" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" href="#sale-latest" role="tab" data-toggle="tab">{{trans('file.Sale')}}</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#purchase-latest" role="tab" data-toggle="tab">{{trans('file.Purchase')}}</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#quotation-latest" role="tab"
                  data-toggle="tab">{{trans('file.Quotation')}}</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#payment-latest" role="tab" data-toggle="tab">{{trans('file.Payment')}}</a>
              </li>
            </ul>

            <div class="tab-content">
              <div role="tabpanel" class="tab-pane fade show active" id="sale-latest">
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>{{trans('file.date')}}</th>
                        <th>{{trans('file.reference')}}</th>
                        <th>{{trans('file.customer')}}</th>
                        <th>{{trans('file.status')}}</th>
                        <th>{{trans('file.grand total')}}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($recent_sale as $sale)
                      <?php $customer = DB::table('customers')->find($sale->customer_id); ?>
                      <tr>
                        <td>{{ date($general_setting->date_format, strtotime($sale->created_at->toDateString())) }}
                        </td>
                        <td>{{$sale->reference_no}}</td>
                        @if (isset($customer->name))
                        <td>{{ $customer->name }}</td>
                        @else
                        <td></td>
                        @endif
                        @if($sale->sale_status == 1)
                        <td>
                          <div class="badge badge-success">{{trans('file.Completed')}}</div>
                        </td>
                        @elseif($sale->sale_status == 2)
                        <td>
                          <div class="badge badge-danger">{{trans('file.Pending')}}</div>
                        </td>
                        @else
                        <td>
                          <div class="badge badge-warning">{{trans('file.Draft')}}</div>
                        </td>
                        @endif
                        <td>{{$sale->grand_total}}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
              <div role="tabpanel" class="tab-pane fade" id="purchase-latest">
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>{{trans('file.date')}}</th>
                        <th>{{trans('file.reference')}}</th>
                        <th>{{trans('file.Supplier')}}</th>
                        <th>{{trans('file.status')}}</th>
                        <th>{{trans('file.grand total')}}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($recent_purchase as $purchase)
                      <?php $supplier = DB::table('suppliers')->find($purchase->supplier_id); ?>
                      <tr>
                        <td>{{date($general_setting->date_format, strtotime($purchase->created_at->toDateString())) }}
                        </td>
                        <td>{{$purchase->reference_no}}</td>
                        @if($supplier)
                        <td>{{$supplier->name}}</td>
                        @else
                        <td>N/A</td>
                        @endif
                        @if($purchase->status == 1)
                        <td>
                          <div class="badge badge-success">Recieved</div>
                        </td>
                        @elseif($purchase->status == 2)
                        <td>
                          <div class="badge badge-success">Partial</div>
                        </td>
                        @elseif($purchase->status == 3)
                        <td>
                          <div class="badge badge-danger">Pending</div>
                        </td>
                        @else
                        <td>
                          <div class="badge badge-danger">Ordered</div>
                        </td>
                        @endif
                        <td>{{$purchase->grand_total}}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
              <div role="tabpanel" class="tab-pane fade" id="quotation-latest">
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>{{trans('file.date')}}</th>
                        <th>{{trans('file.reference')}}</th>
                        <th>{{trans('file.customer')}}</th>
                        <th>{{trans('file.status')}}</th>
                        <th>{{trans('file.grand total')}}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($recent_quotation as $quotation)
                      <?php $customer = DB::table('customers')->find($quotation->customer_id); ?>
                      <tr>
                        <td>{{date($general_setting->date_format, strtotime($quotation->created_at->toDateString()))
                          }}
                        </td>
                        <td>{{$quotation->reference_no}}</td>
                        <td>{{$customer->name}}</td>
                        @if($quotation->quotation_status == 1)
                        <td>
                          <div class="badge badge-danger">Pending</div>
                        </td>
                        @else
                        <td>
                          <div class="badge badge-success">Sent</div>
                        </td>
                        @endif
                        <td>{{$quotation->grand_total}}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
              <div role="tabpanel" class="tab-pane fade" id="payment-latest">
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>{{trans('file.date')}}</th>
                        <th>{{trans('file.reference')}}</th>
                        <th>{{trans('file.Amount')}}</th>
                        <th>{{trans('file.Paid By')}}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($recent_payment as $payment)
                      <tr>
                        <td>{{date($general_setting->date_format, strtotime($payment->created_at->toDateString())) }}
                        </td>
                        <td>{{$payment->payment_reference}}</td>
                        <td>{{$payment->amount}}</td>
                        <td>{{$payment->paying_method}}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-5">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h4>{{trans('file.Best Seller').' '.date('F')}}</h4>
              <div class="right-column">
                <div class="badge badge-primary">{{trans('file.top')}} 5</div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>SL No</th>
                    <th>{{trans('file.Product Details')}}</th>
                    <th>{{trans('file.qty')}}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($best_selling_qty as $key=>$sale)
                  <?php $product = DB::table('products')->find($sale->product_id); ?>
                  <tr>
                    <td>{{$key + 1}}</td>
                    <td>{{$product->name}}<br>[{{$product->code}}]</td>
                    <td>{{$sale->sold_qty}}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h4>{{trans('file.Best Seller').' '.date('Y'). '('.trans('file.qty').')'}}</h4>
              <div class="right-column">
                <div class="badge badge-primary">{{trans('file.top')}} 5</div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>SL No</th>
                    <th>{{trans('file.Product Details')}}</th>
                    <th>{{trans('file.qty')}}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($yearly_best_selling_qty as $key => $sale)
                  <?php $product = DB::table('products')->find($sale->product_id); ?>
                  <tr>
                    <td>{{$key + 1}}</td>
                    {{-- <td>{{$product->name}}<br>[{{$product->code}}]</td> --}}
                    <td></td>
                    <td>{{$sale->sold_qty}}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h4>{{trans('file.Best Seller').' '.date('Y') . '('.trans('file.price').')'}}</h4>
              <div class="right-column">
                <div class="badge badge-primary">{{trans('file.top')}} 5</div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>SL No</th>
                    <th>{{trans('file.Product Details')}}</th>
                    <th>{{trans('file.grand total')}}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($yearly_best_selling_price as $key => $sale)
                  <?php $product = DB::table('products')->find($sale->product_id); ?>
                  <tr>
                    <td>{{$key + 1}}</td>
                    <td>{{$product->name ?? ''}}<br>[{{$product->code ?? ''}}]</td>
                    <td>{{number_format((float)$sale->total_price, 2, '.', '')}}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>

<script type="text/javascript">
  // Show and hide color-switcher
    $(".color-switcher .switcher-button").on('click', function() {
        $(".color-switcher").toggleClass("show-color-switcher", "hide-color-switcher", 300);
    });

    // Color Skins
    $('a.color').on('click', function() {
        /*var title = $(this).attr('title');
        $('#style-colors').attr('href', 'css/skin-' + title + '.css');
        return false;*/
        $.get('setting/general_setting/change-theme/' + $(this).data('color'), function(data) {
        });
        var style_link= $('#custom-style').attr('href').replace(/([^-]*)$/, $(this).data('color') );
        $('#custom-style').attr('href', style_link);
    });

    $(".date-btn").on("click", function() {
        $(".date-btn").removeClass("active");
        $(this).addClass("active");
        var start_date = $(this).data('start_date');
        var end_date = $(this).data('end_date');
        var warehouse_id = $("#warehouse_id").val();
        $.get('dashboard-filter/' + start_date + '/' + end_date + '/?warehouse_id=' + warehouse_id, function(data) {
            dashboardFilter(data);
        });
    });
    
    $(".filter").on("click", function() {
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        var warehouse_id = $("#warehouse_id").val();
        $.get('dashboard-filter/' + start_date + '/' + end_date + '/?warehouse_id=' + warehouse_id, function(data) {
            dashboardFilter(data);
        });
    });
    
    $("#warehouse_id").on("change", function() {
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        var warehouse_id = $("#warehouse_id").val();
        $.get('dashboard-filter/' + start_date + '/' + end_date + '/?warehouse_id=' + warehouse_id, function(data) {
            dashboardFilter(data);
        });
    });

    function dashboardFilter(data){
        $('.revenue-data').hide();
        $('.revenue-data').html(parseFloat(data[0]).toFixed(2));
        $('.revenue-data').show(500);

        $('.return-data').hide();
        $('.return-data').html(parseFloat(data[1]).toFixed(2));
        $('.return-data').show(500);
        
        $('.profit-data').hide();
        $('.profit-data').html(parseFloat(data[2]).toFixed(2));
        $('.profit-data').show(500);

        $('.purchase_return-data').hide();
        $('.purchase_return-data').html(parseFloat(data[3]).toFixed(2));
        $('.purchase_return-data').show(500);
        
        $('.expense-data').hide();
        $('.expense-data').html(parseFloat(data[4]).toFixed(2));
        $('.expense-data').show(500);
        
        $('.boost-data').hide();
        $('.boost-data').html(parseFloat(data[5]).toFixed(2));
        $('.boost-data').show(500);
        
        $('.cost-item-data').hide();
        $('.cost-item-data').html(parseFloat(data[6]).toFixed(2));
        $('.cost-item-data').show(500);
        
        $('.free-return-cost-item-data').hide();
        $('.free-return-cost-item-data').html(parseFloat(data[7]).toFixed(2));
        $('.free-return-cost-item-data').show(500);
        
        $('.purchase-item-data').hide();
        $('.purchase-item-data').html(parseFloat(data[8]).toFixed(2));
        $('.purchase-item-data').show(500);

        $('.paid-item-data').hide();
        $('.paid-item-data').html(parseFloat(data[9]).toFixed(2));
        $('.paid-item-data').show(500);
        
        $('.cash-data').hide();
        $('.cash-data').html(parseFloat(data[10]).toFixed(2));
        $('.cash-data').show(500);
    }
</script>
@endsection