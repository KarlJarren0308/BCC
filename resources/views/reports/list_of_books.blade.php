<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Binangonan Catholic College</title>
    <style>
        body {
            font-family: 'Helvetica';
            font-size: 12px;
        }

        table {
            margin: 5px 0 20px 0;
            width: 100%;
        }

        .header > .logo {
            height: 75px;
        }

        .header > .header-content {
            display: inline-block;
            padding: 0.5rem 1rem;
        }

        .header > .header-content > .title {
            font-size: 2.5em;
        }

        .header > .header-content > .subtitle {
            font-size: 0.75em;
        }

        .body {
            margin-top: -50px;
        }

        .footer {
            border-top: 1px solid #888;
            font-size: 10px;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
        }

        .table {
            border-spacing: none;
        }

        .table th {
            padding: 0.5rem;
        }

        .table td {
            padding: 0.5rem;
        }

        .table > thead > tr {
            background: #dd1e2f;
        }

        .table > tbody > tr:nth-child(odd) {
            background: white;
        }

        .table > tbody > tr:nth-child(even) {
            background: #eee;
        }

        .table > thead > tr > th,
        .table > thead > tr > td {
            color: #f0ad4e;
        }

        .size-1 {
            font-size: 1.5em;
        }

        .size-2 {
            font-size: 1.25em;
        }

        .size-3 {
            font-size: 1em;
        }

        .size-4 {
            font-size: 0.75em;
        }

        .size-5 {
            font-size: 0.5em;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="img/logo.png" class="logo">
        <div class="header-content">
            <div class="title">Binangonan Catholic College</div>
            <div class="subtitle">Libid, Binangonan, Rizal</div>
        </div>
    </div>
    <div class="footer">
        <script type="text/php">
            if(isset($pdf)) {
                $font = Font_Metrics::get_font('helvetica', '');
                $pageText = 'Page {PAGE_NUM} of {PAGE_COUNT}';
                $x = $pdf->get_width() - Font_Metrics::get_text_width($pageText, $font, 7) + 52;
                $y = $pdf->get_height() - 32;
                $pdf->page_text($x, $y, $pageText, $font, 7, array(.467, .467, .467));
                $pdf->page_text(37, $y, 'This is a system generated report.', $font, 7, array(.467, .467, .467));
            }
        </script>
    </div>
    <div class="body">
        <h2 class="text-center">List of Books Report</h2>
        <div class="size-4">Date Range: {{ date('F d, Y', strtotime($from)) }} - {{ date('F d, Y', strtotime($to)) }}</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Call Number</th>
                    <th>Book Title</th>
                    <th>Edition</th>
                    <th>ISBN</th>
                    <th>Language</th>
                    <th>Location</th>
                    <th>Acquisition Method</th>
                </tr>
            </thead>
            <tbody>
                @foreach($books as $book)
                    <tr>
                        <td>{{ $book->Call_Number }}</td>
                        <td>{{ $book->Title }}</td>
                        <td class="text-center">{{ $book->Edition }}</td>
                        <td class="text-center">{{ $book->ISBN }}</td>
                        <td class="text-center">{{ $book->Language }}</td>
                        <td class="text-center">{{ $book->Location }}</td>
                        <td class="text-center">{{ $book->Acquisition_Method }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table>
            <tr>
                <td class="text-right" width="85%">Total number of books acquired within date range:</td>
                <td class="text-center">{{ $books_count }}</td>
            </tr>
        </table>
    </div>
</body>
</html>