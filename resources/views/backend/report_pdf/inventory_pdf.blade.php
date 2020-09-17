<html>
    <head>
        <title>{{ $title }}</title>
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
                        <img src="{{ public_path('images/logo.jpg') }}" class="img-responsive">
                    </td>
                </tr>
                <tr>
                    <td align="center" colspan="2">Bunlo, Mac Arthur Hi-way, 2500 Bocaue, Bulacan</td> 
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <span class="report-name">{{ $title }}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <span>{{ $report_type}}</span>
                    </td>
                </tr>
          </table>
          <br>
          <table class="inventory-content">
            <thead>
                <tr>
                    <th width="15%">ID</th>
                    <th width="44%">Product</th>
                    <th>Category</th>
                    <th width="15%">Quantity</th>
                </tr>
            </thead>
            <tbody>
                @if (count($inventories) > 0)
                    @foreach ($inventories as $item)
                        <tr>
                            <td>{{ $item->number }}</td>
                            @if ($item->productWithVariant)
                            <td>{{ $item->product->product_name.' '.$item->productWithVariant->variant_value }}</td>
                            @else
                            <td>{{ $item->product->product_name }}</td>
                            @endif
                            <td>{{ $item->product->category->name }}</td>
                            <td>{{ $item->inventory_stock }}</td>
                        </tr>
                    @endforeach
                @else
                <tr>
                    <td colspan="4"><span>No {{ $report_type }}</span></td>
                </tr>
                @endif
            </tbody>
        </table>
        @if ($report_type == 'All Stocks')
        <table width="100%" class="t-footer">
            <tr>
                <td align="right">Total Count :</td>
                <td width="15%">{{$total}}</td>
            </tr>
        </table>
        @endif
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