from __future__ import annotations

"""Authentication utilities."""

from functools import wraps
from flask import session, redirect, url_for, g


def login_required(view):
    """Ensure that a user is logged in before accessing the route."""

    @wraps(view)
    def wrapper(*args, **kwargs):
        user = session.get("user")
        if not user:
            return redirect(url_for("public.home"))
        g.user = user
        return view(*args, **kwargs)

    return wrapper

