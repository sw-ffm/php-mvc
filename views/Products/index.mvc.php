{% extends "base.mvc.php" %}

{% block title %}Products{% endblock %}

{% block body %}

    <h1>Products</h1>

    <a href="/products/new">New Product</a><br>

    <p>Total: {{ total }}</p><br>

    {% foreach($products as $product): %}

        <h2>
            <a href="/products/{{ product["id"] }}/show">
            {{ product["name"] }}
            </a>
        </h2>
        <hr>

    {% endforeach; %}

{% endblock %}