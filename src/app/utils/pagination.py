from dataclasses import dataclass

@dataclass
class Pagination:
    """Utility class for handling pagination parameters."""

    page: int = 1
    per_page: int = 10

    @property
    def offset(self) -> int:
        """Return the starting offset for a query."""
        return self.per_page * (self.page - 1)
