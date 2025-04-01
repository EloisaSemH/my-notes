from fastapi import FastAPI
from database import database
from routes import notes
from routes import users

app = FastAPI(
    title="My Notes API",
    description="API to manage notes",
    version="1.0.0"
)

app.include_router(notes.router, prefix="/api")
app.include_router(users.router, prefix="/api")

@app.on_event("startup")
async def startup():
    await database.connect()

@app.on_event("shutdown")
async def shutdown():
    await database.disconnect()
