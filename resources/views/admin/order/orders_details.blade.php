@extends('admin.index')
@section('admin')
<link href="vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">

<style>
    .submit-btn1{
         background: #001E40;
    color: #fff;
    font-size: 13px;
    padding: 5px;
    border-radius:0px 5px 5px 0px;
    outline: none;
    border: 1px solid #001E40;
    }
    .select-group .form-select{
        border-radius:5px 0px 0px 5px !important;
        padding:10px;
    }
    table th ,td{
        text-align: center;
    }
</style>


@if(session('danger'))
    <div id="dangerAlert" class="alert alert-danger">
        {{ session('danger') }}
    </div>

    <script>
        setTimeout(function() {
            $('#dangerAlert').fadeOut('fast');
        }, 3000); 
    </script>
@endif


@if(session('success'))
    <div id="successAlert" class="alert alert-success">
        {{ session('success') }}
    </div>

    <script>
        setTimeout(function() {
            $('#successAlert').fadeOut('fast');
        }, 3000); 
    </script>
@endif




<div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-6">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Orders : {{ $orders->payment_order_id }}</h4>
                </div>
            </div>
            <div class="col-6">
                <div class="page-title-box">
                    <a href="{{ route('admin.download.invoice',$orders->id) }}" class="mb-sm-0" style="background: #FFD731;color: #000;padding: 10px 20px;border-radius: 25px;float: right;margin-bottom: 10px !important;">Download Invoice</a>
                </div>
            </div>
        </div>
 
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        @php $user = App\Models\User::where('id',$orders->user_id)->first(); @endphp
                        <h4>Customer Details</h4>
                        <P>
                            <strong>Name : </strong>{{ $user->name ?? '-' }}<br/>
                            <strong>Email  : </strong>{{ $user->email ?? '-' }}<br/>
                            <strong>Mobile No. : </strong>{{ $user->phone ?? '-' }}<br/>
                        </P>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4>Order Details</h4>
                        <P>
                            <strong>Invoice No : </strong>{{ $orders->payment_order_id }}<br/>
                            <strong>Order Date  : </strong><?php echo (new DateTime($orders->created_at))->format('F j, Y'); ?><br/>
                            
                            @php $shipping_address = App\Models\Address::where('id',$orders->shipping_address)->first(); @endphp
                            <strong>Name : </strong>{{ $shipping_address->shipping_name }}<br/>
                            <strong>Email  : </strong>{{ $shipping_address->shipping_email }}<br/>
                            <strong>Mobile No. : </strong>{{ $shipping_address->shipping_phone }}<br/>
                            <strong>Address : </strong>{{ $shipping_address->shipping_address }}<br/>
                            {{ $shipping_address->city ?? '' }} - {{ $shipping_address->pincode }}

                            
                            
                        </P>
                    </div>
                </div>
            </div>
            
        </div>
                        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"> Orders</h4>
                        
                        <div class="table-responsive" style="overflow-x:scroll;">
                            <table class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Images</th> 
                                <th>Product</th>
                                <th>Color</th>
                                <th>Size</th> 
                                <th>Price</th> 
                                <th>Quantity</th> 
                                <th>Amount</th> 
                                
                            </thead>
    
    
                            <tbody>
                            @if($orders_details)	 
                                @foreach($orders_details as $key=>$item)   
                                    @php
                                        $size  = App\Models\Size::where('id', $item->size_id)->first();
                                        $color = App\Models\Color::where('id', $item->color_id)->first();
                                        $product = App\Models\Product::where('id',$item->product_id)->first();
                                        
                                        // Get the selected image index from order details
                                        $imageIndex = $item->image_index ?? 0;
                                        
                                        // Get ProductDetail to access variant images
                                        $productDetail = $item->product_detail_id 
                                            ? App\Models\ProductDetail::find($item->product_detail_id)
                                            : null;
                                        
                                        // Get variant images from ProductDetail
                                        $variantImages = $productDetail && isset($productDetail->images)
                                            ? json_decode($productDetail->images, true)
                                            : [];
                                        
                                        // Determine which image to display
                                        $displayImage = !empty($variantImages) && isset($variantImages[$imageIndex])
                                            ? $variantImages[$imageIndex]
                                            : ($product->product_img ?? null);
                                    @endphp
                                    <tr>
                                        <td> {{ $key+1 }} </td>
                                        <td>
                                            @if($displayImage)
                                                <img src="{{ url('public/variant_images/'.$displayImage) }}" style="height:100px;width:100px;object-fit:cover;" alt="">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td> 
                                            {{ $item->productname }}
                                        </td>
                                        <td>
                                            @if($color) 
                                                {{ $color->color ?? '-' }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($size)
                                                {{ $size->name ?? '-' }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td> {{ number_format($item->offer_price, 2) }} </td> 
                                        <td> {{ $item->quantity }} </td> 
                                        <td> {{ number_format($item->offer_price * $item->quantity, 2) }} </td> 
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        </div>
    
                        

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
        
          
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                      <div class="table-responsive" style="overflow-x:scroll;">
                            <table class="table table-bordered dt-responsive nowrap table-responsive-sm" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <tbody>
                                <tr>
                                    <td>Order Status</td>
                                    <td>
                                         <div class="input-group select-group">
                                              <select name="order_status" id="order_status" class="form-select">
                                            <option value="0" {{ $orders->order_status == 0 ? 'selected' : '' }}>Pending</option>
                                            <option value="1" {{ $orders->order_status == 1 ? 'selected' : '' }}>Approved</option>
                                            <option value="2" {{ $orders->order_status == 2 ? 'selected' : '' }}>Cancelled</option>
                                           @if($orders->order_status == 3)
                                                <option value="3" selected>Return Requested</option>
                                            @endif
                                            <option value="4" {{ $orders->order_status == 4 ? 'selected' : '' }}>Returned</option>
                                            
                                        </select>
                                   <button onclick="updateOrderStatus({{ $orders->id }}, this)" 
                                            class="submit-btn1">
                                        Update Status
                                    </button>
                                    </div>
                                       
                                    </td>
                                </tr>
                                <tr>
                                    <td>Shipping Status</td>
                                    <td>
                                      <div class="input-group select-group">
                                            <select name="shipping_status" id="shipping_status" class="form-select">
                                            <option value="0"{{ $orders->shipping_status == 0 ? 'selected' : '' }}>Pending</option>
                                            <option value="1"{{ $orders->shipping_status == 1 ? 'selected' : '' }}>Order Received</option>
                                            <option value="2"{{ $orders->shipping_status == 2 ? 'selected' : '' }}>Shipped</option>
                                            <option value="3"{{ $orders->shipping_status == 3 ? 'selected' : '' }}>Out Of Delivery</option>
                                            <option value="4"{{ $orders->shipping_status == 4 ? 'selected' : '' }}>Delivered</option>
                                        </select>
                                           <button onclick="updateShippingStatus({{ $orders->id }}, this)" 
                                                    class="submit-btn1">
                                                Update Status
                                            </button>
                                      </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                      </div>
                      
                    </div>
                </div>
            </div>
           <div class="col-lg-6">
    <div class="card">
        <div class="card-body">
            @php
                $stateName = strtolower(trim($shipping_address->state ?? ''));
                $isTamilNadu = ($stateName == 'tamil nadu');
                $coupon = (float)str_replace(',', '', $orders->coupon_discount ?? 0);
                
                $shipping = (float)($orders->shipping_charge ?? 0);
                $grandTotal = ($calculatedSubtotal + $shipping) - $coupon;
            @endphp
            
            <table class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <tbody>
                    <tr>
                        <td>Subtotal (Inclusive of GST)</td>
                        <td style="text-align: right;"> ₹{{ number_format($calculatedSubtotal, 2) }} </td> 
                    </tr>

                    @if($finalGst > 0)

                        @if($isTamilNadu)
                            <tr>
                                <td>CGST </td>
                                <td style="text-align: right;">
                                    ₹{{ number_format($finalGst / 2, 2) }}
                                </td> 
                            </tr>
                            <tr>
                                <td>SGST </td>
                                <td style="text-align: right;">
                                    ₹{{ number_format($finalGst / 2, 2) }}
                                </td> 
                            </tr>
                        @else
                            <tr>
                                <td>IGST</td>
                                <td style="text-align: right;">
                                    ₹{{ number_format($finalGst, 2) }}
                                </td> 
                            </tr>
                        @endif

                    @endif

                    @if($coupon > 0)
                    <tr>
                        <td>Coupon Discount (-)</td>
                        <td style="text-align: right; color: red;">- ₹{{ number_format($coupon, 2) }} </td> 
                    </tr>
                    @endif
                    @if($shipping > 0)
                    <tr>
                        <td>Shipping Charge</td>
                        <td style="text-align: right;">₹{{ number_format($shipping, 2) }}</td>
                    </tr>
                    @endif
                    <tr style="background-color: #f8f9fa;">
                        <th style="font-size: 16px;">Grand Total</th>
                        <th style="text-align: right; font-size: 16px;">₹{{ number_format($grandTotal, 2) }} </th> 
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
        </div>
                     
                        
</div> <!-- container-fluid -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
function updateOrderStatus(orderId, btn) {

    var newStatus = $('#order_status').val();

    $(btn).prop('disabled', true);
    var originalText = $(btn).html();
    $(btn).html('<i class="fa fa-spinner fa-spin"></i> updating...');

    $.ajax({
        url: '{{ route("update.order.status") }}',
        method: 'POST',
        data: {
            order_id: orderId,
            new_status: newStatus,
            "_token": "{{ csrf_token() }}"
        },
        success: function(response) {

            alert(response.message);
            $(btn).prop('disabled', false);
            $(btn).html(originalText);
        },
        error: function(xhr) {

            console.error(xhr.responseText);

            $(btn).prop('disabled', false);
            $(btn).html(originalText);
        }
    });
}

function updateShippingStatus(orderId, btn) {

    var newStatus = $('#shipping_status').val();

    // 🔹 Set Loading
    $(btn).prop('disabled', true);
    var originalText = $(btn).html();
     $(btn).html('<i class="fa fa-spinner fa-spin"></i> updating...');

    $.ajax({
        url: '{{ route("update.shipping.status") }}',
        method: 'POST',
        data: {
            order_id: orderId,
            new_status: newStatus,
            "_token": "{{ csrf_token() }}"
        },
        success: function(response) {

            alert(response.message);

            // 🔹 Restore Button
            $(btn).prop('disabled', false);
            $(btn).html(originalText);
        },
        error: function(xhr) {

            console.error(xhr.responseText);

            // 🔹 Restore Button Even On Error
            $(btn).prop('disabled', false);
            $(btn).html(originalText);
        }
    });
}


</script>


@endsection