<x-welcome_layout>
    <x-slot name="title">SWC News</x-slot>
        <div>
            <h1>News Feed</h1>
            <!--
            <nav>
                <a href="#">News Feed</a>
                <a href="#">Profile</a>
            </nav>
            <br>
            -->
            <div class="search&sort">
                <input type="text" placeholder="Search for News">
                <button>Search</button>
                <select name="sort_news" id="sort_news">
                    <option value="recent" selected="selected">Most Recent</option> <!-- Default value -->
                    <option value="popular">Most Popular</option>
                </select>
                <button>Sort</button>
                <br>
            </div>
            <div class="news">
                <!-- Fetch news on the database -->

                <!-- if user clicks on upvote or downvote redirect to login page -->
            </div>
        </div>
</x-welcome_layout>