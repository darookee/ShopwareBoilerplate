#!/bin/sh

NEWNAME=${1:-Boilerplate}

echo "Creating Plugin ${NEWNAME}"

find . ! -path "*./.*" -type f -exec sed -i "s/Boilerplate/${NEWNAME}/g" -i "s/boilerplate/${NEWNAME,,}" {} \;
git mv templates/backend/boilerplate templates/backend/${NEWNAME,,} -f
git mv BoilerplateBackend.php ${NEWNAME}Backend.php -f
git mv BoilerplateFrontend.php ${NEWNAME}Frontend.php -f
echo "" > README.md
git rm setup.sh -f
