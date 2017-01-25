<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>NAME</th>
    </tr>
    </thead>
    <tbody>
    @foreach($owners as $owner)
    <tr>
            <td>{{ $owner->id }}</td>
            <td>{{ $owner->present()->name }}</td>
    </tr>
    @endforeach
    </tbody>
</table>