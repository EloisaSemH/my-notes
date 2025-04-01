from databases import Database
from models.users import users
from passlib.context import CryptContext
from schemas.users import UserIn
from datetime import datetime

pwd_context = CryptContext(schemes=["bcrypt"], deprecated="auto")

class UsersService:
    def __init__(self, db: Database):
        self.db = db

    async def get_user(self, email: str):
        query = users.select().where(users.c.email == email)
        return await self.db.fetch_one(query)

    async def create_user(self, user: UserIn):
        data = user.model_dump()
        data["password_hash"] = pwd_context.hash(data["password"])
        data["created_at"] = datetime.now()
        data["updated_at"] = datetime.now()
        del data["password"]
        query = users.insert().values(**data)
        return await self.db.execute(query)
