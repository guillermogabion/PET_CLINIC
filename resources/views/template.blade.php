<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        
        th, td {
            text-align: left;
            padding: 8px;
        }
        
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    @foreach($pets as $pet)
        <h2>Owner Information</h2>
        <p>Client Name: {{ $pet['owner']['client_name'] }}</p>
        <p>Contact: {{ $pet['owner']['contact'] }}</p>
        
        <h2>Pet Information</h2>
        <p>Pet Name: {{ $pet['pet_name'] }}</p>
        <p>Species: {{ $pet['specie'] }}</p>
        <p>Breed: {{ $pet['breed'] }}</p>
        
        <h2>Record Information</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Service</th>
                    <th>Brand Name</th>
                    <th>Date Visited</th>
                    <th>Duration</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pet['record'] as $record)
                    <tr>
                        <td>{{ $record['id'] }}</td>
                        <td>{{ $record['service'] }}</td>
                        <td>{{ $record['brand_name'] }}</td>
                        <td>{{ $record['datevisitss'] }}</td>
                        <td>{{ $record['duration'] }}</td>
                        <td>{{ $record['remarks'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>
</html>
