<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>NAME</th>
    </tr>
    </thead>
    <tbody>
    @foreach($types as $type)
    <tr>
            <td>{{ $type->id }}</td>
            <td>{{ $type->name }}</td>
    </tr>
    @endforeach
    </tbody>
</table>