@echo off
setlocal enabledelayedexpansion

:: Find Python executable
for /f "delims=" %%i in ('where python') do set PYTHON_PATH=%%i

:: Check if Python is installed
if not defined PYTHON_PATH (
    echo Python not found. Make sure Python is installed and added to the system PATH.
    exit /b 1
)

:: Add Python directory to system PATH
set "PATH=%PATH%;%PYTHON_PATH%"

:: Display success message
echo Python has been added to the system PATH.

endlocal
