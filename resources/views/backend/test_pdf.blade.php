<html>
    <head>
        <style>
            .img-responsive {
                height: auto;
                width: auto;
                max-height: 75px;
                max-width: 250px;
            }

            #report-name {
                display: block;
                font-size: 24px;
                font-weight: bold;
                padding-top: 15px;
                padding-bottom: 15px;
            }

            .bg-dark {
                background-color: #000;
                color: #fff;
            }

            #content {
                width: 100%;
                border-collapse: collapse;
            }

            #content th, #content td {
                border: 1px solid;
                padding: 10px;
                text-align: left;
            }

            #t-footer td {
                padding: 10px;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
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
                   <span id="report-name">Inventory Report</span>
                </td>
            </tr>
             <tr>
                <td>Printed by: Ivan Paghubasan</td>
                <td align="right">Date: September 3, 2020</td>
            </tr>
            <tr>
                <td colspan="2">
                    <table id="content">
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

                            @endif
                        </tbody>
                    </table>
                    <table width="100%" id="t-footer">
                        <tr>
                            <td align="right">Total Count :</td>
                            <td width="15%">{{$total}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
       </table>
        <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_script('
                 if ($PAGE_COUNT > 1) {
                     $font = $fontMetrics->getFont("Lato", "regular");
                     $pdf->page_text(522, 770, "Page {PAGE_NUM} / {PAGE_COUNT}", $font, 8, array(.5,.5,.5));
                }
            ');
       }
        </script>
    </body>
</html>