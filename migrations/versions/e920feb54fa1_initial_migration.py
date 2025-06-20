"""initial migration

Revision ID: e920feb54fa1
Revises: 
Create Date: 2025-06-20 04:27:45.292733

"""
from typing import Sequence, Union

from alembic import op
import sqlalchemy as sa


# revision identifiers, used by Alembic.
revision: str = 'e920feb54fa1'
down_revision: Union[str, Sequence[str], None] = None
branch_labels: Union[str, Sequence[str], None] = None
depends_on: Union[str, Sequence[str], None] = None


def upgrade() -> None:
    """Create initial tables."""
    op.create_table(
        "certificates",
        sa.Column("id", sa.Integer(), primary_key=True),
        sa.Column("name", sa.String(length=255), nullable=False),
        sa.Column("regno", sa.String(length=255)),
        sa.Column("dept", sa.String(length=255)),
        sa.Column("year", sa.Integer()),
        sa.Column("section", sa.String(length=10)),
        sa.Column("email", sa.String(length=255), nullable=False),
        sa.Column("position", sa.String(length=255)),
        sa.Column("cert_link", sa.String(length=255), nullable=False),
        sa.Column("event_name", sa.String(length=255), nullable=False),
        sa.Column("college", sa.String(length=255)),
    )

    op.create_table(
        "events",
        sa.Column("id", sa.Integer(), primary_key=True),
        sa.Column("event_name", sa.String(length=255), nullable=False),
        sa.Column("date", sa.Date(), nullable=False),
        sa.Column("isInter", sa.Boolean(), nullable=False),
    )

    op.create_table(
        "logging",
        sa.Column("id", sa.Integer(), primary_key=True),
        sa.Column(
            "timestamp",
            sa.DateTime(),
            server_default=sa.text("CURRENT_TIMESTAMP"),
            onupdate=sa.text("CURRENT_TIMESTAMP"),
            nullable=False,
        ),
        sa.Column("userid", sa.String(length=50), nullable=False),
        sa.Column("log", sa.String(length=255), nullable=False),
    )

    op.create_table(
        "notification",
        sa.Column("id", sa.Integer(), primary_key=True),
        sa.Column(
            "timestamp",
            sa.DateTime(),
            server_default=sa.text("CURRENT_TIMESTAMP"),
            nullable=False,
        ),
        sa.Column("user", sa.String(length=50), nullable=False),
        sa.Column("message", sa.String(length=255), nullable=False),
        sa.Column("type", sa.String(length=50), nullable=False),
        sa.Column("clickURL", sa.String(length=255), nullable=False, server_default="#"),
    )

    op.create_table(
        "login",
        sa.Column("id", sa.Integer(), primary_key=True),
        sa.Column("LoginName", sa.String(length=40), nullable=False),
        sa.Column("PasswordHash", sa.String(length=255), nullable=False),
        sa.Column("Email", sa.String(length=255), nullable=False),
        sa.Column("FullName", sa.String(length=80)),
        sa.Column("IsAdmin", sa.Boolean(), server_default="0", nullable=False),
        sa.Column("FirstName", sa.String(length=40)),
        sa.Column("LastName", sa.String(length=40)),
        sa.Column("Address", sa.String(length=255)),
        sa.Column("Phno", sa.String(length=40)),
        sa.Column("Signature", sa.String(length=255)),
        sa.Column("imgsrc", sa.Text()),
        sa.UniqueConstraint("LoginName"),
        sa.UniqueConstraint("Email"),
        sa.UniqueConstraint("FullName"),
    )

    op.create_table(
        "forgot_password",
        sa.Column("gen_key", sa.String(length=255), primary_key=True),
        sa.Column("id", sa.Integer(), nullable=False),
        sa.Column(
            "times",
            sa.DateTime(),
            server_default=sa.text("CURRENT_TIMESTAMP"),
            nullable=False,
        ),
    )

    # insert default admin user
    op.execute(
        sa.text(
            "INSERT INTO login (id, LoginName, PasswordHash, Email, FullName, IsAdmin) "
            "VALUES (3, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', "
            "'test@test.com', 'admin', 1)"
        )
    )


def downgrade() -> None:
    """Drop initial tables."""
    op.drop_table("forgot_password")
    op.drop_table("login")
    op.drop_table("notification")
    op.drop_table("logging")
    op.drop_table("events")
    op.drop_table("certificates")
