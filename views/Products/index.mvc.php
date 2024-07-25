<!DOCTYPE html>
<html>
    <head>
        <title>{{ title }}</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    </head>
    <body>
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
    </body>
</html>