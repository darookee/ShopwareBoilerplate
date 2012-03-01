#!/bin/sh

NEWNAME=${1:-Boilerplate}

echo "Creating Plugin ${NEWNAME}"

find . ! -path "*./.*" -type f -exec sed -i 's/Boilerplate/${NEWNAME}/g' {} \;
git mv templates/backend/boilerplate templates/backend/${NEWNAME,,} -fr
git mv BoilerplateBackend.php ${NEWNAME}Backend.php
git mv BoilerplateFrontend.php ${NEWNAME}Frontend.php
