from pydantic import BaseModel
from typing import Optional
from uuid import UUID
from datetime import datetime


class NoteIn(BaseModel):
    title: str
    content: str = None
    color: Optional[str] = None
    is_pinned: Optional[bool] = False
    is_archived: Optional[bool] = False


class NoteOut(NoteIn):
    uuid: UUID
    is_pinned: bool
    is_archived: bool
    source: str
    created_at: datetime
    updated_at: datetime