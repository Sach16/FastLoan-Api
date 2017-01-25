<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>NAME</th>
    </tr>
    </thead>
    <tbody>
    @foreach($builders as $builder)
    <tr>
            <td>{{ $builder->id }}</td>
            <td>{{ $builder->name }}</td>
    </tr>
    @endforeach
    </tbody>
</table>