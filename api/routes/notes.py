from database import database
from dependencies.auth import get_current_user
from fastapi import APIRouter, Depends
from fastapi.responses import JSONResponse
from schemas.notes import NoteIn, NoteOut
from services.notes import NotesService
from typing import List
from uuid import UUID

router = APIRouter()
service = NotesService(database)

@router.get("/notes", response_model=List[NoteOut])
async def list_notes(user_id: int = Depends(get_current_user)):
    return await service.get_all_notes(user_id)

@router.post("/notes", response_model=UUID)
async def create(note: NoteIn, user_id: int = Depends(get_current_user)):
    await service.create_note(note, user_id)
    return JSONResponse(content={"status": "Note created"})

@router.get("/notes/{uuid}", response_model=NoteOut)
async def get_note(uuid: UUID, user_id: int = Depends(get_current_user)):
    note = await service.get_note(uuid, user_id)
    return JSONResponse(content=note)

@router.put("/notes/{uuid}", response_model=UUID)
async def update(uuid: UUID, note: NoteIn, user_id: int = Depends(get_current_user)):
    await service.update_note(uuid, note, user_id)
    return JSONResponse(content={"status": "Note updated"})

@router.delete("/notes/{uuid}", response_model=UUID)
async def delete(uuid: UUID, user_id: int = Depends(get_current_user)):
    await service.delete_note(uuid, user_id)
    return JSONResponse(content={"status": "Note deleted"})