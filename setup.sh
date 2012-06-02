#!/bin/sh

NEWNAME=${1:-Boilerplate}
NEWTYPE=${2:-Frontend}

echo "Creating Plugin ${NEWNAME} for ${NEWTYPE}"

if hash git 2>&- /dev/null; then
    git checkout -b ${NEWNAME,,}
fi

sed -i "s/Backend_Boilerplate/${NEWTYPE,,}_Boilerplate/g" Bootstrap.php
find . ! -path "*./.*" -type f -exec sed -i "s/Boilerplate/${NEWNAME}/g" {} \;
find . ! -path "*./.*" -type f -exec sed -i "s/boilerplate/${NEWNAME,,}/g" {} \;
git mv templates/backend/boilerplate templates/backend/${NEWNAME,,} -f
git mv templates/frontend/plugins/boilerplate templates/frontend/plugins/${NEWNAME,,} -f
git mv BoilerplateBackend.php ${NEWNAME}Backend.php -f
git mv BoilerplateFrontend.php ${NEWNAME}Frontend.php -f
echo "Shopware ${NEWTYPE}-Plugin ${NEWNAME}" > README.md
git rm setup.sh -f
git commit -a -m "initial cleanup"
