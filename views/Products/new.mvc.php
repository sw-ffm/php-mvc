{% extends "base.mvc.php" %}

{% block title %}New Product{% endblock %}

{% block body %}

<h1>New product</h1>

<form method="post" action="/products/create">

{% include "Products/form.mvc.php" %}

</form>
    
</body>
</html>

{% endblock %}