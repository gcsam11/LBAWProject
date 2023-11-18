<x-welcome_layout>
    <!-- USER HAS TO BE LOGGED IN -->
    <x-slot name="title">SWC News</x-slot>
        <div class="main">
            <h1>News Feed</h1>
            <!--
            <nav>
                <a href="../pages/welcome.blade.php">News Feed</a>
                <a href="../pages/user_news.blade.php">User's News</a>
                <a href="../pages/profile.blade.php">Profile</a>
            </nav>
            <br>
            -->
            <div class="search&sort">
                <input type="text" placeholder="Search for News">
                <button>Search</button>
                <!--
                <select name="sort_news" id="sort_news">
                    <option value="recent" selected="selected">Most Recent</option>
                    <option value="popular">Most Popular</option>
                </select>
                <button>Sort</button>
                -->
                <br>
            </div>
            <div class="news">
                <!-- Fetch news on the database -->

                <!-- if user clicks on upvote or downvote redirect to login page -->
            </div>
            <br>
            <div class="create_post">
                <button id="create_post">+</button>
            </div>
        </div>
</x-welcome_layout>