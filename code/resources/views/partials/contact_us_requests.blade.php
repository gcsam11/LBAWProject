<div class="contact_us_requests">
    <br><hr><br>
    <h3>Contact Us - Requests Submitted</h3>
        @foreach ($contactUsRequests as $contactUs)
            <article class="contact_us_request">
                <p><strong>Name:</strong> {{ $contactUs->first_name }} {{ $contactUs->last_name }}</p><br><br>
                <p><strong>Email:</strong> {{ $contactUs->email }}</p><br><br>
                <p><strong>Message:</strong> {{ $contactUs->message }}</p><br><br>
            </article>
        @endforeach
</div>