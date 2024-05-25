<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        h1 {
            color: #343a40;
            text-align: center;
        }
        hr {
            border: 0;
            height: 1px;
            background: #dee2e6;
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
        }
        thead {
            background-color: #343a40;
            color: #fff;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #dee2e6;
        }
        th {
            font-weight: bold;
        }
        tbody tr:nth-child(even) {
            background-color: #f1f1f1;
        }
        tbody tr:hover {
            background-color: #e9ecef;
        }
        img {
            display: block;
            max-width: 100px;
            max-height: 100px;
        }
    </style>
</head>
<body>
    <h1>Product List</h1>
    <hr>
    <table>
        <thead>
            <tr>
                <th>QR</th>
                <th>Name</th>
                <th>Description</th>
                <th>Type</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($members as $mem)
                <tr>
                    <td><img src="data:image/png;base64,{{ base64_encode($mem->qrCode) }}" alt="QR Code"></td>
                    <td>{{ $mem->name }}</td>
                    <td>{{ $mem->description }}</td>
                    <td>{{ $mem->type->name }}</td>
                    <td>{{ $mem->price }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
