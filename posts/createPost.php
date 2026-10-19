<h2>Create a Post 🌱</h2>

<form action="posts/uploadPost.php" method="POST">

    <label for="content">What's on your mind?</label>
    <br>

    <textarea
        name="content"
        id="content"
        maxlength="2000"
        placeholder="Share something with the community..."
        required
    ></textarea>

    <br>

    <button type="submit" name="publishPost">
        Publish Post
    </button>

</form>

<a href="home.php">Cancel</a>