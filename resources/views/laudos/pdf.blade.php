<html>

<head>
    <style>
        /* body, {
            margin: 100px 50px;
            background: pink;
        } */

        /* @page {
            margin: 0px 50px;
            margin-top: 50px;
            margin-bottom: 100px;
        } */

        @page {
            margin: 0px 50px;
            margin-top: 80px;
            margin-bottom: 125px;
        }

        body {
            margin: 0px 50px;
            margin-top: 80px;
            margin-bottom: 125px;
        }

        header {
            position: fixed;
            top: -60px;
            left: 0px;
            right: 0px;
            height: 50px;
        }

        footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 50px;
        }

        .footer-counter {
            text-align: right;
        }

        /* footer { position: absolute; bottom: -60px; left: 0px; right: 0px;  height: 50px;vertical-align: middle;text-align: center} */
        .page-number {
            vertical-align: middle;
        }

        .page-number:after {
            content: counter(page);
            vertical-align: middle;
        }

        h1 {
            page-break-before: always;
        }

        .start-page {
            page-break-before: always;
        }
    </style>
</head>

<body>
    <script type="text/php">
        $GLOBALS['chapters'] = array();
        $GLOBALS['figures'] = array();
        $GLOBALS['max_object'] = 0;
    </script>

    {!!$dataLaudoModelo!!}

    <script type="text/php">
        $pdf->close_object();
        </script>

    <main>
        <section id="sumary">
            <div style="position: relative; top: 120px;">
                @if($figure)
                <h2 class="start-page">Lista de Figuras</h2>

                <table style="width:100%;margin: 0 70px;">
                    @foreach ($images as $imgkey => $img)
                    <tr>
                        <td style="width:80%;border-bottom:dotted">{{$img}}</td>
                        <td>%%FG{{$imgkey+1}}%%</td>
                    </tr>
                    @endforeach
                </table>
                @endif

                <h2>Sumário</h2>

                <table style="width:100%;margin: 0 70px">
                    <tbody>
                        @php
                        $l1 = 1;
                        $l2 = 1;
                        $l3 = 1;
                        @endphp
                        @foreach ($matches as $key=>$headings)
                        <tr>
                            <td style="width:80%;border-bottom:dotted">{{$key}} </td>
                            <td style="">%%CH{{$l1}}%%</td>
                        </tr>
                        @php
                        $l2 = 1;

                        if($loop->first):
                        $ch = '%%CH' . $l1 . '%%';
                        endif;

                        if($loop->last):
                        $chLast = '%%CH' . $l1 . '%%';
                        endif;

                        @endphp
                        @if (is_array($headings))

                        @foreach ($headings as $key2 => $sub)
                        <tr>
                            <td style="width:80%;border-bottom:dotted;">{{$key2}}</td>
                            <td style="">%%CH{{$l1}}.{{$l2}}%%</td>
                        </tr>

                        @if (is_array($sub) && !empty($sub))
                        @php
                        $l3 = 1;
                        @endphp
                        @foreach ($sub as $subsub)
                        <tr>
                            <td style="width:80%;border-bottom:dotted">{{$subsub}}</td>
                            <td style="">%%CH{{$l1}}.{{$l2}}.{{$l3}}%%</td>
                        </tr>
                        @php
                        $l3++;
                        @endphp
                        @endforeach

                        @endif

                        @php
                        $l2++;
                        @endphp
                        @endforeach
                        @endif
                        @php
                        $l1++;
                        @endphp
                        @endforeach
                    </tbody>
                </table>

                <script type="text/php">

                    $GLOBALS['max_object'] = count($pdf->get_cpdf()->objects);
                            {{-- echo '<pre>';
                            print_r($pdf->get_cpdf()->objects);
                            exit; --}}

                    </script>
            </div>
        </section>

        <section id="content">
            <div style="position: relative; top: 120px;">
                {!! $content !!}
            </div>
        </section>
    </main>

    <header>
        {!!$dataLaudoModeloHeader!!}
    </header>

    <footer>
        {!!$dataLaudoModeloFooter!!}

        <div class="footer-counter">
            <span class="page-number"></span>
        </div>
    </footer>

    <script type="text/php">
        for ($i = 0; $i <= $GLOBALS['max_object']; $i++) {
                                if (!array_key_exists($i, $pdf->get_cpdf()->objects)) {
                                    continue;
                                }

                                $object = $pdf->get_cpdf()->objects[$i];


                                if (!array_key_exists('c', $object)) {
                                    continue;
                                }

                                foreach ($GLOBALS['chapters'] as $chapter => $page) {
                                    $object['c'] = str_replace( '%%CH'.$chapter.'%%' , $page , $object['c'] );
                                }

                                foreach ($GLOBALS['figures'] as $figure => $page) {
                                    $object['c'] = str_replace( '%%FG'.$figure.'%%' , $page , $object['c'] );
                                }

                                $pdf->get_cpdf()->objects[$i] = $object;
                            }
                    </script>
</body>

</html>
