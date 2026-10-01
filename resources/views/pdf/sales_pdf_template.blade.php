<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
</head>
<body>
    <h1>Sales Report</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Reference No</th>
                <th>User ID</th>
                <th>Grand Total</th>
                <th>Paid Amount</th>
                <th>Due Amount</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
            <tr>
                <td>{{ $sale->reference_no }}</td>
                <td>{{ $sale->user->name }}</td>
                <td>{{ $sale->grand_total }}</td>
                <td>{{ $sale->paid_amount }}</td>
                <td>{{ $sale->grand_total - $sale->paid_amount }}</td>
                <td>{{ $sale->created_at }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="2">Total for Today:</td>
                <td>{{ $totalToday }}</td>
                <td>{{$totalPaid}}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2">Unpaid Amount For Today:</td>
                <td colspan="2">{{ $totalToday - $totalPaid }}</td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</body>
</html>