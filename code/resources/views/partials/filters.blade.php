
<!-- filters.blade.php -->
<form action="{{ route('filter.posts') }}" method="GET">
    <label for="start_date">Start Date:</label>
    <input type="date" id="start_date" name="start_date">

    <label for="end_date">End Date:</label>
    <input type="date" id="end_date" name="end_date">

    <label for="upvotes">Minimum Upvotes:</label>
    <input type="number" id="upvotes" name="upvotes">

    <label for="downvotes">Minimum Downvotes:</label>
    <input type="number" id="downvotes" name="downvotes">

    <label for="username">User Name:</label>
    <input type="text" id="username" name="username">

    <button type="submit">Apply Filters</button>
</form>