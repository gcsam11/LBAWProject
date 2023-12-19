<form class="content" method="POST" action="/send">
    @csrf
    <label for="email">Your email</label>
    <input id="email" type="email" name="email" placeholder="Email" required>
    <button type="submit">Recover</button>
</form>
