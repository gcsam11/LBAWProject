<div class="requests">
    <br><hr><br>
    <h3>Contact Us - Requests Submitted</h3>
        @foreach ($contactUsRequests as $contactUs)
            <article class="request">
                <strong>First Name:</strong> {{ $contactUs->first_name }}<br><br>
                <strong>Last Name:</strong> {{ $contactUs->last_name }}<br><br>
                <strong>Email:</strong> {{ $contactUs->email }}<br><br>
                <strong>Message:</strong> {{ $contactUs->message }}<br><br>
            </article>
        @endforeach
</div>