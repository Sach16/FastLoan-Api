<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>NAME</th>
    </tr>
    </thead>
    <tbody>
    @foreach($sources as $source)
    <tr>
            <td>{{ $source->id }}</td>
            <td>{{ $source->name }}</td>
    </tr>
    @endforeach
    </tbody>
</table>