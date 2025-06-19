#!/usr/bin/env python3
from dataclasses import dataclass, field
import math


@dataclass
class Pagination:
    """Utility class for handling pagination parameters."""

    page: int = 1
    per_page: int = 10
    _total_pages: int = field(default=1, init=False)

    @property
    def offset(self) -> int:
        """Return the starting offset for a query."""
        return self.per_page * (self.page - 1)

    def get_total_pages(self, total_items: int) -> int:
        """Calculate the total number of pages given the total items."""
        return math.ceil(total_items / self.per_page) if total_items > 0 else 1

    @property
    def total_pages(self) -> int:
        """Return total pages."""
        return self._total_pages

    def set_total_pages(self, total_items: int) -> None:
        """Set the total pages based on total items."""
        self._total_pages = self.get_total_pages(total_items)
