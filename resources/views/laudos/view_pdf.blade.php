<body>
    <style>
        .page-break{ page-break-after: always } 
        html{
            border:1px solid black;
        }
        .pagenum:before {
            content: counter(page);
        }

    </style>

    @php 
        $data = str_replace('../../../storage', '../../storage',$data);
    @endphp 
        {!! $data !!}
</body>
