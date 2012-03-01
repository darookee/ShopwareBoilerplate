#!/bin/sh

NEWNAME=${1:-Boilerplate}

echo "Creating Plugin ${NEWNAME}"

if hash git 2>&- /dev/null; then
    git checkout -b ${NEWNAME,,}
fi

find . ! -path "*./.*" -type f -exec sed -i "s/Boilerplate/${NEWNAME}/g" {} \;
find . ! -path "*./.*" -type f -exec sed -i "s/boilerplate/${NEWNAME,,}/g" {} \;
git mv templates/backend/boilerplate templates/backend/${NEWNAME,,} -f
git mv BoilerplateBackend.php ${NEWNAME}Backend.php -f
git mv BoilerplateFrontend.php ${NEWNAME}Frontend.php -f
echo "" > README.md
git rm setup.sh -f

