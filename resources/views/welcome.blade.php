<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Crm</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
             table {
                table-layout: fixed;
                width: 100%;   
            }

            th,td {
                border-style: solid;
                border-width: 1px;
                border-color: #BCBCBC;             
            }   
        </style>
    </head>
    <body>
        
 

            <div class="content" style="margin-top: 5px;">
                <div class="title m-b-md">
                    CRM
                </div>
                <div class=container4>
                  <p><a class="btn btn-primary" href="{{ URL::route('home'); }}">Back</a>
                </div>

               <div class="links" style="margin-top: 5px;">
                <center>Importowano teraz:<center>
                <table>
                                
                @foreach ($workorder as $row)
                <tr>
                    @foreach ($row as $cell)
                    <th>{{$cell}}</th>                                
                    @endforeach
                </tr>
                @endforeach
                </table>

                <center>Logi wszystkich importów:<center>
                <table>
                @foreach ($imports as $row)
                <tr>
                    @foreach ($row as $cell)
                    <th>{{$cell}}</th>                                
                    @endforeach
                </tr>
                @endforeach
                </table>

                <center>Wszystkie dane zaimportowane w bazie:<center>
                <table>
                @foreach ($workorders as $row)
                <tr>
                    @foreach ($row as $cell)
                    <th width="50px">{{$cell}}</th>                                
                    @endforeach
                </tr>
                @endforeach
                </table>
              </div>
            </div>
        
    </body>
</html>
