import sqlalchemy
from sqlalchemy import Column, String, Boolean, DateTime
from sqlalchemy.dialects.postgresql import UUID
import uuid

from database import metadata

notes = sqlalchemy.Table(
    "notes",
    metadata,
    Column("uuid", UUID(as_uuid=True), primary_key=True, default=uuid.uuid4),
    Column("title", String, nullable=False),
    Column("content", String, nullable=True),
    Column("color", String, nullable=True),
    Column("is_pinned", Boolean, nullable=False, default=False),
    Column("is_archived", Boolean, nullable=False, default=False),
    Column("source", String, nullable=False),
    Column("created_at", DateTime, nullable=False),
    Column("updated_at", DateTime, nullable=False),
)