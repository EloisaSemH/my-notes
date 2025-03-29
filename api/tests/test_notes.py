import pytest
from unittest.mock import AsyncMock
from services.notes import NotesService
from schemas.notes import NoteIn
from uuid import UUID

@pytest.fixture
def mock_service():
    mock_db = AsyncMock()
    return NotesService(mock_db)

@pytest.mark.asyncio
async def test_create_note(mock_service):
    note = NoteIn(title="Test", content="This is a test note.")
    mock_service.db.execute.return_value = "mock-uuid"

    result = await mock_service.create_note(note)
    assert result == "mock-uuid"

@pytest.mark.asyncio
async def test_get_all_notes(mock_service):
    mock_service.db.fetch_all.return_value = [{"title": "Note 1"}, {"title": "Note 2"}]

    result = await mock_service.get_all_notes()
    assert isinstance(result, list)
    assert len(result) == 2

@pytest.mark.asyncio
async def test_get_note(mock_service):
    expected_note = {"uuid": "mock-uuid", "title": "Test"}
    mock_service.db.fetch_one.return_value = expected_note

    result = await mock_service.get_note(UUID("12345678-1234-5678-1234-567812345678"))
    assert result["title"] == "Test"

@pytest.mark.asyncio
async def test_update_note(mock_service):
    note = NoteIn(title="Updated", content="Updated content")
    mock_service.db.execute.return_value = 1

    result = await mock_service.update_note(UUID("12345678-1234-5678-1234-567812345678"), note)
    assert result == 1

@pytest.mark.asyncio
async def test_delete_note(mock_service):
    mock_service.db.execute.return_value = 1

    result = await mock_service.delete_note(UUID("12345678-1234-5678-1234-567812345678"))
    assert result == 1
