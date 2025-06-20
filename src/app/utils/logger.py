from __future__ import annotations
import os
import sys
from loguru import logger as _logger

# Remove default handler and configure
_logger.remove()
_logger.add(sys.stdout, level=os.getenv("LOG_LEVEL", "INFO"))

logger = _logger

__all__ = ["logger"]
