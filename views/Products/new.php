<h1>New product</h1>

    <form method="post" action="/products/create">

        <label for="name">Name</label>
        <input type="text" id="name" name="name"> 

        <?php if(isset($errors["name"])): ?>
            <p><?= $errors["name"] ?></p>
        <?php endif; ?>

        <label for="description">Description</label>
<textarea id="description" name="description"></textarea>

        <button type="submit">Save</button>

    </form>

</body>
</html>