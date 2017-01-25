<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>NAME</th>
    </tr>
    </thead>
    <tbody>
    @foreach($cities as $city)
    <tr>
            <td>{{ $city->id }}</td>
            <td>{{ $city->name }}</td>
    </tr>
    @endforeach
    </tbody>
</table>