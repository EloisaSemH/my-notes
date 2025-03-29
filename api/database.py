import os
import databases
import sqlalchemy

DATABASE_URL = os.getenv("DATABASE_URL", "postgresql://user:pass@postgres:5432/appdb")

database = databases.Database(DATABASE_URL)
metadata = sqlalchemy.MetaData()