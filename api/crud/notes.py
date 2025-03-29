import uuid

from models.notes import notes
from database import database
from uuid import UUID
from uuid import uuid4
from schemas.notes import NoteIn
from datetime import datetime

async def get_all_notes():
    query = notes.select().order_by(notes.c.created_at.desc())
    return await database.fetch_all(query)

async def create_note(note: NoteIn):
    data = note.dict()
    data["created_at"] = datetime.now()
    data["updated_at"] = datetime.now()
    data["source"] = "python"
    data["uuid"] = str(uuid.uuid4())
    query = notes.insert().values(**data)
    return await database.execute(query)

async def get_note(uuid: UUID):
    query = notes.select().where(notes.c.uuid == uuid)
    return await database.fetch_one(query)

async def update_note(uuid: UUID, note: NoteIn):
    data = note.dict()
    data["updated_at"] = datetime.now()
    query = notes.update().where(notes.c.uuid == uuid).values(**data)
    return await database.execute(query)

async def delete_note(uuid: UUID):
    query = notes.delete().where(notes.c.uuid == uuid)
    return await database.execute(query)