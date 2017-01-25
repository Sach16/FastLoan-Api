<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>NAME</th>
    </tr>
    </thead>
    <tbody>
    @foreach($agents as $agent)
        <tr>
            <td>{{ $agent->id }}</td>
            <td>{{ $agent->present()->name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>