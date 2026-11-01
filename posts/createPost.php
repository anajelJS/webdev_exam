<h2>Create a Post 🌱</h2>

<form action="posts/uploadPost.php" method="POST" enctype="multipart/form-data"> <!--enctype="multipart/form-data" tells the browser that this form can send files, not just text.-->

    <label for="content">What's on your mind?</label>
    <br>

    <textarea
        name="content"
        id="content"
        maxlength="2000"
        placeholder="Share something with the community..."
    ></textarea>

    <br>

    <label for="image">Add an image (optional)</label>
    <br>
    <input
        type="file"
        name="image"
        id="image"
        accept="image/jpeg,image/png,image/webp"
    >
    <br>

<br>
    <button type="submit" name="publishPost">
        Publish Post
    </button>

</form>

<a href="home.php">Cancel</a>