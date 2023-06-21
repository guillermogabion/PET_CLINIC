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
    <h3>Veterinary Health Certificate (Domestic Movement)</h3>
    
    @foreach($pets as $pet)
        <h2>Owner Information</h2>
        <p>Client Name: <b> {{ $pet['owner']['client_name'] }} </b></p>
        <p>Contact:<b> {{ $pet['owner']['contact'] }}</b></p>
        
        <h2>Pet Information</h2>
        <p>Pet Name: <b> {{ $pet['pet_name'] }} </b></p>
        <p>Species: <b> {{ $pet['specie'] }} </b></p>
        <p>Breed: <b> {{ $pet['breed'] }} </b></p>
        
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
                        <!-- <td>{{ $record['datevisitss'] }}</td>
                        <td>{{ $record['duration'] }}</td>
                        <td>{{ $record['remarks'] }}</td> -->
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <!-- <p style="text-align: left"> Approved By :</p> -->
</body>
</html>
