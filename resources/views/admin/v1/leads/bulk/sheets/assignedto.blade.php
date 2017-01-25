<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>NAME</th>
    </tr>
    </thead>
    <tbody>
    @foreach($assigned_to as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->present()->name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>