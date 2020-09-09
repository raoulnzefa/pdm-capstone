<html>
    <head>
        <title>{{ $title }}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" type="text/css" href="{{ public_path('css/pdf.css') }}">
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
                   <span class="report-name">{{ $title }}</span>
                </td>
            </tr>
        </table>
        <table border="0" width="100%">    
            <tr>
                <td width="13%">Printed by:</td>
                <td>{{ $printed_by }}</td>
            </tr>
            <tr>
                <td>Date & Time Printed:</td>
                <td>{{ $date_printed }}</td>
            </tr>
        </table>
        <br>        
        <table class="content">
            <thead>
                <tr>
                   <th>Product</th>
                   <th>Category</th>
                   <th>Quantity</th>
                   <th>Total</th>
                </tr>
            </thead>
            <tbody>
               
            </tbody>
        </table>
        <script type="text/php">
        if (isset($pdf)) {
            $x = 250;
            $y = 810;
            $text = "Page: {PAGE_NUM} of {PAGE_COUNT}";
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