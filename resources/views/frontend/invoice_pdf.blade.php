<html>
    <head>
        <title>{{ $title }}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" type="text/css" href="{{ public_path('css/pdf.css') }}">
        <style type="text/css">
          @page {
            margin-top: 40px;
            margin-bottom: 40px;
          }
        </style>
    </head>
    <body>
        <main>
          <table border="0" width="100%">
               <tr>
                  <td align="center" colspan="2">
                     <img src="{{ public_path('images/logo.jpg') }}" class="img-responsive">
                  </td>
              </tr>
              <tr>
                  <td align="center" colspan="2">{{$company->address}}</td> 
              </tr>
              <tr>
                  <td align="center" colspan="2">Contact no. {{$company->contact_number}}</td> 
              </tr>
              <tr>
                  <td align="center" colspan="2">TIN: {{$company->tin_number}}</td> 
              </tr>
              <tr>
                  <td colspan="2" align="center">
                     <span class="report-name">{{ $title }}</span>
                  </td>
              </tr>
          </table>
          <br>
          <table border="0" width="100%">
            <tr>
              <td width="50%">
                <table border="0" width="100%">
                  <tr>
                    <td width="37%">Invoice Number:</td>
                    <td>{{$invoice->number}}</td>
                  </tr>
                  <tr>
                    <td>Order Number:</td>
                    <td>{{$invoice->order_number}}</td>
                  </tr>
                  <tr>
                    <td>Customer:</td>
                    <td>{{$invoice->first_name.' '.$invoice->last_name}}</td>
                  </tr>
                  <tr>
                    <td>Invoice Date:</td>
                    <td>{{$invoice->created}}</td>
                  </tr>
                </table>
              </td>
              <td width="50%">
                 @if ($invoice->order->order_shipping_method == 'Shipping')
                 <table border="0" width="100%">
                  <tbody>
                    <tr>
                      <td width="40%">Shipping Address:</td>
                      <td>{{ $invoice->order->shipping->shipping_street.','}}</td>
                    </tr>
                    <tr>
                      <td width="30%"></td>
                      <td>{{ $invoice->order->shipping->shipping_barangay.', '.$invoice->order->shipping->shipping_municipality.', '.$invoice->order->shipping->shipping_province.', '.$invoice->order->shipping->shipping_zip_code}}</td>
                    </tr>
                    <tr>
                      <td width="30%"></td>
                      <td>{{ $invoice->order->shipping->shipping_firstname.' '.$invoice->order->shipping->shipping_lastname }}</td>
                    </tr>
                    <tr>
                      <td width="30%"></td>
                      <td>{{ $invoice->order->shipping->shipping_mobile_no }}</td>
                    </tr>
                  </tbody>
                </table>
                @endif
              </td>
            </tr>
          </table>
          <br>
          <br>
          <table class="content">
            <thead>
              <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total Price</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($invoiceProduct as $item)
                <tr>
                  <td>{{ $item->product_name }}</td>
                  <td>PHP {{ $item->price }}</td>
                  <td>{{ $item->quantity }}</td>
                  <td>PHP {{ $item->total }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
          <table border="0" width="100%" class="invoice-total">
            <tr>
              <td align="right" width="80%"><b>Subtotal:</b></td>
              <td align="right">PHP {{$invoice->order->order_subtotal}}</td>
            </tr>
            <tr>
              <td align="right" width="80%"><b>Discount:</b></td>
              <td align="right">PHP {{$invoice->order->order_discount}}</td>
            </tr>
            @if ($invoice->order->order_shipping_method == 'Shipping')
            <tr>
              <td align="right"><b>Shipping Fee:</b></td>
              <td align="right">PHP {{ $invoice->order->order_shipping_fee }}</td>
            </tr>
            @endif
            <tr>
              <td align="right"><b>Total:</b></td>
              <td align="right">PHP {{ $invoice->order->order_total }}</td>
            </tr>
          </table>
        </main>
    </body>
</html>