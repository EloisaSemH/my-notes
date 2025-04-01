from fastapi import APIRouter, HTTPException
from passlib.context import CryptContext
from database import database
from services.users import UsersService
from dependencies.auth import create_access_token
from schemas.users import LoginData, UserIn, UserOut

router = APIRouter()

pwd_context = CryptContext(schemes=["bcrypt"], deprecated="auto")
service = UsersService(database)

@router.post("/login")
async def login(data: LoginData):
    user = await service.get_user(data.email)

    if not user or not pwd_context.verify(data.password, user["password_hash"]):
        raise HTTPException(status_code=401, detail="Invalid credentials")

    token = create_access_token(user["id"])
    user_data = dict(user)
    user_data.pop("password_hash", None)
    return {"token": token, "user": user_data}

@router.post("/register")
async def register(data: UserIn):
    await service.create_user(data)
    return {"message": "User created successfully"}
