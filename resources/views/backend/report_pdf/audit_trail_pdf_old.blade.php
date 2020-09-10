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
            <tr>
                <td colspan="2" align="center">
                   {{ $date_range }}
                </td>
            </tr>
        </table>
        <br>
        <table border="0" width="100%">    
            <tr>
                <td width="28%">Printed by:</td>
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
                  <th>ID</th>
                  <th>User</th>
                  <th>Action</th>
                  <th>Date</th>
                </tr>
            </thead>
            <tbody>
              @if (count($audit_trail) > 0)
                @foreach ($audit_trail as $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>{{$item->admin->first_name.' '.$item->admin->last_name}}</td>
                        <td>{{$item->action}}</td>
                        <td>{{$item->log_created}}</td>
                    </tr>
                @endforeach
              @else
                <tr>
                    <td colspan="4" align="center">No Audit Trail.</td>
                </tr>
              @endif
            </tbody>
        </table>
        <footer>
            Copyright &copy; <?php echo date("Y");?> 
        </footer>
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