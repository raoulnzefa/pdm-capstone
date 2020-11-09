<html>
<head>
  <title>{{$title}}</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <link rel="stylesheet" type="text/css" href="{{ public_path('css/pdf.css') }}">
</head>
<body>
  <footer>
    Printed by: {{ $printed_by }} &nbsp;| &nbsp;Date & Time Printed: {{ $date_printed }}
  </footer>
  <main>
     <table border="0" width="100%">
         <tr>
            <td align="center" colspan="2">
               <img src="{{ (!is_null($company)) ? $company->logo_url : public_path('images/logo.jpg') }}" class="img-responsive">
            </td>
        </tr>
        <tr>
            <td align="center" colspan="2">{{$company->address}}</td> 
        </tr>
        <tr>
            <td colspan="2" align="center">
               <span class="report-name">{{ $title }}</span>
            </td>
        </tr>
    </table>
    <br>
    <table class="defective-products-content">
        <thead>
            <tr>
               <th width="8%">ID</th>
               <th>Inventory ID</th>
               <th width="38%">Product</th>
               <th>Replaced Qty.</th>
               <th>Created</th>
            </tr>
        </thead>
        <tbody>
          @if (count($defective_products) > 0)
            @foreach ($defective_products as $item)
              <tr>
                <td>{{$item->id}}</td>
                <td>{{$item->inventory_number}}</td>
                <td>{{$item->inventory->product->product_name}}</td>
                <td>{{$item->replacementRequest->quantity}}</td>
                <td>{{date('m/d/Y',strtotime($item->created_at))}}</td>
              </tr>
            @endforeach
          @else

          @endif
        </tbody>
    </table>
  </main>
  <script type="text/php">
    if (isset($pdf)) {
        $x = 495;
        $y = 805;
        $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
        $font = null;
        $size = 12;
        $color = array(0,0,0);
        $word_space = 0.0;  //  default
        $char_space = 0.0;  //  default
        $angle = 0.0;   //  default
        $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
    }
    </script>
</body>
</html>