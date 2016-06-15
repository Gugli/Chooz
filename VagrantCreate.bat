@echo off

SET Putty="C:\Program Files (x86)\PuTTY\putty.exe"
SET LocalDir=%~dp0%
cd %LocalDir%
vagrant up

set SshPort=2222
FOR /F "tokens=1-2 delims= " %%G IN ('vagrant ssh-config deploy') DO (
    if "%%G"=="Port" set SshPort=%%H
)
echo "Port: %SshPort%"
echo "Key: %LocalDir%VagrantInsecureKey.ppk"
start "" /b %Putty% -ssh -P %SshPort% -i "%LocalDir%\VagrantInsecureKey.ppk" vagrant@127.0.0.1

pause