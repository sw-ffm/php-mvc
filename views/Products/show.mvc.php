{% extends "base.mvc.php" %}

{% block title %}Product{% endblock %}

{% block body %}

<h1>{{ product["name"] }}</h1>

<p>{{ product["description"] }}</p>

<p>
    <a href="/products/index">Index</a>
    &nbsp;&nbsp;|&nbsp;&nbsp;
    <a href="/products/{{ product["id"] }}/edit">Edit</a>
    &nbsp;&nbsp;|&nbsp;&nbsp;
    <a href="/products/{{ product["id"] }}/delete">Delete</a>
</p>

{% endblock %}