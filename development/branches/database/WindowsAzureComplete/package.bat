@echo OFF

IF "%1"=="" GOTO ParamMissing
IF "%2"=="" GOTO ParamMissing
IF "%3"=="" GOTO ParamMissing

echo Copying default package components to temporary location...
mkdir deploy-temp
xcopy /s /e /h deploy\webrole deploy-temp

echo Copying %1 to temporary location...
xcopy /s /e /h %1 deploy-temp\PhpOnAzure.Web

echo Creating ServiceDefinition.csdef to temporary location...
TYPE .\deploy\templates\ServiceDefinitionBegin.csdef > .\deploy-temp\ServiceDefinition.csdef
FOR /f "tokens=*" %%A in (%3) do (
    ECHO         ^<Setting name="%%A" /^> >> .\deploy-temp\ServiceDefinition.csdef
)
IF "%4"=="/RDP" (
    TYPE .\deploy\templates\ServiceDefinitionWithRDPEnd.csdef >> .\deploy-temp\ServiceDefinition.csdef
) else (
    TYPE .\deploy\templates\ServiceDefinitionEnd.csdef >> .\deploy-temp\ServiceDefinition.csdef
)

echo Packaging application...
"c:\Program Files\Windows Azure SDK\v1.4\bin\cspack.exe" deploy-temp\ServiceDefinition.csdef /role:PhpOnAzure.Web;deploy-temp\PhpOnAzure.Web /out:%2.cspkg

echo Creating ServiceConfiguration.cscfg
TYPE .\deploy\templates\ServiceConfigurationBegin.cscfg > .\ServiceConfiguration.cscfg
FOR /f "tokens=*" %%A in (%3) do (
    ECHO       ^<Setting name="%%A" value="" /^> >> .\ServiceConfiguration.cscfg
)
IF "%4"=="/RDP" (
    TYPE .\deploy\templates\ServiceConfigurationWithRDPEnd.cscfg >> .\ServiceConfiguration.cscfg
) else (
    TYPE .\deploy\templates\ServiceConfigurationEnd.cscfg >> .\ServiceConfiguration.cscfg
)

echo Cleaning up...
rmdir /S /Q deploy-temp

GOTO End

:ParamMissing
echo Invalid arguments. Usage: package.bat application-path package-file-name settings-file [/RDP]

:End
