@echo off
SET LocalDir = %~dp0
cd %LocalDir%
vagrant destroy --force