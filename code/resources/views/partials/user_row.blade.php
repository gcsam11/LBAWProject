@section('scripts')
<script type="text/javascript" src={{ url('js/admin_buttons.js') }} defer></script>
@endsection
<meta name="csrf-token" content="{{ csrf_token() }}">
<tr>
    <td><a href="{{ route('profile_page', ['id' => $user->id]) }}">{{ $user->name }}</a></td>
    <td>{{ $user->username }}</td>
    <td>{{ $user->email }}</td>
    <td>{{ $user->gender }}</td>
    <td>{{ $user->birthday }}</td>
    <td>{{ $user->reputation }}</td>
    <td><a href="{{ $user->url }}">{{ $user->url }}</a></td>
    @if($user->blocked===true)
        <td><button class="admin_not_block" data-id="{{$user->id}}">Unblock</button></td>
    @else
        <td><button class="admin_block" data-id="{{$user->id}}">Block</button></td>
    @endif
    <td>
        <form action="{{ route('profile_delete', ['id' => $user->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                {{ csrf_field() }}
                {{ method_field('DELETE') }} 
                <button class="admin_delete" type="submit">Delete</button>
        </form>
    </td>
</tr>