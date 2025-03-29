from fastapi import APIRouter
from typing import List
from schemas.notes import NoteIn, NoteOut
from crud import notes as crud
from uuid import UUID
from fastapi.responses import JSONResponse

router = APIRouter()

@router.get("/notes", response_model=List[NoteOut])
async def list_notes():
    return await crud.get_all_notes()

@router.post("/notes", response_model=UUID)
async def create(note: NoteIn):
    await crud.create_note(note)
    return JSONResponse(content={"status": "Note created"})

@router.get("/notes/{uuid}", response_model=NoteOut)
async def get_note(uuid: UUID):
    return await crud.get_note(uuid)

@router.put("/notes/{uuid}", response_model=UUID)
async def update(uuid: UUID, note: NoteIn):
    await crud.update_note(uuid, note)
    return JSONResponse(content={"status": "Note updated"})

@router.delete("/notes/{uuid}", response_model=UUID)
async def delete(uuid: UUID):
    await crud.delete_note(uuid)
    return JSONResponse(content={"status": "Note deleted"})