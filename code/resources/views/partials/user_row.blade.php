<tr>
    <td><a href="{{ route('profile', ['id' => $user->id]) }}">{{ $user->name }}</a></td>
    <td>{{ $user->username }}</td>
    <td>{{ $user->email }}</td>
    <td>{{ $user->gender }}</td>
    <td>{{ $user->birthday }}</td>
    <td>{{ $user->reputation }}</td>
    <td><a href="{{ $user->url }}">{{ $user->url }}</a></td>
</tr>