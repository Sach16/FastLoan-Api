<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>NAME</th>
    </tr>
    </thead>
    <tbody>
    @foreach($statuses as $status)
        <tr>
            <td>{{ $status->id }}</td>
            <td>{{ $status->label }}</td>
        </tr>
    @endforeach
    </tbody>
</table>