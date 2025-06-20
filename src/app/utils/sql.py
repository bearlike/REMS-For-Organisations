"""Database helper functions for cross-dialect queries."""

from __future__ import annotations

from typing import List, Optional
import fnmatch

from sqlalchemy import inspect
from sqlalchemy.engine import Engine


def list_tables(engine: Engine, pattern: str | None = None, schema: Optional[str] = None) -> List[str]:
    """Return table names matching the optional pattern."""
    inspector = inspect(engine)
    tables = inspector.get_table_names(schema=schema)
    if pattern:
        tables = [t for t in tables if fnmatch.fnmatch(t, pattern)]
    return tables


def list_columns(engine: Engine, table: str, schema: Optional[str] = None) -> List[str]:
    """Return column names for the given table."""
    inspector = inspect(engine)
    return [col["name"] for col in inspector.get_columns(table, schema)]
