{% extends "base.mvc.php" %}

{% block title %}Delete Product{% endblock %}

{% block body %}

<h1>Delete Product</h1>


<form method="post" action="/products/{{ product["id"] }}/destroy">
    <p>Delete this produt?</p>
    <button type="submit">Yes</button>
</form>

<p>
<a href="/products/{{ product["id"] }}/show">Cancel</a>
</p>
</body>
</html>

{% endblock %}