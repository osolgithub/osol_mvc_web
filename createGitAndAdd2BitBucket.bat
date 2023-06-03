@echo off
setlocal
:PROMPT
rem Git URL eg:  https://userid@bitbucket.org/userid/repository-name.git
set /p remoteRepositoryURL="Enter remote repository URL: or 'q' to quit "
echo "Entered remote repository URL was " %remoteRepositoryURL%
set quitbat=true
IF not "%remoteRepositoryURL%" == "q" IF not "%remoteRepositoryURL%" ==  "Q" set quitbat=false
if "%quitbat%" == "true" goto END
:NOTEND
rem echo "Inside :NOTEND Entered Value was " %ANYKEY%
git init
git add .
git commit -m "First commit"
git remote add origin %remoteRepositoryURL%
git remote -v
git.exe pull --progress -v --no-rebase --allow-unrelated-histories "origin" main
git push origin main
set /p ANYKEY="Successfully added project to GIT, Press any key to continue: "
:END
endlocal