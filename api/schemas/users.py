from pydantic import BaseModel
from datetime import datetime


class LoginData(BaseModel):
    email: str
    password: str


class UserIn(BaseModel):
    name: str
    email: str
    password: str


class UserOut(UserIn):
    name: str
    email: str
    created_at: datetime
    updated_at: datetime