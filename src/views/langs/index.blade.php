@extends('layouts.lte')

@section('title', 'Языковые строки приложения')

@section('content_header')
    <div class="row">
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li><a href="{{ route('a.start') }}">Главная</a></li>               
                <li class="active">Языковые строки</li>
            </ul>
        </div>
    </div>
@stop

@section('content')
<p class="info">Внимание! Данный интерфейс не рассчитан на использование большого кол-ва текста!</p>
<p class="info">Внимание! Точки в названиях переменных (поле Ключ строки) использовать нельзя!</p>
<p class="info">Внимание! Уникальность названий переменных (поле Ключ строки) не проверяется!</p>
<p></p>
<table id="example" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th></th>
            <th>Ключ строки</th>
            @foreach ($langs as $lKey => $lang)
            <th>{{ $lang }}</th>
            @endforeach
        </tr>
    </thead>
</table>
@stop

@section('custom_js')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.2/css/select.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/qwertcms-lang/css/editor.dataTables.min.css') }}">
    <!-- <link rel="stylesheet" type="text/css" href="../resources/syntax/shCore.css">
    <link rel="stylesheet" type="text/css" href="../resources/demo.css">
    <style type="text/css" class="init">
    
    </style> -->
    <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.12.4.js">
    </script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js">
    </script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js">
    </script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js">
    </script>
    <script type="text/javascript" language="javascript" src="{{ asset('vendor/qwertcms-lang/js/dataTables.editor.min.js') }}">
    </script>
    <!-- <script type="text/javascript" language="javascript" src="../resources/syntax/shCore.js">
    </script>
    <script type="text/javascript" language="javascript" src="../resources/demo.js">
    </script>
    <script type="text/javascript" language="javascript" src="../resources/editor-demo.js">
    </script> -->
    <script type="text/javascript" language="javascript" class="init">
    var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {
    editor = new $.fn.dataTable.Editor( {
        ajax: "{{ route('a.langs.ajax') }}?_token={{ csrf_token() }}",
        table: "#example",
        fields: [ {
                label: "Ключ названия:",
                name: "key"
            }
            @foreach ($langs as $lKey => $lang)
            , {
                label: "{{ $lang }}:",
                name: "{{ $lKey }}"
            }
            @endforeach
        ]
    } );

    $('#example').on( 'click', 'tbody td:not(:first-child)', function (e) {
        editor.inline( this, {
            onBlur: 'submit',
            submit: 'changed' //submit: 'allIfChanged'
        } );
    } );

    $('#example').DataTable( {
        dom: "Bfrtip",
        ajax: "{{ route('a.langs.ajax') }}?_token={{ csrf_token() }}",
        columns: [
            {
                data: null,
                defaultContent: '',
                className: 'select-checkbox',
                orderable: false
            }
            , { data: "key" }
            @foreach ($langs as $lKey => $lang)
            , { data: "{{ $lKey }}" }
            @endforeach
        ],
        order: [ 1, 'asc' ],
        select: {
            style:    'os',
            selector: 'td:first-child'
        },
        buttons: [
            { extend: "create", editor: editor },
            { extend: "edit",   editor: editor },
            { extend: "remove", editor: editor }
        ]
    } );
} );
</script>

@stop