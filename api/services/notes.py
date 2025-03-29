from databases import Database
from models.notes import notes
from schemas.notes import NoteIn
from datetime import datetime
import uuid
from uuid import UUID

class NotesService:
    def __init__(self, db: Database):
        self.db = db

    async def get_all_notes(self):
        query = notes.select().order_by(notes.c.created_at.desc())
        return await self.db.fetch_all(query)

    async def create_note(self, note: NoteIn):
        data = note.model_dump()
        data["created_at"] = datetime.now()
        data["updated_at"] = datetime.now()
        data["source"] = "python"
        data["uuid"] = str(uuid.uuid4())
        query = notes.insert().values(**data).returning(notes.c.uuid)
        note_id = await self.db.execute(query)
        return note_id

    async def get_note(self, uuid: UUID):
        query = notes.select().where(notes.c.uuid == uuid)
        return await self.db.fetch_one(query)

    async def update_note(self, uuid: UUID, note: NoteIn):
        data = note.model_dump()
        data["updated_at"] = datetime.now()
        query = notes.update().where(notes.c.uuid == uuid).values(**data)
        return await self.db.execute(query)

    async def delete_note(self, uuid: UUID):
        query = notes.delete().where(notes.c.uuid == uuid)
        return await self.db.execute(query)
