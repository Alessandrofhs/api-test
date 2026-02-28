<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Pocket ID</th>
            <th>User ID</th>
            <th>Amount</th>
            <th>Notes</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->pocket_id }}</td>
            <td>{{ $item->user_id }}</td>
            <td>{{ $item->amount }}</td>
            <td>{{ $item->notes }}</td>
            <td>{{ $item->created_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>