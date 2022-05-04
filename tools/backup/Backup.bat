SET PGPASSWORD=postgres
@Echo Off
rem Echo %DATE%
SET M=%DATE:~3,2%
SET D=%DATE:~0,2%
SET Y=%DATE:~6,4%
SET MyD=%D%_%M%_%Y%
rem Echo %MyD%

"C:\Program Files\PostgreSQL\9.6\bin\pg_dump.exe" --host localhost --port 5432 --username postgres --format custom --blobs --verbose --file "..\..\..\JDocServer\backup\jdoc_%MyD%.backup" "jdoc"