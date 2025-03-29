from database import database
from fastapi import APIRouter
from fastapi.responses import JSONResponse
from schemas.notes import NoteIn, NoteOut
from services.notes import NotesService
from typing import List
from uuid import UUID

router = APIRouter()
service = NotesService(database)

@router.get("/notes", response_model=List[NoteOut])
async def list_notes():
    return await service.get_all_notes()

@router.post("/notes", response_model=UUID)
async def create(note: NoteIn):
    await service.create_note(note)
    return JSONResponse(content={"status": "Note created"})

@router.get("/notes/{uuid}", response_model=NoteOut)
async def get_note(uuid: UUID):
    note = await service.get_note(uuid)
    return JSONResponse(content=note)

@router.put("/notes/{uuid}", response_model=UUID)
async def update(uuid: UUID, note: NoteIn):
    await service.update_note(uuid, note)
    return JSONResponse(content={"status": "Note updated"})

@router.delete("/notes/{uuid}", response_model=UUID)
async def delete(uuid: UUID):
    await service.delete_note(uuid)
    return JSONResponse(content={"status": "Note deleted"})