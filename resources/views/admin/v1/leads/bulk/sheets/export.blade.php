<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<table>
    <thead>
    <tr>
        <th>NAME</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($leads as $lead)
    <tr>
            <td>{{ $lead->user->present()->name }}</td>
            <td>{{ $lead->user->phone }}</td>
            <td>{{ $lead->user->email }}</td>
            <td>{{ $lead->loan->status->label }} </td>
    </tr>
    @endforeach
    </tbody>
</table>