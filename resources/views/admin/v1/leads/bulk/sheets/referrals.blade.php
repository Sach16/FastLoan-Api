<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>NAME</th>
    </tr>
    </thead>
    <tbody>
    @foreach($referrals as $referral)
        <tr>
            <td>{{ $referral->id }}</td>
            <td>{{ $referral->present()->name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>